<?php

    require_once("fbErrorCodes.class.php");
    require("fishbowlAPI.class.php");
    define('FISHBOWL_INI', "fishbowl.ini");

class FBSalesOrder {

    public $ini_array;
    public $fbApi;
    public $result;
    //public $id;
    private $salesMan;
    private $notes;
    private $soNumber;
    private $customerPO;
    private $Status;
    private $Carrier;
    private $firstShipDate;
    private $createdDate;
    private $issuedDate;
    private $taxRatePercentage;
    private $taxRateName;
    private $shippingTerms;
    private $paymentTerms;
    private $customerContact;
    private $customerName;
    private $FOB;
    private $quickBooksClassName;
    private $locationGroup;
    private $billToName;
    private $billToAddressField;
    private $billToCity;
    private $billToState;
    private $billToCountry;
    private $billToZip;
    private $shipToName;
    private $shipToAddressField;
    private $shipToCity;
    private $shipToCountry;
    private $shipToState;
    private $shipToZip;
    private $productId;
    private $productNum;
    private $soID;
    private $description;
    private $taxable;
    private $quantity;
    private $productPrice;
    private $totalPrice;
    private $UOMCode;
    private $itemType;
    private $productStatus;
    private $productQuickBooksClassName;
    private $newItemFlag;
    private $lineNumber;
    private $issueFlag;
    private $ignoreItem;
    private $kitItem;
    private $xmlResponse;
    
    
    public function __construct() {
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
        
    public function getSalesMan() {
        return $this->salesMan;
    }
    
    public function setSalesMan($salesMan) {
        $this->salesMan = $salesMan;
    }

    public function getNotes() {
        return $this->notes;
    }
    
    public function setNotes($notes) {
        return $this->notes = $notes;
    }
    
    public function getSoNumber() {
        return $this->soNumber;
    }

    public function setSoNumber($soNumber){
        $this->soNumber = $soNumber;
    }
    
    public function getCustomerPO() {
        return $this->customerPO;
    }

    public function setCustomerPO($customerPO) {
        $this->customerPO = $customerPO;
    }

    public function getStatus() {
        return $this->Status;
    }

    public function setStatus($Status) {
        $this->Status = $Status;
    }

    public function getCarrier() {
        return $this->Carrier;
    }

    public function setCarrier($Carrier) {
        $this->Carrier = $Carrier;
    }

    public function getFirstShipDate() {
        return $this->firstShipDate;
    }

    public function setFirstShipDate($firstShipDate) {
        $this->firstShipDate = $firstShipDate;
    }
    
    public function getCreatedDate() {
        return $this->createdDate;
    }

    public function setCreatedDate($createdDate) {
        $this->createdDate = $createdDate;
    }

    public function getIssuedDate() {
        return $this->issuedDate;
    }

    public function setIssuedDate($issuedDate) {
        $this->issuedDate = $issuedDate;
    }
    
    public function getTaxRatePercentage() {
        return $this->taxRatePercentage;
    }

    public function setTaxRatePercentage($taxRatePercentage) {
        $this->taxRatePercentage = $taxRatePercentage;
    }

    public function getTaxRateName() {
        return $this->taxRateName;
    }

    public function setTaxRateName($taxRateName) {
        $this->taxRateName = $taxRateName;
    }
    
    public function getShippingTerms() {
        return $this->shippingTerms;
    }

    public function setShippingTerms($shippingTerms) {
        $this->shippingTerms = $shippingTerms;
    }

    public function getPaymentTerms() {
        return $this->paymentTerms;
    }

    public function setPaymentTerms($paymentTerms) {
        $this->paymentTerms = $paymentTerms;
    }

    public function getCustomerContact() {
    return $this->customerContact;
    }

    public function setCustomerContact($customerContact) {
        $this->customerContact = $customerContact;
    }

    public function getCustomerName() {
        return $this->customerName;
    }

    public function setCustomerName($customerName) {
        $this->customerName = $customerName;
    }
    
    public function getFOB() {
        return $this->FOB;
    }

    public function setFOB($FOB) {
        $this->FOB = $FOB;
    }

    public function getQuickBooksClassName() {
        return $this->quickBooksClassName;
    }

    public function setQuickBooksClassName($quickBooksClassName) {
        $this->quickBooksClassName = $quickBooksClassName;
    }

    public function getLocationGroup() {
        return $this->locationGroup;
    }

    public function setLocationGroup($locationGroup) {
        $this->locationGroup = $locationGroup;
    }

    public function getBillToName() {
        return $this->billToName;
    }

    public function setBillToName($billToName){
        $this->billToName = $billToName;
    }
    
    public function getBillToAddressField() {
        return $this->billToAddressField;
    }

    public function setBillToAddressField($billToAddressField) {
        $this->billToAddressField = $billToAddressField;
    }

    public function getBillToCity() {
        return $this->billToCity;
    }

    public function setBillToCity($billToCity) {
        $this->billToCity = $billToCity;
    }

    public function getBillToState() {
        return $this->billToState;
    }

    public function setBillToState($billToState) {
        $this->billToState = $billToState;
    }

    public function getBillToCountry() {
        return $this->billToCountry;
    }

    public function setBillToCountry($billToCountry) {
        $this->billToCountry = $billToCountry;
    }
    
    public function getBillToZip() {
        return $this->billToZip;
    }

    public function setBillToZip($billToZip) {
        $this->billToZip = $billToZip;
    }

    public function getShipToName() {
        return $this->shipToName;
    }

    public function setShipToName($shipToName) {
        $this->shipToName = htmlspecialchars($shipToName);
    }
    
    public function getShipToAddressField() {
        return $this->shipToAddressField;
    }

    public function setShipToAddressField($shipToAddressField) {
        $this->shipToAddressField = htmlspecialchars($shipToAddressField);
    }
    
    public function getShipToCity() {
        return $this->shipToCity;
    }

    public function setShipToCity($shipToCity) {
        $this->shipToCity = $shipToCity;
    }

    public function getShipToCountry() {
        return $this->shipToCountry;
    }

    public function setShipToCountry($shipToCountry) {
        $this->shipToCountry = $shipToCountry;
    }
    
    public function getShipToState() {
        return $this->shipToState;
    }

    public function setShipToState($shipToState) {
        $this->shipToState = $shipToState;
    }

    public function getShipToZip() {
        return $this->shipToZip;
    }

    public function setShipToZip($shipToZip) {
        $this->shipToZip = $shipToZip;
    } 

    public function getProductId() {
        return $this->productId;
    }

    public function setProductId($productId) {
        $this->productId = $productId;
    }
    
    public function getProductNum() {
        return $this->productNum;
    }

    public function setProductNum($productNum) {
        $this->productNum = $productNum;
    }

    public function getSoID() {
        return $this->soID;
    }

    public function setSoID($soID) {
        $this->soID = $soID;
    }
    
    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function getTaxable() {
        return $this->taxable;
    }

    public function setTaxable($taxable) {
        $this->taxable = $taxable;
    }
    
    public function getQuantity() {
        return $this->quantity;
    }

    public function setQuantity($quantity) {
        $this->quantity = $quantity;
    }

    public function getProductPrice() {
        return $this->productPrice;
    }

    public function setProductPrice($productPrice) {
        $this->productPrice = $productPrice;
    }     

    public function getTotalPrice() {
        return $this->totalPrice;
    }

    public function setTotalPrice($totalPrice) {
        $this->totalPrice = $totalPrice;
    }
    
    public function getUOMCode() {
        return $this->UOMCode;
    }

    public function setUOMCode($UOMCode) {
        $this->UOMCode = $UOMCode;
    }

    public function getItemType() {
        return $this->itemType;
    }

    public function setItemType($itemType) {
        $this->itemType = $itemType;
    }
    
    public function getProductStatus() {
        return $this->productStatus;
    }

    public function setProductStatus($productStatus) {
        $this->productStatus = $productStatus;
    }

    public function getProductQuickBooksClassName() {
        return $this->productQuickBooksClassName;
    }

    public function setProductQuickBooksClassName($productQuickBooksClassName) {
        $this->productQuickBooksClassName = $productQuickBooksClassName;
    }
    
    public function getNewItemFlag() {
        return $this->newItemFlag;
    }

    public function setNewItemFlag($newItemFlag) {
        $this->newItemFlag = $newItemFlag;
    }

    public function getLineNumber() {
        return $this->lineNumber;
    }

    public function setLineNumber($lineNumber) {
        $this->lineNumber = $lineNumber;
    }
    
    public function getIssueFlag() {
        return $this->issueFlag;
    }

    public function setIssueFlag($issueFlag) {
        $this->issueFlag = $issueFlag;
    }

    public function getIgnoreItem() {
        return $this->ignoreItem;
    }

    public function setIgnoreItem($ignoreItem) {
        $this->ignoreItem = $ignoreItem;
    } 
    
    public function getKitItem() {
        return $this->kitItem;
    }

    public function setKitItem($kitItem) {
        $this->kitItem = $kitItem;
    }
    
    public function saveSO($xml){
        
        $this->createRequest($xml);
        $len = strlen($this->xmlRequest);
        $packed = pack("N", $len);

        // Send and get the response
        fwrite($this->id, $packed, 4);
        fwrite($this->id, $this->xmlRequest);
        $this->getResponse();

        // Set the result
        $this->setResult($this->parseXML($this->xmlResponse));
        return true;
    }
    
    public function addSoItem($SoNum,$sku,$Qty,$price){
        $xml = "<FbiXml>\n<Ticket>\n<Key>{$this->fbApi->key}</Key>\n</Ticket>\n<FbiMsgsRq>\n".
                "<AddSOItemRq>\n".
                        "<OrderNum>{$SoNum}</OrderNum>\n".
                        "<SalesOrderItem>\n".
                        "<Status>10</Status>\n".
                                "<ID>99999</ID>\n".
                        "<SOID>12399</SOID>\n".
			"<ProductNumber>{$sku}</ProductNumber>\n".
			"<Taxable>false</Taxable>\n".
			"<Quantity>{$Qty}</Quantity>\n".
			"<ProductPrice>{$price}</ProductPrice>\n".
			"<UOMCode>ea</UOMCode>\n".
			"<ItemType>10</ItemType>\n".
			"<QuickBooksClassName>None</QuickBooksClassName>\n".
			"<NewItemFlag>false</NewItemFlag>\n".
                        "</SalesOrderItem>\n".
                        "</AddSOItemRq >\n".
                        "</FbiMsgsRq>\n</FbiXml>\n";
        $this->createRequest($xml);
        $len = strlen($this->xmlRequest);
        $packed = pack("N", $len);
        print_r($this->xmlRequest);
        // Send and get the response
        fwrite($this->id, $packed, 4);
        fwrite($this->id, $this->xmlRequest);
        $this->getResponse();

        // Set the result
        $this->setResult($this->parseXML($this->xmlResponse));
        print_r($this->xmlResponse);
    }
    
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
		//$this->setStatus('ProductQueryRs');
                //$this->fbApi->closeConnection();
	}
    
    public function executeQuery($type, $queryString) {
		if( $type != "Name" || $type != "Query" ) {
			// TODO: Set error
			//throw new Exception('Wrong input!');
		}
		// Setup xml
		$xml = "<ExecuteQueryRq>\n<{$type}>{$queryString}</{$type}>\n</ExecuteQueryRq>\n";
		
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
		$this->setStatus('ExecuteQueryRs');
	}

        
        
        private function createRequest($xmlData) {
		$this->xmlRequest = $xmlData;
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
    
}


?>
