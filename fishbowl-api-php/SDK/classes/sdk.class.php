<?php
/**
 * @package : FishbowlSDK
 * @author : dnewsom <dave.newsom@fishbowlinventory.com>
 * @author : kbatchelor <kevin.batchelor@fishbowlinventory.com>
 * @version : 1.0
 * @date : 2010-04-29
 *
 * Utility routines for Fishbowls SDK
 */

class FishbowlSDK {
    public $result;
    public $statusCode;
    public $statusMsg;
    public $loggedIn;
    protected $xmlRequest;
    protected $xmlResponse;
    private $id;
    private $key;

	/**
	 * Create the connection to Fishbowl
	 * @param string $host
	 * @param string $port
	 * @param string $user
	 * @param string $pass
	 */
    public function __construct($host, $port, $user, $pass) {
        $this->host = $host;
        $this->port = $port;
        $this->user = $user;
        $this->pass = base64_encode(md5($pass, true));
        
        $this->id = fsockopen($this->host, $this->port);
    }

    public function extractRowArray($rows) {
            $result = array();
            $rowCount = 0;
            $header = array();
            foreach( $rows as $row ) {
                $row = str_replace('"', '', $row);
                if( $rowCount++ < 1 ) {
                    $header = explode(",", $row);
                } else {
                    $cols = explode(",", $row);
                    $resRow = array();
                    foreach( $cols as $cid => $col ) {
                        $resRow[ $header[$cid] ] = $col;
                    }
                    $result[] = $resRow;
                }
            }
            return $result;
        }
        
    /**
     * Close the connection
     */
    public function closeConnection() {
        fclose($this->id);
    }

    /**
     * Login to Fishbowl
     */
    public function login() {
        // Parse XML
        $this->xmlRequest = "<FbiXml>\n".
			                "    <Ticket/>\n" .
             				"    <FbiMsgsRq>\n" .
			                "        <LoginRq>\n" .
             			    "            <IAID>" . APP_KEY . "</IAID>\n" .
			                "            <IAName>" . APP_NAME . "</IAName>\n" .
             			    "            <IADescription>" . APP_DESCRIPTION . "</IADescription>\n" .
			                "            <UserName>" . $this->user . "</UserName>\n" .
             			    "            <UserPassword>" . $this->pass . "</UserPassword>\n" .
			                "        </LoginRq>\n" .
             			    "    </FbiMsgsRq>\n" .
			                "</FbiXml>";

        // Pack for sending
        $len = strlen($this->xmlRequest);
        $packed = pack("N", $len);

        // Send and get the response
        fwrite($this->id, $packed, 4);
        fwrite($this->id, $this->xmlRequest);
        $this->getResponse();
        
        // Set the result
        $this->setResult($this->parseXML($this->xmlResponse));

        if ($this->statusCode == 1000) {
	        // Set the key
    	    $this->key = $this->result['Ticket']['Key'];
    	    $this->loggedIn = true;
        } else {
        	$this->loggedIn = false;
        }
    }

