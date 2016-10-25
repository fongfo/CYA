<?php
            require_once dirname(__file__) . "/../../SolidCommerce/FBSalesOrder.php";
            require_once dirname(__file__) . "/../../SolidCommerce/searchOrder.php";
            define('SCSKU_INI', "/../../SolidCommerce/scSku_map.ini");
            define('KIT_INI', "/../../SolidCommerce/kit_map.ini");


    class RateRequest {
        private $request;
        private $rate_ini;
        private $allowedShipTypes = array( "ShipTo", "ShipFrom" );
        private $soInfo;
        private $SCObj; 
        private $scSku;
        private $kitSku;

        public function __construct() {
            $this->rate_ini = parse_ini_file( dirname(__file__) . "/../upswebapi.ini", true);
            $this->soInfo = new FBSalesOrder();
            $this->SCObj = new searchOrder();
            $this->scSku = parse_ini_file(SCSKU_INI,true);
            $this->kitSku = parse_ini_file(KIT_INI,true);
            
            $this->request = array(        
                "Request" => null,
                "Shipment" => array(
                    "Shipper" => null,
                    "ShipTo" => null,
                    "ShipFrom" => null,
                    "Service" => null,
                    "Package" => null
                )
            );
        }
        
        public function setRequest( $context ) {
            $this->request["Request"] = array( 
                "RequestOption" => "Rate",
                "TransactionReference" => array( 
                    "CustomerContext" => $context
                )
            );
        }
        
        public function setShipper($shipperInfo ) {
            $this->request["Shipment"]["Shipper"] = array();
            $shipper = &$this->request["Shipment"]["Shipper"];
            $shipper["Name"] = $shipperInfo["name"];
            $shipper["ShipperNumber"] = $shipperInfo["number"];
            $shipper["Address"] = array();
            foreach($shipperInfo["AddressLine"] as $line) {
                $shipper["Address"]["AddressLine"][] = $line;
            }
            $shipper["Address"]["City"] = $shipperInfo["city"];
            $shipper["Address"]["StateProvinceCode"] = $shipperInfo["state"];
            $shipper["Address"]["PostalCode"] = $shipperInfo["zip"];
            $shipper["Address"]["CountryCode"] = $shipperInfo["country"];
        }
        
        public function setShipmentByType( $shipType, $shipInfo ) {
            if( in_array( $shipType, $this->allowedShipTypes ) ) {
                $this->request["Shipment"][$shipType] = array();
                $shipment = &$this->request["Shipment"][$shipType];
            } else {
                throw new Exception("Shipment type not valid!");                
            }
            
            $shipment["Name"] = $shipInfo["name"];
            $shipment["Address"] = array();
            foreach($shipInfo["AddressLine"] as $line) {
                $shipment["Address"]["AddressLine"][] = $line;
            }
            $shipment["Address"]["City"] = $shipInfo["city"];
            $shipment["Address"]["StateProvinceCode"] = $shipInfo["state"];
            $shipment["Address"]["PostalCode"] = $shipInfo["zip"];
            $shipment["Address"]["CountryCode"] = $shipInfo["country"];
        }
        
        public function setService( $serviceInfo ) {
            $this->request["Shipment"]["Service"] = array(
                "Code" => $serviceInfo["code"],
                "Description" => $serviceInfo["description"]
            );
        }
        
        public function setPackage( $packageInfo ) {
            $this->request["Shipment"]["Package"] = array(
                "PackagingType" => array(
                    "Code" => $packageInfo["pkg_code"],
                    "Description" => $packageInfo["pkg_description"]
                ),
                "Dimensions" => array(
                    "UnitOfMeasurement" => array(
                        "Code" => $packageInfo["dim_code"],
                        "Description" => $packageInfo["dim_description"]
                    ),
                    "Length" => $packageInfo["dim_l"],
                    "Width" => $packageInfo["dim_w"],
                    "Height" => $packageInfo["dim_h"],
                ),
                "PackageWeight" => array(
                    "UnitOfMeasurement" => array(
                        "Code" => $packageInfo["weight_code"],
                        "Description" => $packageInfo["weight_description"]
                    ),
                    "Weight" => $packageInfo["weight"]
                )
            );
        }
        
        public function allowNegotiatedRates() {
            $this->request["Shipment"]["ShipmentRatingOptions"] = array(
                "NegotiatedRatesIndicator" => ""
            );
        }
        
        public function getRequest() {
            return $this->request;
        }
        
        public function getShipperInfo(){
            $shipperInfo = array(        
              "name"=>$this->rate_ini["SHIPPER"]['SHIPPER_NAME'],
              "number"=> $this->rate_ini["SHIPPER"]['SHIPPER_NUMBER'],              
              "AddressLine"=>array(
              "AddressLine"=>$this->rate_ini["SHIPPER"]['SHIPPER_ADDRESS']
    
              ),
              "city"=>$this->rate_ini["SHIPPER"]['SHIPPER_CITY'],
              "state"=> $this->rate_ini["SHIPPER"]['SHIPPER_StateProvinceCode'],
              "zip"=> $this->rate_ini["SHIPPER"]['SHIPPER_POSTALCODE'],
              "country"=>$this->rate_ini["SHIPPER"]['SHIPPER_COUNTRYCODE']                               
          );
            return $shipperInfo;
        }
        
        public function getShipFromInfo(){
            $shipFromInfo = array(        
              "name"=>$this->rate_ini["SHIPPER"]['SHIPPER_NAME'],           
              "AddressLine"=>array(
              "AddressLine"=>$this->rate_ini["SHIPPER"]['SHIPPER_ADDRESS']
              ),
              "city"=>$this->rate_ini["SHIPPER"]['SHIPPER_CITY'],
              "state"=> $this->rate_ini["SHIPPER"]['SHIPPER_StateProvinceCode'],
              "zip"=> $this->rate_ini["SHIPPER"]['SHIPPER_POSTALCODE'],
              "country"=>$this->rate_ini["SHIPPER"]['SHIPPER_COUNTRYCODE']                               
          );
            return $shipFromInfo;
        }
        
        public function getShipToInfo($soNum){

            
            $scOrder = $this->SCObj->getSCOrder($soNum);
            $sku = isset($this->scSku['SC-FB'][$scOrder['OrderItems']['OrderItem']['SKU']])?$this->scSku['SC-FB'][$scOrder['OrderItems']['OrderItem']['SKU']]:$scOrder['OrderItems']['OrderItem']['SKU'];
           
            
            $shipToInfo = array(        
              "name"=>$scOrder['BuyerName'],           
              "AddressLine"=>array(
              "AddressLine"=>$scOrder['ShipToStreet1']
              ),
              "city"=>$scOrder['ShipToCity'],
              "state"=>$scOrder['ShipToState'],
              "zip"=>$scOrder['ShipToZip'],
              "country"=>$scOrder['ShipToCountry']                               
          );
            return $shipToInfo;
        }
        
        public function getPackageInfo($soNum){

            
            $scOrder = $this->SCObj->getSCOrder($soNum);
            $sku = isset($this->scSku['SC-FB'][$scOrder['OrderItems']['OrderItem']['SKU']])?$this->scSku['SC-FB'][$scOrder['OrderItems']['OrderItem']['SKU']]:$scOrder['OrderItems']['OrderItem']['SKU'];
            
            $this->soInfo->fbApi->getProducts('Get', $sku, 0, null) ;
            //print_r( $soInfo->fbApi->result['FbiMsgsRs']['ProductGetRs']);
            $weight=$this->soInfo->fbApi->result['FbiMsgsRs']['ProductGetRs']['Product']['Weight'];
            $height=$this->soInfo->fbApi->result['FbiMsgsRs']['ProductGetRs']['Product']['Height'];
            $width=$this->soInfo->fbApi->result['FbiMsgsRs']['ProductGetRs']['Product']['Width'];
            $length=$this->soInfo->fbApi->result['FbiMsgsRs']['ProductGetRs']['Product']['Len'];
            
            $packageInfo = array(        
                "pkg_code"=>'02',
                "pkg_description"=>"Rate",                               
                "dim_code"=>'IN',
                "dim_description"=>'iNCHES',                   
                "dim_l"=>$length,
                "dim_w"=>$width,
                "dim_h"=>$height,
                "weight_code"=>"Lbs",
                "weight_description"=>'pounds',
                "weight"=>$weight                               
          );
            return $packageInfo;
        }
        
        public function getServiceInfo(){

            $serviceInfo = array(
              "code"=>$this->rate_ini["SHIPPER"]['UPS_GROUND_CODE'],
              "description"=>$this->rate_ini["SHIPPER"]['UPS_GROUND_DES']
          );
            return $serviceInfo;
        }
        
        
        
    }
    
?>
