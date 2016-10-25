<?php
    
    //require_once dirname(__FILE__) . '/';
    class UPS {

        private $config;
        private $allowedApis = array( 
            "AV", "FREIGHTRATE", "LBRECOVERY", "RATE", "SHIP", "TRACK", "VOID"
        );
        private $isProduction = true;
        private $parameters = array();
        
        function __construct() {
            $this->config = parse_ini_file( dirname(__file__) . "/upswebapi.ini", true);
        }

        public function call($apiPath,$method,$parameters)
        {
            ob_start();
            $curl_request = curl_init();
            curl_setopt($curl_request, CURLOPT_URL, $this->generateUrl($apiPath));
            curl_setopt($curl_request, CURLOPT_POST, 1);
            curl_setopt($curl_request, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
            curl_setopt($curl_request, CURLOPT_HEADER, 1);
            curl_setopt($curl_request, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl_request, CURLOPT_FOLLOWLOCATION, 0);
            curl_setopt($curl_request, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            //curl_setopt($curl_request, CURLOPT_USERPWD, $this->config['UPS_USER'].":".$this->config['UPS_PASSWORD']);

            $jsonEncodedData = json_encode($parameters);

            curl_setopt($curl_request, CURLOPT_POSTFIELDS, $jsonEncodedData);
            $result = curl_exec($curl_request);
            curl_close($curl_request);

            $result = explode("\r\n\r\n", $result, 2);
            $response = json_decode($result[1]);
            ob_end_flush();

            return $response;
        }
        
        
        private function generateUrl( $apiPath ) {
            if( $this->isProduction ) {
                $url = $this->config["API"]["PROD_DOMAIN"];
            } else {                
                $url = $this->config["API"]["TEST_DOMAIN"];
            }
            if(in_array($apiPath, $this->allowedApis) 
                && isset($this->config["API"][$apiPath . "_PATH"])) {
                $url .= $this->config["API"][$apiPath . "_PATH"];
            }
            return $url;
            
        }

        private function setLoginParameters() {
            $parameters = array(
                "UsernameToken" => array(
                    "Username" => $this->config["SECURITY"]["USER"],
                    "Password" => $this->config["SECURITY"]["PASS"]
                ),
                "ServiceAccessToken" => array( 
                    "AccessLicenseNumber" => $this->config["SECURITY"]["LICENSE"]
                )
            );
            return $parameters;
        }


        
        function setRateRequestForImport( $requestVar ) {
            require_once dirname(__file__) . "\Request\multiRequestForImport.php";
            $rateRequest = new RateRequest();
            $rateRequest->setRequest($requestVar["context"]);
            $rateRequest->setShipper($rateRequest->getShipperInfo());
            $rateRequest->setShipmentByType("ShipTo", $rateRequest->getShipToInfoForImport($requestVar["shipInfo"]));
            $rateRequest->setShipmentByType("ShipFrom", $rateRequest->getShipFromInfo());
            $rateRequest->setService($rateRequest->getServiceInfo());
            $rateRequest->setPackage($rateRequest->getPackageInfoForImport($requestVar["productInfo"]));
            $rateRequest->allowNegotiatedRates();
            
            
            $this->parameters = array(
                "UPSSecurity" => $this->setLoginParameters(),
                "RateRequest" => $rateRequest->getRequest()
            );
            
            print_r($this->parameters);
        }
        
        function getRateRequest($json = true){
            return $json ? json_encode($this->parameters) : $this->parameters;
        }
        
        function setTrackRequest($requestVar ){
            
            require dirname(__file__) . "\Request\TrackRequest.php";
            $trackRequest = new TrackRequest();
            $trackRequest->setRequest($requestVar["context"]);
            $trackRequest->setInquiryNumber($requestVar["number"]);
            
            $this->parameters = array(
                "UPSSecurity" => $this->setLoginParameters(),
                "TrackRequest" => $trackRequest->getRequest()
            );
            print_r($this->parameters);

        }
        
        function getTrackRequest($json = true){
            return $json ? json_encode($this->parameters) : $this->parameters;
        }             
        
        function invokeTrackRequest() {
            
            $trackResponse = $this->call("TRACK", "POST", $this->parameters);
            return $trackResponse;
        }
        
        function invokeRateRequest() {
            
            $rateResponse = $this->call("RATE", "POST", $this->parameters);
            return $rateResponse;
        }
        
        function getConfig() {
            return $this->config;
        }

    }

?>