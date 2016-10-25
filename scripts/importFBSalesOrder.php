<?php

    require "classes/SolidCommerce/FBSalesOrder.php";
    require "classes/SolidCommerce/searchOrder.php";
    require_once "/classes/UPS/UPSForImport.php";
    
    define('SCSKU_INI', "classes/SolidCommerce/scSku_map.ini");
    define('KIT_INI', "classes/SolidCommerce/kit_map.ini");
    date_default_timezone_set('America/Chicago');
    $fbSalesOrder = new FBSalesOrder();
    $scOrderObj = new searchOrder();
    $scSku = parse_ini_file(SCSKU_INI,true);
    $kitSku = parse_ini_file(KIT_INI,true);
    $orderNumber = count($scOrderObj->saleID)."<br>";                                                                                                                                                       
    $rate = new UPS();
    $logName = "log".date("Ymd-His").".txt";
    $logDir = realpath(dirname(__FILE__))."\\"."log"."\\";
    $path = $logDir.$logName;


    
    file_put_contents($path, $orderNumber, FILE_APPEND);
    if($scOrderObj->saleID){
    foreach($scOrderObj->saleID as $key => $value){
    
        $totalPrice = 0;
        $totalPublic = 0;
    
            //Get order info from SC
            $scOrder = $scOrderObj->getSCOrder($value['saleID']);  

        echo(date("Y-m-d H:i:s"));
        echo"<br/>-----------------<br/>";
        print_r($scOrder);    
        
        file_put_contents($path, json_encode($scOrder), FILE_APPEND);
        file_put_contents($path, "<br/>-----------------<br/>".$value['saleID']."<br/>-----------------<br/>".$scOrder['StoreOrderID']."<br/>-----------------<br/>", FILE_APPEND);
        echo"<br/>-----------------<br/>";
        
        //Set order info
               
        $fbSalesOrder->setSoNumber('');
        $fbSalesOrder->setSalesMan('admin'); 
        
        
        if($scOrder['marketID'] == 1 )
            {
                $fbSalesOrder->setCustomerPO($scOrder['StoreOrderID']);
            }else if($scOrder['marketID'] == 3 ){
                $fbSalesOrder->setCustomerPO($scOrder['OrderPayments']['Payment']['TransactionID']);
            }else if($scOrder['marketID'] == 32 ){
                $fbSalesOrder->setCustomerPO($scOrder['StoreOrderID']);
            }else if($scOrder['marketID'] == 35 ){
                $fbSalesOrder->setCustomerPO($scOrder['StoreOrderID']);
            }else{
                echo "There are no such market places";
                file_put_contents($path, "There are no such market places", FILE_APPEND);
            }
        
        
        $fbSalesOrder->setStatus('10');
        $fbSalesOrder->setCarrier('UPS GROUND');
        $fbSalesOrder->setCreatedDate($scOrder['OrderDateTime']);
        $fbSalesOrder->setTaxRateName('Sales Tax');
        $fbSalesOrder->setShippingTerms('Prepaid &amp; Billed');
        $fbSalesOrder->setPaymentTerms('NONE');

        //Get Market Places
        if($scOrder['marketID'] == 1 )
            {
                $fbSalesOrder->setCustomerContact('MarketPlace - Amazon');  
                $fbSalesOrder->setCustomerName('MarketPlace - Amazon');
            }else if($scOrder['marketID'] == 3 ){
                $fbSalesOrder->setCustomerContact('MarketPlace - eBay'); 
                $fbSalesOrder->setCustomerName('MarketPlace - eBay');
            }else if($scOrder['marketID'] == 32 ){
                $fbSalesOrder->setCustomerContact('MarketPlace - Rakuten'); 
                $fbSalesOrder->setCustomerName('MarketPlace - Rakuten');
            }else if($scOrder['marketID'] == 35 ){
                $fbSalesOrder->setCustomerContact('MarketPlace - NEGG'); 
                $fbSalesOrder->setCustomerName('MarketPlace - NEGG');
            }else{
                echo "There are no such market places";
                file_put_contents($path, "There are no such market places", FILE_APPEND);
            }


        $fbSalesOrder->setFOB('Origin');
        $fbSalesOrder->setQuickBooksClassName(' ');
        //$fbSalesOrder->setLocationGroup($scOrder->scOrder['OrderItems']['OrderItem']['WarehouseName']);
        $fbSalesOrder->setLocationGroup('AYC');

        //Set beill to info
        $fbSalesOrder->setBillToName('MarketPlace DBA Madison and Park');
        $fbSalesOrder->setBillToAddressField("Customers' Direct Source"."\n"."www.shopsaloncity.com");
        $fbSalesOrder->setBillToCity('Garland');
        $fbSalesOrder->setBillToState('Texas');
        $fbSalesOrder->setBillToCountry('US');
        $fbSalesOrder->setBillToZip('75041');

        //Set ship to info
        $fbSalesOrder->setShipToName($scOrder['BuyerName']);
        $fbSalesOrder->setShipToAddressField($scOrder['BuyerName']."\n".$scOrder['ShipToStreet1'].", ".$scOrder['ShipToStreet2']."\n".$scOrder['Phone']);
        $fbSalesOrder->setShipToCity($scOrder['ShipToCity']);
        $fbSalesOrder->setShipToCountry($scOrder['ShipToCountry']);
        $fbSalesOrder->setShipToState($scOrder['ShipToState']);
        $fbSalesOrder->setShipToZip($scOrder['ShipToZip']);

        // Creating XML of the SalesOrder object
        $xml = new SimpleXMLElement('<FbiXml></FbiXml>');
        $Ticket = $xml->addChild('Ticket');
        $Key = $Ticket->addChild('Key', $fbSalesOrder->fbApi->key);
        $FbiMsgsRq = $xml->addChild('FbiMsgsRq');
        $SOSaveRq = $FbiMsgsRq->addChild('SOSaveRq');
        $SalesOrder = $SOSaveRq->addChild('SalesOrder');
        $SalesOrder->addChild('Number', $fbSalesOrder->getSoNumber());
        $SalesOrder->addChild('Salesman', $fbSalesOrder->getSalesMan());
        
        $SalesOrder->addChild('CustomerPO', $fbSalesOrder->getCustomerPO());
        $SalesOrder->addChild('Status', $fbSalesOrder->getStatus());
        $SalesOrder->addChild('Carrier', $fbSalesOrder->getCarrier());
        $SalesOrder->addChild('CreatedDate', $fbSalesOrder->getCreatedDate());
        $SalesOrder->addChild('TaxRateName', $fbSalesOrder->getTaxRateName());
        $SalesOrder->addChild('ShippingTerms', $fbSalesOrder->getShippingTerms());
        $SalesOrder->addChild('PaymentTerms', $fbSalesOrder->getPaymentTerms());
        $SalesOrder->addChild('CustomerContact', $fbSalesOrder->getCustomerContact());
        $SalesOrder->addChild('CustomerName', $fbSalesOrder->getCustomerName());
        $SalesOrder->addChild('FOB', $fbSalesOrder->getFOB());
        $SalesOrder->addChild('QuickBooksClassName', $fbSalesOrder->getQuickBooksClassName());
        $SalesOrder->addChild('LocationGroup', $fbSalesOrder->getLocationGroup());
        $BillTo = $SalesOrder->addChild('BillTo');
        $BillTo->addChild('Name', $fbSalesOrder->getBillToName());
        $BillTo->addChild('AddressField', $fbSalesOrder->getBillToAddressField());
        $BillTo->addChild('City', $fbSalesOrder->getBillToCity());
        $BillTo->addChild('Zip', $fbSalesOrder->getBillToZip());
        $BillTo->addChild('Country', $fbSalesOrder->getBillToCountry());
        $BillTo->addChild('State', $fbSalesOrder->getBillToState());
        $Ship = $SalesOrder->addChild('Ship');
        $Ship->addChild('Name', $fbSalesOrder->getShipToName());
        $Ship->addChild('AddressField', $fbSalesOrder->getShipToAddressField());
        $Ship->addChild('City', $fbSalesOrder->getShipToCity());
        $Ship->addChild('Zip', $fbSalesOrder->getShipToZip());
        $Ship->addChild('Country', $fbSalesOrder->getShipToCountry());
        $Ship->addChild('State', $fbSalesOrder->getShipToState());
        $Items = $SalesOrder->addChild('Items');

        $orderItems = $scOrder['OrderItems'];
        
        //Set items info
        
        //check if there are multiple items
        if(!ISSET($orderItems['OrderItem']['SKU'])){
            foreach($orderItems['OrderItem'] as $item_key => $item_value){
                print_r($item_value);
                echo"<br/>-----------------<br/>";
                $totalQty = 0;
                $totalQty += $item_value['Qty'];
                
                
        if(isset($scSku['DUO'][$item_value['SKU']])){
                
        //check if it is a kit product        
            if(isset($kitSku[$item_value['SKU']])){
                $fbSalesOrder->setProductNum(isset($scSku['SC-FB'][$item_value['SKU']])?$scSku['SC-FB'][$item_value['SKU']]:$item_value['SKU']);
                $fbSalesOrder->setNotes(isset($scSku['NOTES'][$item_value['SKU']])?$scSku['NOTES'][$item_value['SKU']]:'');
                $fbSalesOrder->setTaxable('false');
                $fbSalesOrder->setQuantity($item_value['Qty']*2);
                $fbSalesOrder->setProductPrice($item_value['SoldPrice']/2);
                $fbSalesOrder->setUOMCode('ea');
                $fbSalesOrder->setItemType('10');
                $fbSalesOrder->setProductQuickBooksClassName(' ');
                $fbSalesOrder->setNewItemFlag('false');
                $fbSalesOrder->setIssueFlag('false');
                $fbSalesOrder->setIgnoreItem('false');

                $SalesOrderItem = $Items->addChild('SalesOrderItem');
                $SalesOrderItem->addChild('ProductNumber', $fbSalesOrder->getProductNum());              
                $SalesOrderItem->addChild('Taxable', $fbSalesOrder->getTaxable());
                $SalesOrderItem->addChild('Quantity', $fbSalesOrder->getQuantity());
                $SalesOrderItem->addChild('ProductPrice', $fbSalesOrder->getProductPrice());
                $SalesOrderItem->addChild('UOMCode', $fbSalesOrder->getUOMCode());
                $SalesOrderItem->addChild('ItemType', $fbSalesOrder->getItemType());
                $SalesOrderItem->addChild('QuickBooksClassName', $fbSalesOrder->getProductQuickBooksClassName());
                $SalesOrderItem->addChild('NewItemFlag', $fbSalesOrder->getNewItemFlag());    
                $SalesOrderItem->addChild('Note', $fbSalesOrder->getNotes());
                
                foreach($kitSku[$item_value['SKU']] as $kit =>$kit_value){
                    $fbSalesOrder->setProductNum($kit_value);
                    $fbSalesOrder->setNotes(isset($scSku['NOTES'][$item_value['SKU']])?$scSku['NOTES'][$item_value['SKU']]:'');
                    $fbSalesOrder->setTaxable('false');
                    $fbSalesOrder->setQuantity($item_value['Qty']*2);
                    $fbSalesOrder->setProductPrice(0);
                    $fbSalesOrder->setUOMCode('ea');
                    $fbSalesOrder->setItemType('10');
                    $fbSalesOrder->setProductQuickBooksClassName(' ');
                    $fbSalesOrder->setNewItemFlag('false');
                    $fbSalesOrder->setIssueFlag('false');
                    $fbSalesOrder->setIgnoreItem('false');

                    $SalesOrderItem = $Items->addChild('SalesOrderItem');
                    $SalesOrderItem->addChild('ProductNumber', $fbSalesOrder->getProductNum());                    
                    $SalesOrderItem->addChild('Taxable', $fbSalesOrder->getTaxable());
                    $SalesOrderItem->addChild('Quantity', $fbSalesOrder->getQuantity());
                    $SalesOrderItem->addChild('ProductPrice', $fbSalesOrder->getProductPrice());
                    $SalesOrderItem->addChild('UOMCode', $fbSalesOrder->getUOMCode());
                    $SalesOrderItem->addChild('ItemType', $fbSalesOrder->getItemType());
                    $SalesOrderItem->addChild('QuickBooksClassName', $fbSalesOrder->getProductQuickBooksClassName());
                    $SalesOrderItem->addChild('NewItemFlag', $fbSalesOrder->getNewItemFlag());
                    $SalesOrderItem->addChild('Note', $fbSalesOrder->getNotes());
                }
            }else{
                
                
                $fbSalesOrder->fbApi->getProducts('Get', isset($scSku['SC-FB'][$item_value['SKU']])?$scSku['SC-FB'][$item_value['SKU']]:$item_value['SKU'], 0, null) ;

                if ($fbSalesOrder->fbApi->statusCode != 1000) {
                    // Display error messages if it's not blank
                    if (!empty($fbSalesOrder->fbApi->statusMsg)) {
                        echo $fbSalesOrder->fbApi->statusMsg;
                    }
                }
                //echo "<pre>";
                $productInfo = $fbSalesOrder->fbApi->result['FbiMsgsRs']['ProductGetRs']['Product'];
                $Weight = $productInfo['Weight'];
                $Width = $productInfo['Width'];
                $Height = $productInfo['Height'];
                $Len = $productInfo['Len'];
                
                $requestVar=array(
                    "context" =>"Rate",
                    "shipInfo"=>array(
                        "BuyerName"=>$scOrder['BuyerName'],
                        "ShipToStreet1"=>$scOrder['ShipToStreet1'],
                        "ShipToCity"=>$scOrder['ShipToCity'],
                        "ShipToState"=>$scOrder['ShipToState'],
                        "ShipToZip"=>$scOrder['ShipToZip']
                    ),
                    "productInfo" => array(
                        "weight"=>$Weight,
                        "len"=>$Len,
                        "width"=>$Width,
                        "height"=>$Height
                    )
                );

                $rate->setRateRequestForImport($requestVar);
                $result = $rate->invokeRateRequest();
                $duoPrice = $result->RateResponse->RatedShipment->NegotiatedRateCharges->TotalCharge->MonetaryValue;
                $totalPrice = $totalPrice + $duoPrice*$item_value['Qty']*2;
                $totalPublic = $totalPublic + $duoPrice*$item_value['Qty']*2;                
                
                print_r($item_value);
                $fbSalesOrder->setProductNum(isset($scSku['SC-FB'][$item_value['SKU']])?$scSku['SC-FB'][$item_value['SKU']]:$item_value['SKU']);
                $fbSalesOrder->setNotes(isset($scSku['NOTES'][$item_value['SKU']])?$scSku['NOTES'][$item_value['SKU']]:'');
                $fbSalesOrder->setTaxable('false');
                $fbSalesOrder->setQuantity($item_value['Qty']*2);
                $fbSalesOrder->setProductPrice($item_value['SoldPrice']/2);
                $fbSalesOrder->setUOMCode('ea');
                $fbSalesOrder->setItemType('10');
                $fbSalesOrder->setProductQuickBooksClassName(' ');
                $fbSalesOrder->setNewItemFlag('false');
                $fbSalesOrder->setIssueFlag('false');
                $fbSalesOrder->setIgnoreItem('false');

                $SalesOrderItem = $Items->addChild('SalesOrderItem');
                $SalesOrderItem->addChild('ProductNumber', $fbSalesOrder->getProductNum());
                $SalesOrderItem->addChild('Taxable', $fbSalesOrder->getTaxable());
                $SalesOrderItem->addChild('Quantity', $fbSalesOrder->getQuantity());
                $SalesOrderItem->addChild('ProductPrice', $fbSalesOrder->getProductPrice());
                $SalesOrderItem->addChild('UOMCode', $fbSalesOrder->getUOMCode());
                $SalesOrderItem->addChild('ItemType', $fbSalesOrder->getItemType());
                $SalesOrderItem->addChild('QuickBooksClassName', $fbSalesOrder->getProductQuickBooksClassName());
                $SalesOrderItem->addChild('NewItemFlag', $fbSalesOrder->getNewItemFlag());
                $SalesOrderItem->addChild('Note', $fbSalesOrder->getNotes());
                }                
            }else{
                if(isset($kitSku[$item_value['SKU']])){
                $fbSalesOrder->setProductNum(isset($scSku['SC-FB'][$item_value['SKU']])?$scSku['SC-FB'][$item_value['SKU']]:$item_value['SKU']);
                $fbSalesOrder->setNotes(isset($scSku['NOTES'][$item_value['SKU']])?$scSku['NOTES'][$item_value['SKU']]:'');
                $fbSalesOrder->setTaxable('false');
                $fbSalesOrder->setQuantity($item_value['Qty']);
                $fbSalesOrder->setProductPrice($item_value['SoldPrice']);
                $fbSalesOrder->setUOMCode('ea');
                $fbSalesOrder->setItemType('10');
                $fbSalesOrder->setProductQuickBooksClassName(' ');
                $fbSalesOrder->setNewItemFlag('false');
                $fbSalesOrder->setIssueFlag('false');
                $fbSalesOrder->setIgnoreItem('false');

                $SalesOrderItem = $Items->addChild('SalesOrderItem');
                $SalesOrderItem->addChild('ProductNumber', $fbSalesOrder->getProductNum());              
                $SalesOrderItem->addChild('Taxable', $fbSalesOrder->getTaxable());
                $SalesOrderItem->addChild('Quantity', $fbSalesOrder->getQuantity());
                $SalesOrderItem->addChild('ProductPrice', $fbSalesOrder->getProductPrice());
                $SalesOrderItem->addChild('UOMCode', $fbSalesOrder->getUOMCode());
                $SalesOrderItem->addChild('ItemType', $fbSalesOrder->getItemType());
                $SalesOrderItem->addChild('QuickBooksClassName', $fbSalesOrder->getProductQuickBooksClassName());
                $SalesOrderItem->addChild('NewItemFlag', $fbSalesOrder->getNewItemFlag());    
                $SalesOrderItem->addChild('Note', $fbSalesOrder->getNotes());
                
                foreach($kitSku[$item_value['SKU']] as $kit =>$kit_value){
                    $fbSalesOrder->setProductNum($kit_value);
                    $fbSalesOrder->setNotes(isset($scSku['NOTES'][$item_value['SKU']])?$scSku['NOTES'][$item_value['SKU']]:'');
                    $fbSalesOrder->setTaxable('false');
                    $fbSalesOrder->setQuantity($item_value['Qty']);
                    $fbSalesOrder->setProductPrice(0);
                    $fbSalesOrder->setUOMCode('ea');
                    $fbSalesOrder->setItemType('10');
                    $fbSalesOrder->setProductQuickBooksClassName(' ');
                    $fbSalesOrder->setNewItemFlag('false');
                    $fbSalesOrder->setIssueFlag('false');
                    $fbSalesOrder->setIgnoreItem('false');

                    $SalesOrderItem = $Items->addChild('SalesOrderItem');
                    $SalesOrderItem->addChild('ProductNumber', $fbSalesOrder->getProductNum());                    
                    $SalesOrderItem->addChild('Taxable', $fbSalesOrder->getTaxable());
                    $SalesOrderItem->addChild('Quantity', $fbSalesOrder->getQuantity());
                    $SalesOrderItem->addChild('ProductPrice', $fbSalesOrder->getProductPrice());
                    $SalesOrderItem->addChild('UOMCode', $fbSalesOrder->getUOMCode());
                    $SalesOrderItem->addChild('ItemType', $fbSalesOrder->getItemType());
                    $SalesOrderItem->addChild('QuickBooksClassName', $fbSalesOrder->getProductQuickBooksClassName());
                    $SalesOrderItem->addChild('NewItemFlag', $fbSalesOrder->getNewItemFlag());
                    $SalesOrderItem->addChild('Note', $fbSalesOrder->getNotes());
                }
            }else{
                
                $fbSalesOrder->fbApi->getProducts('Get', isset($scSku['SC-FB'][$item_value['SKU']])?$scSku['SC-FB'][$item_value['SKU']]:$item_value['SKU'], 0, null) ;

                if ($fbSalesOrder->fbApi->statusCode != 1000) {
                    // Display error messages if it's not blank
                    if (!empty($fbSalesOrder->fbApi->statusMsg)) {
                        echo $fbSalesOrder->fbApi->statusMsg;
                    }
                }
                //echo "<pre>";
                $productInfo = $fbSalesOrder->fbApi->result['FbiMsgsRs']['ProductGetRs']['Product'];
                $Weight = $productInfo['Weight'];
                $Width = $productInfo['Width'];
                $Height = $productInfo['Height'];
                $Len = $productInfo['Len'];
                //echo "</pre>";
                
                $requestVar=array(
                    "context" =>"Rate",
                    "shipInfo"=>array(
                        "BuyerName"=>$scOrder['BuyerName'],
                        "ShipToStreet1"=>$scOrder['ShipToStreet1'],
                        "ShipToCity"=>$scOrder['ShipToCity'],
                        "ShipToState"=>$scOrder['ShipToState'],
                        "ShipToZip"=>$scOrder['ShipToZip']
                    ),
                    "productInfo" => array(
                        "weight"=>$Weight,
                        "len"=>$Len,
                        "width"=>$Width,
                        "height"=>$Height
                    )
                );

                $rate->setRateRequestForImport($requestVar);
                $result = $rate->invokeRateRequest();
                $mulPrice = $result->RateResponse->RatedShipment->NegotiatedRateCharges->TotalCharge->MonetaryValue;
                $totalPrice = $totalPrice + $mulPrice*$item_value['Qty'];
                $totalPublic = $totalPublic + $mulPrice*$item_value['Qty'];
                
                print_r($item_value);
                $fbSalesOrder->setProductNum(isset($scSku['SC-FB'][$item_value['SKU']])?$scSku['SC-FB'][$item_value['SKU']]:$item_value['SKU']);
                $fbSalesOrder->setNotes(isset($scSku['NOTES'][$item_value['SKU']])?$scSku['NOTES'][$item_value['SKU']]:'');
                $fbSalesOrder->setTaxable('false');
                $fbSalesOrder->setQuantity($item_value['Qty']);
                $fbSalesOrder->setProductPrice($item_value['SoldPrice']);
                $fbSalesOrder->setUOMCode('ea');
                $fbSalesOrder->setItemType('10');
                $fbSalesOrder->setProductQuickBooksClassName(' ');
                $fbSalesOrder->setNewItemFlag('false');
                $fbSalesOrder->setIssueFlag('false');
                $fbSalesOrder->setIgnoreItem('false');

                $SalesOrderItem = $Items->addChild('SalesOrderItem');
                $SalesOrderItem->addChild('ProductNumber', $fbSalesOrder->getProductNum());
                $SalesOrderItem->addChild('Taxable', $fbSalesOrder->getTaxable());
                $SalesOrderItem->addChild('Quantity', $fbSalesOrder->getQuantity());
                $SalesOrderItem->addChild('ProductPrice', $fbSalesOrder->getProductPrice());
                $SalesOrderItem->addChild('UOMCode', $fbSalesOrder->getUOMCode());
                $SalesOrderItem->addChild('ItemType', $fbSalesOrder->getItemType());
                $SalesOrderItem->addChild('QuickBooksClassName', $fbSalesOrder->getProductQuickBooksClassName());
                $SalesOrderItem->addChild('NewItemFlag', $fbSalesOrder->getNewItemFlag());
                $SalesOrderItem->addChild('Note', $fbSalesOrder->getNotes());
                }               
            }
        }
        
        }else{
            $totalQty = $orderItems['OrderItem']['Qty'];
            //$fbSalesOrder->setProductNum(isset($scSku[$orderItems['OrderItem']['SKU']])?$scSku[$orderItems['OrderItem']['SKU']]:$orderItems['OrderItem']['SKU']);
            
            if(isset($scSku['DUO'][$orderItems['OrderItem']['SKU']])){
            //check if it is a kit product   
            if(isset($kitSku[$orderItems['OrderItem']['SKU']])){
                $fbSalesOrder->setProductNum(isset($scSku['SC-FB'][$orderItems['OrderItem']['SKU']])?$scSku['SC-FB'][$orderItems['OrderItem']['SKU']]:$orderItems['OrderItem']['SKU']);
                $fbSalesOrder->setNotes(isset($scSku['NOTES'][$orderItems['OrderItem']['SKU']])?$scSku['NOTES'][$orderItems['OrderItem']['SKU']]:'');
                $fbSalesOrder->setTaxable('false');
                $fbSalesOrder->setQuantity($orderItems['OrderItem']['Qty']*2);
                $fbSalesOrder->setProductPrice($orderItems['OrderItem']['SoldPrice']/2);
                $fbSalesOrder->setUOMCode('ea');
                $fbSalesOrder->setItemType('10');
                $fbSalesOrder->setProductQuickBooksClassName(' ');
                $fbSalesOrder->setNewItemFlag('false');
                $fbSalesOrder->setIssueFlag('false');
                $fbSalesOrder->setIgnoreItem('false');

                $SalesOrderItem = $Items->addChild('SalesOrderItem');
                $SalesOrderItem->addChild('ProductNumber', $fbSalesOrder->getProductNum());
                $SalesOrderItem->addChild('Taxable', $fbSalesOrder->getTaxable());
                $SalesOrderItem->addChild('Quantity', $fbSalesOrder->getQuantity());
                $SalesOrderItem->addChild('ProductPrice', $fbSalesOrder->getProductPrice());
                $SalesOrderItem->addChild('UOMCode', $fbSalesOrder->getUOMCode());
                $SalesOrderItem->addChild('ItemType', $fbSalesOrder->getItemType());
                $SalesOrderItem->addChild('QuickBooksClassName', $fbSalesOrder->getProductQuickBooksClassName());
                $SalesOrderItem->addChild('NewItemFlag', $fbSalesOrder->getNewItemFlag());    
                $SalesOrderItem->addChild('Note', $fbSalesOrder->getNotes());
                foreach($kitSku[$orderItems['OrderItem']['SKU']] as $kit =>$kit_value){
                    $fbSalesOrder->setProductNum($kit_value);
                    $fbSalesOrder->setNotes(isset($scSku['NOTES'][$orderItems['OrderItem']['SKU']])?$scSku['NOTES'][$orderItems['OrderItem']['SKU']]:'');
                    $fbSalesOrder->setTaxable('false');
                    $fbSalesOrder->setQuantity($orderItems['OrderItem']['Qty']*2);
                    $fbSalesOrder->setProductPrice(0);
                    $fbSalesOrder->setUOMCode('ea');
                    $fbSalesOrder->setItemType('10');
                    $fbSalesOrder->setProductQuickBooksClassName(' ');
                    $fbSalesOrder->setNewItemFlag('false');
                    $fbSalesOrder->setIssueFlag('false');
                    $fbSalesOrder->setIgnoreItem('false');

                    $SalesOrderItem = $Items->addChild('SalesOrderItem');
                    $SalesOrderItem->addChild('ProductNumber', $fbSalesOrder->getProductNum());
                    $SalesOrderItem->addChild('Taxable', $fbSalesOrder->getTaxable());
                    $SalesOrderItem->addChild('Quantity', $fbSalesOrder->getQuantity());
                    $SalesOrderItem->addChild('ProductPrice', $fbSalesOrder->getProductPrice());
                    $SalesOrderItem->addChild('UOMCode', $fbSalesOrder->getUOMCode());
                    $SalesOrderItem->addChild('ItemType', $fbSalesOrder->getItemType());
                    $SalesOrderItem->addChild('QuickBooksClassName', $fbSalesOrder->getProductQuickBooksClassName());
                    $SalesOrderItem->addChild('NewItemFlag', $fbSalesOrder->getNewItemFlag());
                    $SalesOrderItem->addChild('Note', $fbSalesOrder->getNotes());
                }
            }else{
                
                $fbSalesOrder->fbApi->getProducts('Get', isset($scSku['SC-FB'][$orderItems['OrderItem']['SKU']])?$scSku['SC-FB'][$orderItems['OrderItem']['SKU']]:$orderItems['OrderItem']['SKU'], 0, null) ;

                if ($fbSalesOrder->fbApi->statusCode != 1000) {
                    // Display error messages if it's not blank
                    if (!empty($fbSalesOrder->fbApi->statusMsg)) {
                        echo $fbSalesOrder->fbApi->statusMsg;
                    }
                }
                //echo "<pre>";
                $productInfo = $fbSalesOrder->fbApi->result['FbiMsgsRs']['ProductGetRs']['Product'];
                $Weight = $productInfo['Weight'];
                $Width = $productInfo['Width'];
                $Height = $productInfo['Height'];
                $Len = $productInfo['Len'];
                //echo "</pre>";
                
                $requestVar=array(
                    "context" =>"Rate",
                    "shipInfo"=>array(
                        "BuyerName"=>$scOrder['BuyerName'],
                        "ShipToStreet1"=>$scOrder['ShipToStreet1'],
                        "ShipToCity"=>$scOrder['ShipToCity'],
                        "ShipToState"=>$scOrder['ShipToState'],
                        "ShipToZip"=>$scOrder['ShipToZip']
                    ),
                    "productInfo" => array(
                        "weight"=>$Weight,
                        "len"=>$Len,
                        "width"=>$Width,
                        "height"=>$Height
                    )
                );

                $rate->setRateRequestForImport($requestVar);
                $result = $rate->invokeRateRequest();
                $duoPrice = $result->RateResponse->RatedShipment->NegotiatedRateCharges->TotalCharge->MonetaryValue;
                $totalPrice = $totalPrice + $duoPrice*$orderItems['OrderItem']['Qty']*2;
                $totalPublic = $totalPublic + $duoPrice*$orderItems['OrderItem']['Qty']*2;
                
                $fbSalesOrder->setProductNum(isset($scSku['SC-FB'][$orderItems['OrderItem']['SKU']])?$scSku['SC-FB'][$orderItems['OrderItem']['SKU']]:$orderItems['OrderItem']['SKU']);
                $fbSalesOrder->setTaxable('false');
                $fbSalesOrder->setNotes(isset($scSku['NOTES'][$orderItems['OrderItem']['SKU']])?$scSku['NOTES'][$orderItems['OrderItem']['SKU']]:'');
                $fbSalesOrder->setQuantity($orderItems['OrderItem']['Qty']*2);
                $fbSalesOrder->setProductPrice($orderItems['OrderItem']['SoldPrice']/2);
                $fbSalesOrder->setUOMCode('ea');
                $fbSalesOrder->setItemType('10');
                $fbSalesOrder->setProductQuickBooksClassName(' ');
                $fbSalesOrder->setNewItemFlag('false');
                $fbSalesOrder->setIssueFlag('false');
                $fbSalesOrder->setIgnoreItem('false');

                $SalesOrderItem = $Items->addChild('SalesOrderItem');
                $SalesOrderItem->addChild('ProductNumber', $fbSalesOrder->getProductNum());
                $SalesOrderItem->addChild('Taxable', $fbSalesOrder->getTaxable());
                $SalesOrderItem->addChild('Quantity', $fbSalesOrder->getQuantity());
                $SalesOrderItem->addChild('ProductPrice', $fbSalesOrder->getProductPrice());
                $SalesOrderItem->addChild('UOMCode', $fbSalesOrder->getUOMCode());
                $SalesOrderItem->addChild('ItemType', $fbSalesOrder->getItemType());
                $SalesOrderItem->addChild('QuickBooksClassName', $fbSalesOrder->getProductQuickBooksClassName());
                $SalesOrderItem->addChild('NewItemFlag', $fbSalesOrder->getNewItemFlag());
                $SalesOrderItem->addChild('Note', $fbSalesOrder->getNotes());
                }
        }else{
            
            //check if it is a kit product   
            if(isset($kitSku[$orderItems['OrderItem']['SKU']])){
                $fbSalesOrder->setProductNum(isset($scSku['SC-FB'][$orderItems['OrderItem']['SKU']])?$scSku['SC-FB'][$orderItems['OrderItem']['SKU']]:$orderItems['OrderItem']['SKU']);
                $fbSalesOrder->setNotes(isset($scSku['NOTES'][$orderItems['OrderItem']['SKU']])?$scSku['NOTES'][$orderItems['OrderItem']['SKU']]:'');
                $fbSalesOrder->setTaxable('false');
                $fbSalesOrder->setQuantity($orderItems['OrderItem']['Qty']);
                $fbSalesOrder->setProductPrice($orderItems['OrderItem']['SoldPrice']);
                $fbSalesOrder->setUOMCode('ea');
                $fbSalesOrder->setItemType('10');
                $fbSalesOrder->setProductQuickBooksClassName(' ');
                $fbSalesOrder->setNewItemFlag('false');
                $fbSalesOrder->setIssueFlag('false');
                $fbSalesOrder->setIgnoreItem('false');

                $SalesOrderItem = $Items->addChild('SalesOrderItem');
                $SalesOrderItem->addChild('ProductNumber', $fbSalesOrder->getProductNum());
                $SalesOrderItem->addChild('Taxable', $fbSalesOrder->getTaxable());
                $SalesOrderItem->addChild('Quantity', $fbSalesOrder->getQuantity());
                $SalesOrderItem->addChild('ProductPrice', $fbSalesOrder->getProductPrice());
                $SalesOrderItem->addChild('UOMCode', $fbSalesOrder->getUOMCode());
                $SalesOrderItem->addChild('ItemType', $fbSalesOrder->getItemType());
                $SalesOrderItem->addChild('QuickBooksClassName', $fbSalesOrder->getProductQuickBooksClassName());
                $SalesOrderItem->addChild('NewItemFlag', $fbSalesOrder->getNewItemFlag());    
                $SalesOrderItem->addChild('Note', $fbSalesOrder->getNotes());
                foreach($kitSku[$orderItems['OrderItem']['SKU']] as $kit =>$kit_value){
                    $fbSalesOrder->setProductNum($kit_value);
                    $fbSalesOrder->setNotes(isset($scSku['NOTES'][$orderItems['OrderItem']['SKU']])?$scSku['NOTES'][$orderItems['OrderItem']['SKU']]:'');
                    $fbSalesOrder->setTaxable('false');
                    $fbSalesOrder->setQuantity($orderItems['OrderItem']['Qty']);
                    $fbSalesOrder->setProductPrice(0);
                    $fbSalesOrder->setUOMCode('ea');
                    $fbSalesOrder->setItemType('10');
                    $fbSalesOrder->setProductQuickBooksClassName(' ');
                    $fbSalesOrder->setNewItemFlag('false');
                    $fbSalesOrder->setIssueFlag('false');
                    $fbSalesOrder->setIgnoreItem('false');

                    $SalesOrderItem = $Items->addChild('SalesOrderItem');
                    $SalesOrderItem->addChild('ProductNumber', $fbSalesOrder->getProductNum());
                    $SalesOrderItem->addChild('Taxable', $fbSalesOrder->getTaxable());
                    $SalesOrderItem->addChild('Quantity', $fbSalesOrder->getQuantity());
                    $SalesOrderItem->addChild('ProductPrice', $fbSalesOrder->getProductPrice());
                    $SalesOrderItem->addChild('UOMCode', $fbSalesOrder->getUOMCode());
                    $SalesOrderItem->addChild('ItemType', $fbSalesOrder->getItemType());
                    $SalesOrderItem->addChild('QuickBooksClassName', $fbSalesOrder->getProductQuickBooksClassName());
                    $SalesOrderItem->addChild('NewItemFlag', $fbSalesOrder->getNewItemFlag());
                    $SalesOrderItem->addChild('Note', $fbSalesOrder->getNotes());
                }
            }else{
                
                
                $fbSalesOrder->fbApi->getProducts('Get', isset($scSku['SC-FB'][$orderItems['OrderItem']['SKU']])?$scSku['SC-FB'][$orderItems['OrderItem']['SKU']]:$orderItems['OrderItem']['SKU'], 0, null) ;

                if ($fbSalesOrder->fbApi->statusCode != 1000) {
                    // Display error messages if it's not blank
                    if (!empty($fbSalesOrder->fbApi->statusMsg)) {
                        echo $fbSalesOrder->fbApi->statusMsg;
                    }
                }
                //echo "<pre>";
                $productInfo = $fbSalesOrder->fbApi->result['FbiMsgsRs']['ProductGetRs']['Product'];
                $Weight = $productInfo['Weight'];
                $Width = $productInfo['Width'];
                $Height = $productInfo['Height'];
                $Len = $productInfo['Len'];
                //echo "</pre>";
                
                $requestVar=array(
                    "context" =>"Rate",
                    "shipInfo"=>array(
                        "BuyerName"=>$scOrder['BuyerName'],
                        "ShipToStreet1"=>$scOrder['ShipToStreet1'],
                        "ShipToCity"=>$scOrder['ShipToCity'],
                        //"ShipToState"=>$scOrder['ShipToState'],
                        "ShipToZip"=>$scOrder['ShipToZip']
                    ),
                    "productInfo" => array(
                        "weight"=>$Weight,
                        "len"=>$Len,
                        "width"=>$Width,
                        "height"=>$Height
                    )
                );

                $rate->setRateRequestForImport($requestVar);
                $result = $rate->invokeRateRequest();
                $sinPrice = $result->RateResponse->RatedShipment->NegotiatedRateCharges->TotalCharge->MonetaryValue;
                $totalPrice = $totalPrice + $sinPrice*$orderItems['OrderItem']['Qty'];
                $totalPublic = $totalPublic + $sinPrice*$orderItems['OrderItem']['Qty'];
                
                $fbSalesOrder->setProductNum(isset($scSku['SC-FB'][$orderItems['OrderItem']['SKU']])?$scSku['SC-FB'][$orderItems['OrderItem']['SKU']]:$orderItems['OrderItem']['SKU']);
                $fbSalesOrder->setTaxable('false');
                $fbSalesOrder->setNotes(isset($scSku['NOTES'][$orderItems['OrderItem']['SKU']])?$scSku['NOTES'][$orderItems['OrderItem']['SKU']]:'');
                $fbSalesOrder->setQuantity($orderItems['OrderItem']['Qty']);
                $fbSalesOrder->setProductPrice($orderItems['OrderItem']['SoldPrice']);
                $fbSalesOrder->setUOMCode('ea');
                $fbSalesOrder->setItemType('10');
                $fbSalesOrder->setProductQuickBooksClassName(' ');
                $fbSalesOrder->setNewItemFlag('false');
                $fbSalesOrder->setIssueFlag('false');
                $fbSalesOrder->setIgnoreItem('false');

                $SalesOrderItem = $Items->addChild('SalesOrderItem');
                $SalesOrderItem->addChild('ProductNumber', $fbSalesOrder->getProductNum());
                $SalesOrderItem->addChild('Taxable', $fbSalesOrder->getTaxable());
                $SalesOrderItem->addChild('Quantity', $fbSalesOrder->getQuantity());
                $SalesOrderItem->addChild('ProductPrice', $fbSalesOrder->getProductPrice());
                $SalesOrderItem->addChild('UOMCode', $fbSalesOrder->getUOMCode());
                $SalesOrderItem->addChild('ItemType', $fbSalesOrder->getItemType());
                $SalesOrderItem->addChild('QuickBooksClassName', $fbSalesOrder->getProductQuickBooksClassName());
                $SalesOrderItem->addChild('NewItemFlag', $fbSalesOrder->getNewItemFlag());
                $SalesOrderItem->addChild('Note', $fbSalesOrder->getNotes());
                }
            
        }
        }
        
        //Set market places service fee
        if($scOrder['ShipFee']==0){
            $fbSalesOrder->setProductNum('MarketPlace P&amp;H Fees');
            $fbSalesOrder->setTaxable('false');
            $fbSalesOrder->setQuantity(1);
            
                        
            if($scOrder['marketID'] == 1 )
            {
                $fbSalesOrder->setProductPrice(round(-$scOrder['TotalSale'] * $scOrderObj->ini_array['AMAZON_SERVICE_FEE'],2));
                $fbSalesOrder->setNotes('Amazon');
            }else if($scOrder['marketID'] == 3 ){
                $fbSalesOrder->setProductPrice(round(-$scOrder['TotalSale'] * $scOrderObj->ini_array['PAYPAL_FEE'] - $scOrderObj->ini_array['PAYPAL_FEE_PLUS'],2));
                $fbSalesOrder->setNotes('Paypal');
            }else if($scOrder['marketID'] == 32 ){
                $fbSalesOrder->setProductPrice(round(-$scOrder['TotalSale'] * $scOrderObj->ini_array['RAKUTEN_FEE'] - $totalQty * $scOrderObj->ini_array['RAKUTEN_PLUS_PER_ITEM'],2));
                $fbSalesOrder->setNotes('Rakuten.com');
            }else if($scOrder['marketID'] == 35 ){
                $fbSalesOrder->setProductPrice(round(-$scOrder['TotalSale'] * $scOrderObj->ini_array['NEWEGG_FEE'],2));
                $fbSalesOrder->setNotes('Newegg');
            }else{
                echo "There are no such market places";
            }
            $fbSalesOrder->setUOMCode('ea');
            $fbSalesOrder->setItemType('10');
            $fbSalesOrder->setProductQuickBooksClassName(' ');
            $fbSalesOrder->setNewItemFlag('false');
            $fbSalesOrder->setIssueFlag('false');
            $fbSalesOrder->setIgnoreItem('false');

            $SalesOrderItem = $Items->addChild('SalesOrderItem');
            $SalesOrderItem->addChild('ProductNumber', $fbSalesOrder->getProductNum());
            $SalesOrderItem->addChild('Taxable', $fbSalesOrder->getTaxable());
            $SalesOrderItem->addChild('Quantity', $fbSalesOrder->getQuantity());
            $SalesOrderItem->addChild('ProductPrice', $fbSalesOrder->getProductPrice());
            $SalesOrderItem->addChild('UOMCode', $fbSalesOrder->getUOMCode());
            $SalesOrderItem->addChild('ItemType', $fbSalesOrder->getItemType());
            $SalesOrderItem->addChild('QuickBooksClassName', $fbSalesOrder->getProductQuickBooksClassName());
            $SalesOrderItem->addChild('NewItemFlag', $fbSalesOrder->getNewItemFlag());
            $SalesOrderItem->addChild('Note', $fbSalesOrder->getNotes());
        }else{
            $fbSalesOrder->setProductNum('AYC-SHIPPING');
            $fbSalesOrder->setTaxable('false');
            $fbSalesOrder->setQuantity(1);
            $fbSalesOrder->setProductPrice($scOrder['ShipFee']);
            $fbSalesOrder->setUOMCode('ea');
            $fbSalesOrder->setItemType('60');
            $fbSalesOrder->setProductQuickBooksClassName(' ');
            $fbSalesOrder->setNewItemFlag('false');
            $fbSalesOrder->setIssueFlag('false');
            $fbSalesOrder->setIgnoreItem('false');

            $SalesOrderItem = $Items->addChild('SalesOrderItem');
            $SalesOrderItem->addChild('ProductNumber', $fbSalesOrder->getProductNum());
            $SalesOrderItem->addChild('Taxable', $fbSalesOrder->getTaxable());
            $SalesOrderItem->addChild('Quantity', $fbSalesOrder->getQuantity());
            $SalesOrderItem->addChild('ProductPrice', $fbSalesOrder->getProductPrice());
            $SalesOrderItem->addChild('UOMCode', $fbSalesOrder->getUOMCode());
            $SalesOrderItem->addChild('ItemType', $fbSalesOrder->getItemType());
            $SalesOrderItem->addChild('QuickBooksClassName', $fbSalesOrder->getProductQuickBooksClassName());
            $SalesOrderItem->addChild('NewItemFlag', $fbSalesOrder->getNewItemFlag());
            
            $fbSalesOrder->setProductNum('MarketPlace P&amp;H Fees');
            $fbSalesOrder->setTaxable('false');
            $fbSalesOrder->setQuantity(1);
                                    
            if($scOrder['marketID'] == 1 )
            {
                $fbSalesOrder->setProductPrice(round(-($scOrder['TotalSale'] + $scOrder['ShipFee'])* $scOrderObj->ini_array['AMAZON_SERVICE_FEE'],2));
                $fbSalesOrder->setNotes('Amazon');
            }else if($scOrder['marketID'] == 3 ){
                $fbSalesOrder->setProductPrice(round(-($scOrder['TotalSale'] + $scOrder['ShipFee']) * $scOrderObj->ini_array['PAYPAL_FEE'] - $scOrderObj->ini_array['PAYPAL_FEE_PLUS'],2));
                $fbSalesOrder->setNotes('Paypal');
            }else if($scOrder['marketID'] == 32 ){
                $fbSalesOrder->setProductPrice(round(-$scOrder['TotalSale'] * $scOrderObj->ini_array['RAKUTEN_FEE'] - $totalQty * $scOrderObj->ini_array['RAKUTEN_PLUS_PER_ITEM'],2));
                $fbSalesOrder->setNotes('Rakuten.com');
            }else if($scOrder['marketID'] == 35 ){
                $fbSalesOrder->setProductPrice(round(-$scOrder['TotalSale'] * $scOrderObj->ini_array['NEWEGG_FEE'],2));
                $fbSalesOrder->setNotes('Newegg');
            }else{
                echo "There are no such market places";
            }
            $fbSalesOrder->setUOMCode('ea');
            $fbSalesOrder->setItemType('10');
            $fbSalesOrder->setProductQuickBooksClassName(' ');
            $fbSalesOrder->setNewItemFlag('false');
            $fbSalesOrder->setIssueFlag('false');
            $fbSalesOrder->setIgnoreItem('false');

            $SalesOrderItem = $Items->addChild('SalesOrderItem');
            $SalesOrderItem->addChild('ProductNumber', $fbSalesOrder->getProductNum());
            $SalesOrderItem->addChild('Taxable', $fbSalesOrder->getTaxable());
            $SalesOrderItem->addChild('Quantity', $fbSalesOrder->getQuantity());
            $SalesOrderItem->addChild('ProductPrice', $fbSalesOrder->getProductPrice());
            $SalesOrderItem->addChild('UOMCode', $fbSalesOrder->getUOMCode());
            $SalesOrderItem->addChild('ItemType', $fbSalesOrder->getItemType());
            $SalesOrderItem->addChild('QuickBooksClassName', $fbSalesOrder->getProductQuickBooksClassName());
            $SalesOrderItem->addChild('NewItemFlag', $fbSalesOrder->getNewItemFlag());
            $SalesOrderItem->addChild('Note', $fbSalesOrder->getNotes());
        } 
        //Set the shipping
        $fbSalesOrder->setProductNum('AYC-SHIPPING');
        $fbSalesOrder->setTaxable('false');
        $fbSalesOrder->setQuantity(1);
        $fbSalesOrder->setProductPrice($totalPrice);
        $fbSalesOrder->setUOMCode('ea');
        $fbSalesOrder->setItemType('60');
        $fbSalesOrder->setProductQuickBooksClassName(' ');
        $fbSalesOrder->setNewItemFlag('false');
        $fbSalesOrder->setIssueFlag('false');
        $fbSalesOrder->setIgnoreItem('false');

        $SalesOrderItem = $Items->addChild('SalesOrderItem');
        $SalesOrderItem->addChild('ProductNumber', $fbSalesOrder->getProductNum());
        $SalesOrderItem->addChild('Taxable', $fbSalesOrder->getTaxable());
        $SalesOrderItem->addChild('Quantity', $fbSalesOrder->getQuantity());
        $SalesOrderItem->addChild('ProductPrice', $fbSalesOrder->getProductPrice());
        $SalesOrderItem->addChild('UOMCode', $fbSalesOrder->getUOMCode());
        $SalesOrderItem->addChild('ItemType', $fbSalesOrder->getItemType());
        $SalesOrderItem->addChild('QuickBooksClassName', $fbSalesOrder->getProductQuickBooksClassName());
        $SalesOrderItem->addChild('NewItemFlag', $fbSalesOrder->getNewItemFlag());
        //Set the free shipping
        $fbSalesOrder->setProductNum('Free Shipping');
        $fbSalesOrder->setTaxable('false');
        $fbSalesOrder->setQuantity('1');
        $fbSalesOrder->setProductPrice('');
        $fbSalesOrder->setUOMCode('ea');
        $fbSalesOrder->setItemType('30');
        //$fbSalesOrder->setProductQuickBooksClassName(' ');
        $fbSalesOrder->setNewItemFlag('false');
        $fbSalesOrder->setIssueFlag('false');
        $fbSalesOrder->setIgnoreItem('false');

        $SalesOrderItem = $Items->addChild('SalesOrderItem');
        $SalesOrderItem->addChild('ProductNumber', $fbSalesOrder->getProductNum());
        $SalesOrderItem->addChild('Taxable', $fbSalesOrder->getTaxable());
        $SalesOrderItem->addChild('Quantity', $fbSalesOrder->getQuantity());
        $SalesOrderItem->addChild('ProductPrice', $fbSalesOrder->getProductPrice());
        $SalesOrderItem->addChild('UOMCode', $fbSalesOrder->getUOMCode());
        $SalesOrderItem->addChild('ItemType', $fbSalesOrder->getItemType());
        $SalesOrderItem->addChild('QuickBooksClassName', $fbSalesOrder->getProductQuickBooksClassName());
        $SalesOrderItem->addChild('NewItemFlag', $fbSalesOrder->getNewItemFlag());
        
        $SOSaveRq->addChild('IssueFlag', $fbSalesOrder->getIssueFlag());
        $SOSaveRq->addChild('IgnoreItems', $fbSalesOrder->getIgnoreItem());

        $salesOrderXml = $xml->asXML();

        $fbSalesOrder->saveSO($salesOrderXml);

        if ($fbSalesOrder->fbApi->statusCode != 1000) {
                    // Display error messages if it's not blank
                    if (!empty($fbSalesOrder->fbApi->statusMsg)) {
                            echo $fbSalesOrder->fbApi->statusMsg;
                    }
            }else{
                $scOrderObj->updateSCOder($value['saleID']);
            }

            print_r($fbSalesOrder->fbApi->statusMsg);
            file_put_contents($path, $fbSalesOrder->fbApi->statusMsg."<br/>-----------------<br/>".$salesOrderXml, FILE_APPEND);

        echo"<br/>-----------------<br/>";
        echo "success!";
        echo ($salesOrderXml);
    }
    }else{
        echo "No orders imported.";
        file_put_contents($path, "No orders imported.", FILE_APPEND);
    }
