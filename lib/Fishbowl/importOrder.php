<?php
    require_once("fbErrorCodes.class.php");
    require_once("fishbowlAPI.class.php");
    define('FISHBOWL_INI', "fishbowl.ini");

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
				"						<Carrier>UPS Ground</Carrier>\n".
				"						<FirstShipDate>2016-08-29T00:00:00</FirstShipDate>\n".
				"						<CreatedDate>2016-08-29T00:00:00</CreatedDate>\n".
				"						<IssuedDate>2016-08-29T16:48:56</IssuedDate>\n".
				"						<TaxRatePercentage>0.0625</TaxRatePercentage>\n".
				"						<TaxRateName>Sales Tax</TaxRateName>\n".
				"						<ShippingTerms>Prepaid</ShippingTerms>\n".
				"						<PaymentTerms>COD</PaymentTerms>\n".
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
				"                                           </BillTo>\n".
				"                                           <Ship>\n".
				"						  <Name>test</Name>\n".
                                "                                                 <AddressField>test address</AddressField>".
				"                                          	  <Zip>93101</Zip>".
				"                                                 <Country>US</Country>".      
				"                                                 <State>California</State>".                          
				"                                           </Ship>\n"	;	
                         $xml .="                                           <Items>\n
                                                                                <SalesOrderItem>\n
										  <ID>10765</ID>\n
										  <ProductNumber>00-KIN-APPHDR-057</ProductNumber>\n
                                                                                  <SOID>10765</SOID>\n
                                                                                  <Description>Battery Pack</Description>
                                                                                  <Taxable>false</Taxable>\n 
										  <Quantity>1</Quantity>\n
                                                                                  <ProductPrice>0.95</ProductPrice>
                                                                                  <TotalPrice>0.95</TotalPrice>
                                                                                  <UOMCode>ea</UOMCode>
										  <ItemType>10</ItemType>\n
										  <QuickBooksClassName> </QuickBooksClassName>\n
										  <NewItemFlag>false</NewItemFlag>\n
                                                                                  <LineNumber>2</LineNumber>
                                                                                </SalesOrderItem>\n
                                                                            </Items>\n";
		$xml .="                                            </SalesOrder>\n".
                                "                                       <IssueFlag>false</IssueFlag>".
                                "                                       <IgnoreItems>false</IgnoreItems>".
                                "           </SOSaveRq>\n";
                        
		// Create request and pack
		$this->fbApi->createRequest($xml);
        $len = strlen($this->fbApi->xmlRequest);
        $packed = pack("N", $len);

        // Send and get the response
        fwrite($this->fbApi->id, $packed, 4);
        fwrite($this->fbApi->id, $this->fbApi->xmlRequest);
        $this->fbApi->getResponse();

        // Set the result
        $this->fbApi->setResult($this->fbApi->parseXML($this->fbApi->xmlResponse));
    }
        
    }