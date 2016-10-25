<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


require dirname(__file__) . "\UPS.php";

$rate = new UPS();


$context = $_POST["Context"];
$Num = $_POST["soNum"];
$requestVar=array(
  "context" =>$context,

  "soNum" =>$Num,


);

$rate->setRateRequest($requestVar);


$result = $rate->invokeRateRequest();
print_r("Negotiated Rate price is:".$result->RateResponse->RatedShipment->NegotiatedRateCharges->TotalCharge->MonetaryValue);
echo"<br>";
print_r("Public Rate price is:". $result->RateResponse->RatedShipment->RatedPackage->TransportationCharges->MonetaryValue);

