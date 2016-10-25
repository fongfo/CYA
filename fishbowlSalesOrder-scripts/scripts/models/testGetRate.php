
<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require "models/FBSalesOrder.php";
require "models/UPS.php";
define('UPS_INI', "models/upsConfig.ini");
//require "models/FBSalesOrder.php";


class getRate{
    
public $ups;
public $ups_ini;
public $parameters;
public $shipToName;
public $shipToAddress;
public $shipToCity;
public $shipToStateProvinceCode;
public $shipToPostalCode;
public $shipToCountryCode;
public $Length;
public $Width;
public $Height;
public $weight;
        

function _construct(){
    $this->ups = new UPS();
    $this->ups_ini = parse_ini_file(UPS_INI);
    

    
    
    
    $rate = $ups->call($parameters);
    print_r($rate);
    $result = $rate->RateResponse->RatedShipment->NegotiatedRateCharges->TotalCharge->MonetaryValue;
    print_r($result);

}

public function setParameters($parameter){
$this->parameters = array(
"UPSSecurity"=>array(
    "UsernameToken"=>array(
        "Username"=>$ups_ini['UPS_USER'],
        "Password"=>$ups_ini['UPS_PASSWORD']
    ),
    "ServiceAccessToken"=>array(
        "AccessLicenseNumber"=>$ups_ini['ACCESS_KEY']
    )
),
"RateRequest" => array(
  "Request"=> array(
      "RequestOption"=> "Rate",              
      ),
  "Shipment"=>array(

      "Shipper" => array(        
          "Name"=>$ups_ini['SHIPPER_NAME'],
          "ShipperNumber"=>$ups_ini['SHIPPER_NUMBER'],
          "Address"=>array(
          "AddressLine"=>'',
          "City"=>$ups_ini['SHIPPER_CITY'],
          "StateProvinceCode"=> $ups_ini['SHIPPER_StateProvinceCode'],
          "PostalCode"=>$ups_ini['SHIPPER_POSTALCODE'],
          "CountryCode"=>$ups_ini['SHIPPER_COUNTRYCODE']   
          ),

      ),

      "ShipTo" => array(
          "Name"=>$this->getShipToName(),
          "Address"=>array(
              "AddressLine"=>$this->getShipToAddress(),
              "City"=>$this->getShipToCity(),
                "StateProvinceCode"=>$this->getShipToStateProvinceCode(),
                "PostalCode"=>$this->getShipToPostalCode(),
                "CountryCode"=>$this->getShipToCountryCode()
              )
          ),


      "ShipFrom"=> array(
          "Name"=>$ups_ini['SHIPPER_NAME'],
          "Address"=>array(
          "AddressLine"=>'',
          "City"=>$ups_ini['SHIPPER_CITY'],
          "StateProvinceCode"=> $ups_ini['SHIPPER_StateProvinceCode'],
          "PostalCode"=>$ups_ini['SHIPPER_POSTALCODE'],
          "CountryCode"=>$ups_ini['SHIPPER_COUNTRYCODE']     
              )
          ),

      "Service"=> array(
          "Code"=>$ups_ini['UPS_GROUND_CODE'],
          "Description"=>$ups_ini['UPS_GROUND_DES']
      ),

      "Package"=>array(
          "PackagingType"=>array(
              "Code"=>$ups_ini['PACKAGE_CODE'],
              "Description"=>$ups_ini['PACKAGE_DES']
          ),
          "Dimensions"=>ARRAY(
              "UnitOfMeasurement"=>ARRAY(
                   "Code"=>$ups_ini['DIMENSION_CODE'],
                   "Description"=>$ups_ini['DIMENSION_DES']    
              ),
              "Length"=>$this->getLength(),
              "Width"=>$this->getWidth(),
              "Height"=>$this->getHeight()
          ),
          "PackageWeight"=>ARRAY (
              "UnitOfMeasurement"=>ARRAY(
                    "Code"=>$ups_ini['PACKAGEWEIGHT_CODE']   ,
                    "Description"=>$ups_ini['PACKAGEWEIGHT_DES']   
                ),
                "Weight"=>$this->getweight()
            )   
      ),
      "ShipmentRatingOptions"=>array(
          "NegotiatedRatesIndicator" =>''
      )
      ),
  ),

);
}

public function setShipToNmae($shipToName){
    $this->shipToName = $shipToName;
}
public function getShipToNmae(){
    return $this->shipToName;
}

public function setShipToAddress($shipToAddress){
    $this->shipToAddress = $shipToAddress;
}
public function getShipToAddress(){
    return $this->shipToAddress;
}

public function setShipToCity($shipToCity){
    $this->shipToCity = $shipToCity;
}
public function getShipToCity(){
    return $this->shipToCity;
}

public function setShipToStateProvinceCode($shipToStateProvinceCode){
    $this->shipToStateProvinceCode = $shipToStateProvinceCode;
}
public function getShipToStateProvinceCode(){
    return $this->shipToStateProvinceCode;
}

public function setShipToCountryCode($shipToCountryCode){
    $this->shipToName = $shipToName;
}
public function getShipToCountryCode(){
    return $this->shipToCountryCode;
}

public function setShipToPostalCode($shipToPostalCode){
    $this->shipToPostalCode = $shipToPostalCode;
}
public function getShipToPostalCode(){
    return $this->shipToPostalCode;
}
public function setLength($Length){
    $this->Length = $Length;
}
public function getLength(){
    return $this->Length;
}

public function setWidth($Width){
    $this->Width = $Width;
}
public function getWidth(){
    return $this->Width;
}

public function setHeight($Height){
    $this->Height = $Height;
}
public function getHeight(){
    return $this->Height;
}

public function setweight($weight){
    $this->weight = $weight;
}
public function getweight(){
    return $this->weight;
}
//$scOrderObj = new searchOrder();



}
//stdClass Object ( [RateResponse] => stdClass Object ( [Response] => stdClass Object ( [ResponseStatus] => stdClass Object ( [Code] => 1 [Description] => Success ) [Alert] => stdClass Object ( [Code] => 110971 [Description] => Your invoice may vary from the displayed reference rates ) [TransactionReference] => ) [RatedShipment] => stdClass Object ( [Service] => stdClass Object ( [Code] => 03 [Description] => ) [RatedShipmentAlert] => stdClass Object ( [Code] => 110971 [Description] => Your invoice may vary from the displayed reference rates ) [BillingWeight] => stdClass Object ( [UnitOfMeasurement] => stdClass Object ( [Code] => LBS [Description] => Pounds ) [Weight] => 29.0 ) [TransportationCharges] => stdClass Object ( [CurrencyCode] => USD [MonetaryValue] => 21.21 ) [ServiceOptionsCharges] => stdClass Object ( [CurrencyCode] => USD [MonetaryValue] => 0.00 ) [TotalCharges] => stdClass Object ( [CurrencyCode] => USD [MonetaryValue] => 21.21 ) [NegotiatedRateCharges] => stdClass Object ( [TotalCharge] => stdClass Object ( [CurrencyCode] => USD [MonetaryValue] => 14.40 ) ) [RatedPackage] => stdClass Object ( [TransportationCharges] => stdClass Object ( [CurrencyCode] => USD [MonetaryValue] => 21.21 ) [ServiceOptionsCharges] => stdClass Object ( [CurrencyCode] => USD [MonetaryValue] => 0.00 ) [TotalCharges] => stdClass Object ( [CurrencyCode] => USD [MonetaryValue] => 21.21 ) [Weight] => 18.0 [BillingWeight] => stdClass Object ( [UnitOfMeasurement] => stdClass Object ( [Code] => LBS [Description] => Pounds ) [Weight] => 29.0 ) ) ) ) ) 