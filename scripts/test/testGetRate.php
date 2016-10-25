<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


require dirname(__file__) . "/../classes/UPS/UPS.php";

$rate = new UPS();

$requestVar=array(
  "context" =>'test',
  "soNum" => '479066967',
);
try {
$rate->setRateRequest($requestVar);


$result = $rate->invokeRateRequest();
var_dump($result);
}catch( Exception $e) {
        var_dump( $e );
    }