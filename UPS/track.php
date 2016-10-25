<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require dirname(__file__) . "\UPS.php";

$track = new UPS();

$requestVar=array(
  "context" =>'test',
    "number" =>"1Z2Y263A9099970604"
);

$track->setTrackRequest($requestVar);


$result = $track->invokeTrackRequest();
print_r($result);


/*$parameters = array(
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
$track->call(rate,$parameters);*/