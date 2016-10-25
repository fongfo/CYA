<?php

    require "models/FBSalesOrder.php";
    require "models/searchOrder.php";
    define('SCSKU_INI', "models/scSku_map.ini");
    define('KIT_INI', "models/kit_map.ini");
    // Not needed anymore
    //include "XMLSerializer.php";
    
    $fbSalesOrder = new FBSalesOrder();
    $scOrderObj = new searchOrder();
    //$test = array(     470419702    );
    $scSku = parse_ini_file(SCSKU_INI,true);
    $kitSku = parse_ini_file(KIT_INI,true);
    foreach($scOrderObj->saleID as $key => $value){
    //$scOrder = $scOrderObj->getSCOrder($scOrderObj->saleID);
    // Setting variables in SalesOrder object
        
        //print_r("SaleID = "."\n".$value['saleID']."\n") ;

            /*$order_param = array(
                                    'appKey' => $scOrderObj->ini_array['APPKEY'],
                                    'securityKey'=>$scOrderObj->ini_array['SECURITYKEY'],
                                    'xslUri'=>'',
                                    'saleID'=> $value,
                                    'isLoadPayments'=>'True',
                                    'isLoadWarehouseName'=>'True'
                                    ); 
            $SCOrder = $scOrderObj->OrderObjec->getOrder($order_param);
            //$res=$SCOrder->GetOrderResult;
            $re = $scOrderObj->OrderObjec->objToXml($SCOrder);
            $res=$scOrderObj->OrderObjec->xmlToArr($re,false);
            $scOrder=$res['GetOrder']['Order'];*/
        
            //Get order info from SC
            $scOrder = $scOrderObj->getSCOrder($value['saleID']);
            
        echo"<br/>-----------------<br/>";
        print_r($scOrder);    
        echo"<br/>-----------------<br/>";
        
        //Set order info
        $fbSalesOrder->setSalesMan('admin');        
        $fbSalesOrder->setSoNumber($scOrder['saleID']);
        
        
        if($scOrder['marketID'] == 1 )
            {
                $fbSalesOrder->setCustomerPO($scOrder['StoreOrderID'].$scOrder['OrderDateTime']);
            }else if($scOrder['marketID'] == 3 ){
                $fbSalesOrder->setCustomerPO($scOrder['OrderPayments']['Payment']['TransactionID'].$scOrder['OrderDateTime']);
            }else if($scOrder['marketID'] == 32 ){
                $fbSalesOrder->setCustomerPO($scOrder['StoreOrderID'].$scOrder['OrderDateTime']);
            }else if($scOrder['marketID'] == 35 ){
                $fbSalesOrder->setCustomerPO($scOrder['StoreOrderID'].$scOrder['OrderDateTime']);
            }else{
                echo "There are no such market places";
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
        $fbSalesOrder->setShipToAddressField($scOrder['BuyerName']."\n".$scOrder['Phone']."\n".$scOrder['BuyerEmail']."\n".$scOrder['ShipToStreet1']);
        $fbSalesOrder->setShipToCity($scOrder['ShipToCity']);
        $fbSalesOrder->setShipToCountry($scOrder['ShipToCountry']);
        $fbSalesOrder->setShipToState($scOrder['ShipToState']);
        $fbSalesOrder->setShipToZip($scOrder['ShipToZip']);




        // Creating XML of the SalesOrder object
        $xml = new SimpleXMLElement('<FbiXml></FbiXml>');
        $Ticket = $xml->addChild('Ticket');
        $Key = $Ticket->addChild('Key', $fbSalesOrder->fbApi->key);
        $FbiMsgsRq = $xml->addChild('FbiMsgsRq');
        $ImportRq = $FbiMsgsRq->addChild('ImportRq');
        $Type = $ImportRq->addChild('Type','ImportSO');
        $SalesOrder = $ImportRq->addChild('SalesOrder');
        $SalesOrder->addChild('Salesman', $fbSalesOrder->getSalesMan());
        $SalesOrder->addChild('Number', $fbSalesOrder->getSoNumber());
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
                $SalesOrderItem->addChild('Note', $fbSalesOrder->getNotes());
                $SalesOrderItem->addChild('Taxable', $fbSalesOrder->getTaxable());
                $SalesOrderItem->addChild('Quantity', $fbSalesOrder->getQuantity());
                $SalesOrderItem->addChild('ProductPrice', $fbSalesOrder->getProductPrice());
                $SalesOrderItem->addChild('UOMCode', $fbSalesOrder->getUOMCode());
                $SalesOrderItem->addChild('ItemType', $fbSalesOrder->getItemType());
                $SalesOrderItem->addChild('QuickBooksClassName', $fbSalesOrder->getProductQuickBooksClassName());
                $SalesOrderItem->addChild('NewItemFlag', $fbSalesOrder->getNewItemFlag());    
                
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
                    $SalesOrderItem->addChild('Note', $fbSalesOrder->getNotes());
                    $SalesOrderItem->addChild('Taxable', $fbSalesOrder->getTaxable());
                    $SalesOrderItem->addChild('Quantity', $fbSalesOrder->getQuantity());
                    $SalesOrderItem->addChild('ProductPrice', $fbSalesOrder->getProductPrice());
                    $SalesOrderItem->addChild('UOMCode', $fbSalesOrder->getUOMCode());
                    $SalesOrderItem->addChild('ItemType', $fbSalesOrder->getItemType());
                    $SalesOrderItem->addChild('QuickBooksClassName', $fbSalesOrder->getProductQuickBooksClassName());
                    $SalesOrderItem->addChild('NewItemFlag', $fbSalesOrder->getNewItemFlag());
                }
            }else{
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
                $SalesOrderItem->addChild('Note', $fbSalesOrder->getNotes());
                $SalesOrderItem->addChild('Taxable', $fbSalesOrder->getTaxable());
                $SalesOrderItem->addChild('Quantity', $fbSalesOrder->getQuantity());
                $SalesOrderItem->addChild('ProductPrice', $fbSalesOrder->getProductPrice());
                $SalesOrderItem->addChild('UOMCode', $fbSalesOrder->getUOMCode());
                $SalesOrderItem->addChild('ItemType', $fbSalesOrder->getItemType());
                $SalesOrderItem->addChild('QuickBooksClassName', $fbSalesOrder->getProductQuickBooksClassName());
                $SalesOrderItem->addChild('NewItemFlag', $fbSalesOrder->getNewItemFlag());
                }                
            }
        }else{
            $totalQty = 1;
            //$fbSalesOrder->setProductNum(isset($scSku[$orderItems['OrderItem']['SKU']])?$scSku[$orderItems['OrderItem']['SKU']]:$orderItems['OrderItem']['SKU']);
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
                $SalesOrderItem->addChild('Note', $fbSalesOrder->getNotes());
                $SalesOrderItem->addChild('Taxable', $fbSalesOrder->getTaxable());
                $SalesOrderItem->addChild('Quantity', $fbSalesOrder->getQuantity());
                $SalesOrderItem->addChild('ProductPrice', $fbSalesOrder->getProductPrice());
                $SalesOrderItem->addChild('UOMCode', $fbSalesOrder->getUOMCode());
                $SalesOrderItem->addChild('ItemType', $fbSalesOrder->getItemType());
                $SalesOrderItem->addChild('QuickBooksClassName', $fbSalesOrder->getProductQuickBooksClassName());
                $SalesOrderItem->addChild('NewItemFlag', $fbSalesOrder->getNewItemFlag());                
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
                    $SalesOrderItem->addChild('Note', $fbSalesOrder->getNotes());
                    $SalesOrderItem->addChild('Taxable', $fbSalesOrder->getTaxable());
                    $SalesOrderItem->addChild('Quantity', $fbSalesOrder->getQuantity());
                    $SalesOrderItem->addChild('ProductPrice', $fbSalesOrder->getProductPrice());
                    $SalesOrderItem->addChild('UOMCode', $fbSalesOrder->getUOMCode());
                    $SalesOrderItem->addChild('ItemType', $fbSalesOrder->getItemType());
                    $SalesOrderItem->addChild('QuickBooksClassName', $fbSalesOrder->getProductQuickBooksClassName());
                    $SalesOrderItem->addChild('NewItemFlag', $fbSalesOrder->getNewItemFlag());
                }
            }else{
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
                $SalesOrderItem->addChild('Note', $fbSalesOrder->getNotes());
                $SalesOrderItem->addChild('Quantity', $fbSalesOrder->getQuantity());
                $SalesOrderItem->addChild('ProductPrice', $fbSalesOrder->getProductPrice());
                $SalesOrderItem->addChild('UOMCode', $fbSalesOrder->getUOMCode());
                $SalesOrderItem->addChild('ItemType', $fbSalesOrder->getItemType());
                $SalesOrderItem->addChild('QuickBooksClassName', $fbSalesOrder->getProductQuickBooksClassName());
                $SalesOrderItem->addChild('NewItemFlag', $fbSalesOrder->getNewItemFlag());
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
            }else if($scOrder['marketID'] == 3 ){
                $fbSalesOrder->setProductPrice(round(-$scOrder['TotalSale'] * $scOrderObj->ini_array['PAYPAL_FEE'] - $scOrderObj->ini_array['PAYPAL_FEE_PLUS'],2)); 
            }else if($scOrder['marketID'] == 32 ){
                $fbSalesOrder->setProductPrice(round(-$scOrder['TotalSale'] * $scOrderObj->ini_array['RAKUTEN_FEE'] - $totalQty * $scOrderObj->ini_array['RAKUTEN_PLUS_PER_ITEM'],2));
            }else if($scOrder['marketID'] == 35 ){
                $fbSalesOrder->setProductPrice(round(-$scOrder['TotalSale'] * $scOrderObj->ini_array['NEWEGG_FEE'],2));
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
            }else if($scOrder['marketID'] == 3 ){
                $fbSalesOrder->setProductPrice(round(-$scOrder['TotalSale'] * $scOrderObj->ini_array['PAYPAL_FEE'] - $scOrderObj->ini_array['PAYPAL_FEE_PLUS'],2)); 
            }else if($scOrder['marketID'] == 32 ){
                $fbSalesOrder->setProductPrice(round(-$scOrder['TotalSale'] * $scOrderObj->ini_array['RAKUTEN_FEE'] - $totalQty * $scOrderObj->ini_array['RAKUTEN_PLUS_PER_ITEM'],2));
            }else if($scOrder['marketID'] == 35 ){
                $fbSalesOrder->setProductPrice(round(-$scOrder['TotalSale'] * $scOrderObj->ini_array['NEWEGG_FEE'],2));
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
        } 
        //Set the shipping
        /*$fbSalesOrder->setProductNum('AYC-SHIPPING');
        $fbSalesOrder->setTaxable('false');
        $fbSalesOrder->setQuantity(1);
        $fbSalesOrder->setProductPrice(0);
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
        $fbSalesOrder->setProductNum('Free-Shipping');
        $fbSalesOrder->setTaxable('false');
        $fbSalesOrder->setQuantity(' ');
        $fbSalesOrder->setProductPrice(' ');
        $fbSalesOrder->setUOMCode(' ');
        $fbSalesOrder->setItemType('30');
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
        */
        $ImportRq->addChild('IssueFlag', $fbSalesOrder->getIssueFlag());
        $ImportRq->addChild('IgnoreItems', $fbSalesOrder->getIgnoreItem());

        $salesOrderXml = $xml->asXML();

        // Output as XML: header() needs to be the first line in the output to screen
        //header('Content-type: text/plain');
        //header('Content-type: text/xml');
        //echo $salesOrderXml;

        $fbSalesOrder->saveSO($salesOrderXml);

        if ($fbSalesOrder->fbApi->statusCode != 1000) {
                    // Display error messages if it's not blank
                    if (!empty($fbSalesOrder->fbApi->statusMsg)) {
                            echo $fbSalesOrder->fbApi->statusMsg;
                    }
            }

            print_r($fbSalesOrder->fbApi->result);

        //echo"<br/>-----------------<br/>";

        echo"<br/>-----------------<br/>";
        echo "success!";
        echo ($salesOrderXml);
    }
    //echo ($scOrder['saleID']);
    //print_r ($scOrder->res);
