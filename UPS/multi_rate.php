<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
error_reporting(0); 

require dirname(__file__) . "\UPS.php";
require_once "/../fishbowlSalesOrder-scripts/scripts/models/searchOrder.php";

$SCObj = new searchOrder();
$rate = new UPS();

$scSku = parse_ini_file("/../fishbowlSalesOrder-scripts/scripts/models/scSku_map.ini",true);
$kitSku = parse_ini_file("/../fishbowlSalesOrder-scripts/scripts/models/kit_map.ini",true);

$context = "AYC";
$Num = $_POST["soNum"];
$Method = $_POST["Method"];
$NegOrPub = $_POST["public"];
echo $_POST['Method'];

$scOrder = $SCObj->getSCOrder($Num);
$orderItems = $scOrder['OrderItems'];
//$context = "test";

$totalPrice = 0;
$totalPublic = 0;
        
//Check if multiple items     

if(!ISSET($orderItems['OrderItem']['SKU'])){    
    foreach($orderItems['OrderItem'] as $item_key => $item_value){
        $sku = isset($scSku['SC-FB'][$item_value['SKU']])? $scSku['SC-FB'][$item_value['SKU']] : $item_value['SKU'];
        print_r($item_value['SKU']);
        print_r($sku) ;
        $requestVar=array(
            "context" =>$context,
            "soNum" =>$Num,
            "sku" =>$sku,
            "shipMethod"=>$Method,
            "public"=>$NegOrPub
        );
        print_r($requestVar);
        $rate->setRateRequest($requestVar);


        $result = $rate->invokeRateRequest();
        $ne_price = $result->RateResponse->RatedShipment->NegotiatedRateCharges->TotalCharge->MonetaryValue;
        print_r($result);
        $pu_price = $result->RateResponse->RatedShipment->RatedPackage->TransportationCharges->MonetaryValue;
        print_r($item_value['SKU']."-Negotiated Rate price is:".$result->RateResponse->RatedShipment->NegotiatedRateCharges->TotalCharge->MonetaryValue);
        echo"<br>";
        print_r($item_value['SKU']."-Public Rate price is:". $result->RateResponse->RatedShipment->RatedPackage->TransportationCharges->MonetaryValue);
        echo"<br>";
        $totalPrice = $totalPrice + $ne_price*$item_value['Qty'];
        $totalPublic = $totalPublic + $pu_price*$item_value['Qty'];
    }
    
}else{
    $sku = isset($scSku['SC-FB'][$scOrder['OrderItems']['OrderItem']['SKU']])?$scSku['SC-FB'][$scOrder['OrderItems']['OrderItem']['SKU']]:$scOrder['OrderItems']['OrderItem']['SKU'];
        
    $requestVar=array(
        "context" =>$context,
        "soNum" =>$Num,
        "sku" =>$sku,
        "shipMethod"=>$Method,
        "public"=>$NegOrPub
    );

    $rate->setRateRequest($requestVar);

    $result = $rate->invokeRateRequest();
    
    //print_r($result);
    $ne_price = $result->RateResponse->RatedShipment->NegotiatedRateCharges->TotalCharge->MonetaryValue;
    $pu_price = $result->RateResponse->RatedShipment->RatedPackage->TransportationCharges->MonetaryValue;
    $totalPrice=$ne_price*$scOrder['OrderItems']['OrderItem']['Qty'];
    echo"<br>";
    $totalPublic=$pu_price*$scOrder['OrderItems']['OrderItem']['Qty'];
}

print_r("Total Negotiated Rate price is:".$totalPrice);
echo"<br>";
print_r("Total Public Rate price is:".$totalPublic);