    /**
     * Get customer information
     * @param string $type
     * @param string $name
     */
    public function getCustomer($type = 'NameList', $name = null) {
        // Setup XML
        if ($type == "Get") {
            $xml = "<CustomerGetRq>\n" .
					"<Name>{$name}</Name>\n" .
					"</CustomerGetRq>\n";
        } elseif ($type == "List") {
            $xml = "<CustomerListRq></CustomerListRq>\n";
        } else {
            $xml = "<CustomerNameListRq></CustomerNameListRq>\n";
        }
        
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
    
	/**
	 * Save the customer information
	 * @param string $customerName
	 * @param string $customerNumber
	 * @param string $active
	 * @param string $address
	 * @param string $city
	 * @param string $zip
	 * @param string $type
	 * @param string $state
	 * @param string $phone
	 * @param string $email
	 */
	public function saveCustomer($customerName = "Kevin", $customerNumber = "734", $active = "true", $address ="test", $city="amarillo", $zip="12345", $type="Main Office", $state = 'UT', $phone="800-555-5555", $email="email") {
        // Setup XML
		$xml = "              <CustomerSaveRq>\n".
		        "                  <Customer>\n".
				"						<CustomerID>-1</CustomerID>\n".
				"						<AccountID>-1</AccountID>\n".
				"						<Status>Normal</Status>\n".
				"						<DefPaymentTerms>COD</DefPaymentTerms>\n".
				"						<DefShipTerms>Prepaid &amp; Billed</DefShipTerms>\n".
				"						<TaxRate>None</TaxRate>\n".
				"                        <Name>{$customerName}</Name>\n".
		        "                        <Number>{$customerNumber}</Number>\n".
				"                        <ActiveFlag>{$active}</ActiveFlag>\n".
				"						 <JobDepth>1</JobDepth>\n".
				"							<Addresses>\n".
				"							  <Address>\n".
				"								<ID>-1</ID>\n".
				"								<AccountId>-1</AccountId>\n".
				"								<Name>{$customerName}</Name>\n".
				"								<Attn>{$customerName}</Attn>\n".
				"								<Street>{$address}</Street>\n".
				"								<City>{$city}</City>\n".
				"								<Zip>{$zip}</Zip>\n".
				"								<Default>true</Default>\n".
				"								<Residential>false</Residential>\n".
				"								<Type>{$type}</Type>\n".
				"								<State>\n".
				"								  <Code>{$state}</Code>\n".
				"								  <CountryID>2</CountryID>\n".
				"								</State>\n".
				"								<Country>\n".
				"								  <ID>2</ID>\n".
				"								  <Name>UNITED STATES</Name>\n".
				"								  <Code>US</Code>\n".
				"								</Country>\n".
				"								<AddressInformationList>\n".
				"								  <AddressInformation>\n".
				"									<ID>-1</ID>\n".
				"									<Name>{$customerName}</Name>\n".
				"									<Data>{$phone}</Data>\n".
				"									<Default>true</Default>\n".
				"									<Type>Main</Type>\n".				
				"								  </AddressInformation>\n".
				"								  <AddressInformation>\n".
				"									<ID>-1</ID>\n".
				"									<Name>{$customerName}</Name>\n".
				"									<Data>{$email}</Data>\n".
				"									<Default>true</Default>\n".
				"									<Type>Email</Type>\n".				
				"								  </AddressInformation>\n".
				"								</AddressInformationList>\n".
				"							  </Address>\n".
				"							</Addresses>\n".
				"                  </Customer>\n".
				"              </CustomerSaveRq>\n";

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
	
    /**
     * Get vendor information
     * @param string $type
     * @param string $name
     */
    function getVendor($type = 'NameList', $name = null) {
        if ($type == "Get") {
            $xml = "<VendorGetRq>\n<Name>{$name}</Name>\n</VendorGetRq>\n";
        } elseif ($type == "List") {
            $xml = "<VendorListRq></VendorListRq>\n";
        } else {
            $xml = "<VendorNameListRq></VendorNameListRq>\n";
        }

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

    /**
     * Get product information
     * @param string $type
     * @param string $productNum
     * @param integer $getImage
     * @param string $upc
     */
    public function getProducts($type = 'Get', $productNum = 'B201', $getImage = 0, $upc = null) {
        // Setup XML
        if ($type == "Get") {
            $xml = "<ProductGetRq>\n" .
                   "    <Number>{$productNum}</Number>\n" .
                   "    <GetImage>{$getImage}</GetImage>\n" .
                   "</ProductGetRq>\n";
        } elseif ($type == "Query") {
            $xml = "<ProductQueryRq>\n";
                if ($upc != null) {
                    $xml .= "    <UPC>{$upc}</UPC>\n";
                } else {
                    $xml .= "    <ProductNum>{$productNum}</ProductNum>\n";
                }
            $xml .= "    <GetImage>{$getImage}</GetImage>\n" .
                    "</ProductQueryRq>\n";
        }

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
    
	/**
	 * Get list of SO's by location group
	 * @param string $LocationGroup
	 */
	public function getSOList($LocationGroup = 'SLC') {
		// Parse XML
		$xml = "<GetSOListRq>\n<LocationGroup>{$LocationGroup}</LocationGroup>\n</GetSOListRq>\n";

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
	
	/**
	 * Loads SO for a given number
	 * @param string $number
	 */
	public function getSO($number = '50032') {
		// Parse XML
		$xml = "<LoadSORq>\n<Number>{$number}</Number>\n</LoadSORq>\n";
		
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
    
	/**
	 * Saves a Sales Order with items
	 * @param $data array
	 */
	public function saveSO() {
        // Setup XML
                        $xml =  "<FbiXml>".
                                "    <Ticket>".
                                "        <Key></Key>".
                                "    </Ticket>".
                                "<FbiMsgsRq>".                                              
                                "              <SOSaveRq>\n".
				"					<SalesOrder>\n".
				"						<Salesman>admin</Salesman>\n".
				"						<Number>60312</Number>\n".
				"						<Status>10</Status>\n".
				"						<Carrier>Will Call</Carrier>\n".
				"						<FirstShipDate>2016-08-29T00:00:00</FirstShipDate>\n".
				"						<CreatedDate>2016-08-29T00:00:00</CreatedDate>\n".
				"						<IssuedDate>2016-08-29T16:48:56</IssuedDate>\n".
				"						<TaxRatePercentage>0.0625</TaxRatePercentage>\n".
				"						<TaxRateName>Utah</TaxRateName>\n".
				"						<ShippingTerms>Prepaid  </ShippingTerms>\n".
				"						<PaymentTerms>COD</PaymentTerms>\n".
				"						<CustomerContact>Beach Bike</CustomerContact>\n".
				"						<CustomerName>Beach Bike</CustomerName>\n".
				"						<FOB>Origin</FOB>\n".
				"						<QuickBooksClassName>Salt Lake City</QuickBooksClassName>\n".
				"						<LocationGroup>AYC</LocationGroup>\n".				
				"						<BillTo>\n".
				"						  <Name>test</Name>\n".
                                "                                                 <AddressField>555 Suntan Ave.</AddressField>".
				"                                                 <City>Santa Barbara</City>".
				"                                          	  <Zip>93101</Zip>".                        
				"						</BillTo>\n".
				"						<Ship>\n".
				"						  <Name>test</Name>\n".
                                "                                                 <AddressField>555 Suntan Ave.</AddressField>".
				"                                          	  <Zip>93101</Zip>".
				"                                                 <Country>US</Country>".      
				"                                                 <State>California</State>".                          
				"						</Ship>\n";
		//foreach ($data['soitems'] AS $key=>$value) {
			$xml .="					<SalesOrderItem>\n
										  <ID>-1</ID>\n
										  <ProductNumber>00-KIN-APPHDR-057</ProductNumber>\n
                                                                                  <SOID>94</SOID>\n
                                                                                  <Description>Battery Pack</Description>
                                                                                  <Taxable>true</Taxable>\n 
										  <Quantity>1</Quantity>\n
                                                                                  <ProductPrice>-95.00</ProductPrice>
                                                                                  <TotalPrice>-95.00</TotalPrice>
                                                                                  <UOMCode>ea</UOMCode>
										  <ItemType>20</ItemType>\n
										  <QuickBooksClassName>Salt Lake City</QuickBooksClassName>\n
										  <NewItemFlag>false</NewItemFlag>\n
                                                                                  <LineNumber>2</LineNumber>
										</SalesOrderItem>\n";
		//}
		$xml .="					  </SalesOrder>\n".
                        "<IssueFlag>false</IssueFlag>".
			"<IgnoreItems>false</IgnoreItems>".
                        "</SOSaveRq>\n".
                        "</FbiMsgsRq>";
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
	
    /**
     * Get part information. Can be search by either PartNum or UPC
     * @param string $partNum (optional)
     * @param string $upc (optional)
     */
    public function getPart($partNum, $upc) {
    	// Setup xml
    	$xml = "<PartGetRq>\n";
    	if (!is_null($partNum)) {
    		$xml .= "<Number>{$partNum}</Number>\n";
    	} else {
    		$xml .= "<Number>{$upc}</Number>\n";
    	}
    	$xml .= "</PartGetRq>\n";
		
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
    
    /**
     * Get inventory quantity information for a part
     * $param string $partNum
     */
    public function getInvQty($partNum) {
    	// Setup xml
    	$xml = "<InvQtyRq>\n<PartNum>{$partNum}</PartNum>\n</InvQtyRq>\n";
		
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

    /**
     * Get tax rate list
     */
    public function getTaxRates() {
        // Parse XML
        $xml = "<TaxRateGetRq></TaxRateGetRq>\n";
		
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

    /**
     * Export Request
     * @param string $type
     */
    public function SDKExport($type) {
        // Parse XML
        $xml = "<ExportRq>\n<Type>{$type}</Type>\n</ExportRq>\n";
		
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

    /**
     * Export List Request
     */
    public function SDKExportList() {
        // Parse XML
        $xml = "<ExportListRq></ExportListRq>\n";
		
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

    /**
     * Parse xml data and store the results
     */
	protected function parseXML($xml, $recursive = false, $cust = false) {
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
	    return $newArray;
	}
	
	/**
	 * Set the XML Request
	 * @param string $xmlData
	 */
	protected function createRequest($xmlData) {
		$this->xmlRequest = $this->xmlHeader() . $xmlData . $this->xmlFooter();
	}
	
	/**
	 * Create XML header
	 */
	private function xmlHeader() {
        $xml = "<FbiXml>\n<Ticket>\n<UserID>1</UserID>\n<Key>{$this->key}</Key>\n</Ticket>\n<FbiMsgsRq>\n";
        return $xml;
	}
	
	/**
	 * Create XML foorter
	 */
	private function xmlFooter() {
        $xml = "</FbiMsgsRq>\n</FbiXml>\n";
		return $xml;
	}
	
	/**
	 * Determine the length (in bytes) of our reponse and stream it.
	 */
	private function getResponse() {
		$packed_len = stream_get_contents($this->id, 4); //The first 4 bytes contain our N-packed length
		$hdr = unpack('Nlen', $packed_len);
		$len = $hdr['len'];
		$this->xmlResponse = stream_get_contents($this->id, $len);
	}
	
	/**
	 * Set the results from a response
	 */
	private function setResult($res) {
		$this->result = $res;
		$this->statusCode = $this->result['FbiMsgsRs']['@attributes']['statusCode'];
		//$this->statusMsg = $this->result['FbiMsgsRs']['@attributes']['statusMessage'];
	}
}

?>