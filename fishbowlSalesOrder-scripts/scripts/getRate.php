<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require "models/UPS.php";
//require "models/FBSalesOrder.php";
$ups = new UPS();
//$scOrderObj = new searchOrder();
$parameters = array(
   "UPSSecurity"=>array(
        "UsernameToken"=>array(
            "Username"=>'deanchou',
            "Password"=>'Anderson9729266488'
        ),
        "ServiceAccessToken"=>array(
            "AccessLicenseNumber"=>'5D16906D343745DE'
        )
    ),
  "RateRequest" => array(
      "Request"=> array(
          "RequestOption"=> "Rate",              
          ),
      "Shipment"=>array(
          
          "Shipper" => array(        
              "Name"=>'AYC GROUP',
              "ShipperNumber"=> "2Y263A",
              "Address"=>array(
              "AddressLine"=>'',
              "City"=>'GARLAND',
              "StateProvinceCode"=> 'TX',
              "PostalCode"=> "75041",
              "CountryCode"=> 'US'   
              ),
              
          ),
          
          "ShipTo" => array(
              "Name"=>'AYC GROUP',
              "Address"=>array(
                  "AddressLine"=>'525 N Estrella Pkwy Ste 104',
                  "City"=>'GOODYEAR',
                    "StateProvinceCode"=> 'AZ',
                    "PostalCode"=> "85338",
                    "CountryCode"=> 'US'
                  )
              ),
              
          
          "ShipFrom"=> array(
              "Name"=>'AYC GROUP',
              "Address"=>array(
                  "AddressLine"=>'',                  
              "City"=>'GARLAND',
              "StateProvinceCode"=> 'TX',
              "PostalCode"=> "75041",
              "CountryCode"=> 'US'   
                  )
              ),
          
          "Service"=> array(
              "Code"=>'03',
              "Description"=>'UPS GROUND'
          ),
          
          "Package"=>array(
              "PackagingType"=>array(
                  "Code"=>'02',
                  "Description"=>"Rate"
              ),
              "Dimensions"=>ARRAY(
                  "UnitOfMeasurement"=>ARRAY(
                       "Code"=>'IN',
                       "Description"=>'iNCHES'    
                  ),
                  "Length"=>"13",
                  "Width"=>"19",
                  "Height"=>"19"
              ),
              "PackageWeight"=>ARRAY (
                  "UnitOfMeasurement"=>ARRAY(
                        "Code"=>"Lbs",
                        "Description"=>'pounds'
                    ),
                    "Weight"=>"18"
                )   
          ),
          "ShipmentRatingOptions"=>array(
              "NegotiatedRatesIndicator" =>''
          )
          ),
      ),
      
  );

$rate = $ups->call($parameters);
print_r($rate);
$result = $rate->RateResponse->RatedShipment->NegotiatedRateCharges->TotalCharge->MonetaryValue;
print_r($result);

//stdClass Object ( [RateResponse] => stdClass Object ( [Response] => stdClass Object ( [ResponseStatus] => stdClass Object ( [Code] => 1 [Description] => Success ) [Alert] => stdClass Object ( [Code] => 110971 [Description] => Your invoice may vary from the displayed reference rates ) [TransactionReference] => ) [RatedShipment] => stdClass Object ( [Service] => stdClass Object ( [Code] => 03 [Description] => ) [RatedShipmentAlert] => stdClass Object ( [Code] => 110971 [Description] => Your invoice may vary from the displayed reference rates ) [BillingWeight] => stdClass Object ( [UnitOfMeasurement] => stdClass Object ( [Code] => LBS [Description] => Pounds ) [Weight] => 29.0 ) [TransportationCharges] => stdClass Object ( [CurrencyCode] => USD [MonetaryValue] => 21.21 ) [ServiceOptionsCharges] => stdClass Object ( [CurrencyCode] => USD [MonetaryValue] => 0.00 ) [TotalCharges] => stdClass Object ( [CurrencyCode] => USD [MonetaryValue] => 21.21 ) [NegotiatedRateCharges] => stdClass Object ( [TotalCharge] => stdClass Object ( [CurrencyCode] => USD [MonetaryValue] => 14.40 ) ) [RatedPackage] => stdClass Object ( [TransportationCharges] => stdClass Object ( [CurrencyCode] => USD [MonetaryValue] => 21.21 ) [ServiceOptionsCharges] => stdClass Object ( [CurrencyCode] => USD [MonetaryValue] => 0.00 ) [TotalCharges] => stdClass Object ( [CurrencyCode] => USD [MonetaryValue] => 21.21 ) [Weight] => 18.0 [BillingWeight] => stdClass Object ( [UnitOfMeasurement] => stdClass Object ( [Code] => LBS [Description] => Pounds ) [Weight] => 29.0 ) ) ) ) ) 