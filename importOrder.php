<?php
    require_once("fbErrorCodes.class.php");
    require_once("fishbowlAPI.class.php");
    define('FISHBOWL_INI', "fishbowl.ini");
    /**
     * Inventory Class for Fishbowl Integration
     * @author Clement Yu
     */
    class importOrder {
        public $ini_array;
        public $fbApi;
        
        function __construct() {
            $this->ini_array = parse_ini_file(FISHBOWL_INI);
            define('APP_KEY', $this->ini_array['APP_KEY']);
            define('APP_NAME', $this->ini_array['APP_NAME']);
            define('APP_DESCRIPTION', $this->ini_array['APP_DESCRIPTION']);
            
            $this->fbApi = new FishbowlAPI($this->ini_array['HOST'], $this->ini_array['PORT']);

            $this->fbApi->Login($this->ini_array['USER'], $this->ini_array['PASSWORD']);
            if (!$this->fbApi->checkAccessRights("Sales Order", "View")) {
                throw new Exception('You do not have access to use that function.');
            }

            if ($this->fbApi->statusCode != 1000) {
                throw new Exception('Error connecting to Fishbowl!');
            }
            $this->id = $this->fbApi->id;
            
        }
        private function createRequest($xmlData) {
		$this->xmlRequest = $this->xmlHeader() . $xmlData . $this->xmlFooter();
	}
        private function getResponse() {
		$packed_len = stream_get_contents($this->id, 4); //The first 4 bytes contain our N-packed length
		$hdr = unpack('Nlen', $packed_len);
		$len = $hdr['len'];
		$this->xmlResponse = stream_get_contents($this->id, $len);
	}
        private function xmlHeader() {
        $xml = "<FbiXml>\n<Ticket>\n<Key>{$this->fbApi->key}</Key>\n</Ticket>\n<FbiMsgsRq>\n";
        return $xml;
        }
	
	/**
	 * Create XML foorter
	 */
	private function xmlFooter() {
		$xml = "</FbiMsgsRq>\n</FbiXml>\n";
		return $xml;
	}
        
        private function setResult($res) {
		$this->result = $res;
	}
        
        private function parseXML($xml, $recursive = false, $cust = false) {
		if (!$recursive) {
			$array = simplexml_load_string($xml);
		} else {
			$array = $xml;
		}
	
		$newArray = array();
		$array = (array) $array;

		foreach ($array as $key=>$value) {
			$value = (array) $value;
			if (isset($value[0])) {
				if (count($value) > 1) {
					$newArray[$key] = (array) $value;
				} else {
					$newArray[$key] = trim($value[0]);
				}
			} else {
				$newArray[$key] = $this->parseXML($value, true);
			}
		}
		if (!isset($newArray['statusMessage'])) {
			$newArray['statusMessage'] = "null";
		}
		return $newArray;
	}
        
        public function saveSO() {
        // Setup XML
                        $xml =                                               
                                "              <SOSaveRq>\n".
				"					<SalesOrder>\n".
				"						<Salesman>admin</Salesman>\n".
				"						<Number>99999</Number>\n".
                                "                                               <CustomerPO>123123123</CustomerPO>".
				"						<Status>10</Status>\n".
				"						<Carrier>UPS GROUND</Carrier>\n".
				//"						<FirstShipDate>2016-08-29T00:00:00</FirstShipDate>\n".
				"						<CreatedDate>2016-08-29T00:00:00</CreatedDate>\n".
				//"						<IssuedDate>2016-08-29T16:48:56</IssuedDate>\n".
				//"						<TaxRatePercentage>0.0625</TaxRatePercentage>\n".
				"						<TaxRateName>Sales Tax</TaxRateName>\n".
				"						<ShippingTerms>Prepaid</ShippingTerms>\n".
				"						<PaymentTerms>Prepaid</PaymentTerms>\n".
				"						<CustomerContact>MarketPlace - Amazon</CustomerContact>\n".
				"						<CustomerName>MarketPlace - Amazon</CustomerName>\n".
				"						<FOB>Origin</FOB>\n".
				"						<QuickBooksClassName> </QuickBooksClassName>\n".
				"						<LocationGroup>AYC</LocationGroup>\n".				
				"                                           <BillTo>\n".
				"						  <Name>MarketPlace - Madison and Park</Name>\n".
                                "                                                 <AddressField>Customers' Direct Source</AddressField>".
				"                                                 <City>Garland</City>".
				"                                          	  <Zip>75041</Zip>".
				"                                                 <Country>US</Country>".      
				"                                                 <State>Texas</State>".                        
				"                                           </BillTo>\n".
				"                                           <Ship>\n".
				"						  <Name>test</Name>\n".
                                "                                                 <AddressField>test address</AddressField>".
				"                                                 <City>Glendale</City>".     
				"                                          	  <Zip>91205</Zip>".
				"                                                 <Country>US</Country>".      
				"                                                 <State>California</State>".                     
				"                                           </Ship>\n".
                                "                                           <Items>\n".
                                "                                               <SalesOrderItem>\n".
				//"						  <ID>10765</ID>\n".
				"						  <ProductNumber>00-KIN-APPHDR-057</ProductNumber>\n".
                                //"                                                 <SOID>10765</SOID>\n".
                                //"                                                 <Description>Appliance Holder - Crimped</Description>".
                                "                                                 <Taxable>false</Taxable>\n".
				"						  <Quantity>1</Quantity>\n".
                                "                                                 <ProductPrice>0.95</ProductPrice>".
                                //"                                                 <TotalPrice>0.95</TotalPrice>".
                                "                                                 <UOMCode>ea</UOMCode>".
				"						  <ItemType>10</ItemType>\n".
				"						  <QuickBooksClassName> </QuickBooksClassName>\n".
				"						  <NewItemFlag>false</NewItemFlag>\n".
                                //"                                                 <LineNumber>2</LineNumber>".
				"						</SalesOrderItem>\n".
                                "                                           </Items>\n".
                                "                                       </SalesOrder>\n".
                                "                                       <IssueFlag>false</IssueFlag>".
                                "                                       <IgnoreItems>false</IgnoreItems>".
                                "               </SOSaveRq>\n";
                        
		// Create request and pack
		$this->createRequest($xml);
        $len = strlen($this->xmlRequest);
        $packed = pack("N", $len);

        // Send and get the response
        fwrite($this->id, $packed, 4);
        fwrite($this->id, $this->xmlRequest);
        $this->getResponse();

        // Set the result
        $this->setResult($this->parseXML($this->xmlResponse));
    }
        
    }