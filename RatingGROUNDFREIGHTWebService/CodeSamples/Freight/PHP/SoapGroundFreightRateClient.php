<?php

  //Configuration
  $access = " Add License Key Here";
  $userid = " Add User Id Here";
  $passwd = " Add Password Here";
  $wsdl = " Add Wsdl File Here ";
  $operation = "ProcessFreightRate";
  $endpointurl = ' Add URL Here';
  $outputFileName = "XOLTResult.xml";

  function processFreightRate()
  {
      //create soap request
      $option['RequestOption'] = 'RateChecking Option';
      $request['Request'] = $option;
      $shipfrom['Name'] = 'Good Incorporation';
      $addressFrom['AddressLine'] = '2010 WARSAW ROAD';
      $addressFrom['City'] = 'Roswell';
      $addressFrom['StateProvinceCode'] = 'GA';
      $addressFrom['PostalCode'] = '30076';
      $addressFrom['CountryCode'] = 'US';
      $shipfrom['Address'] = $addressFrom;
      $request['ShipFrom'] = $shipfrom;

      $shipto['Name'] = 'Sony Company Incorporation';
      $addressTo['AddressLine'] = '2311 YORK ROAD';
      $addressTo['City'] = 'TIMONIUM';
      $addressTo['StateProvinceCode'] = 'MD';
      $addressTo['PostalCode'] = '21093';
      $addressTo['CountryCode'] = 'US';
      $shipto['Address'] = $addressTo;
      $request['ShipTo'] = $shipto;

      $payer['Name'] = 'Payer inc';
      $addressPayer['AddressLine'] = '435 SOUTH STREET';
      $addressPayer['City'] = 'RIS TOWNSHIP';
      $addressPayer['StateProvinceCode'] = 'NJ';
      $addressPayer['PostalCode'] = '07960';
      $addressPayer['CountryCode'] = 'US';
      $payer['Address'] = $addressPayer;
      $shipmentbillingoption['Code'] = '10';
      $shipmentbillingoption['Description'] = 'PREPAID';
      $paymentinformation['Payer'] = $payer;
      $paymentinformation['ShipmentBillingOption'] = $shipmentbillingoption;
      $request['PaymentInformation'] = $paymentinformation;

      $service['Code'] = '308';
      $service['Description'] = 'UPS Freight LTL';
      $request['Service'] = $service;

      $handlingunitone['Quantity'] = '20';
      $handlingunitone['Type'] = array
      (
          'Code' => 'PLT',
          'Description' => 'PALLET'
      );
      $request['HandlingUnitOne'] = $handlingunitone;

      $commodity['CommodityID'] = '';
      $commodity['Description'] = 'No Description';
      $commodity['Weight'] = array
      (
         'UnitOfMeasurement' => array
         (
             'Code' => 'LBS',
             'Description' => 'Pounds'
         ),
         'Value' => '750'
      );
      $commodity['Dimensions'] = array
      (
          'UnitOfMeasurement' => array
          (
              'Code' => 'IN',
              'Description' => 'Inches'
          ),
          'Length' => '23',
          'Width' => '17',
          'Height' => '45'
      );
      $commodity['NumberOfPieces'] = '45';
      $commodity['PackagingType'] = array
      (
           'Code' => 'BAG',
           'Description' => 'BAG'
      );
      $commodity['DangerousGoodsIndicator'] = '';
      $commodity['CommodityValue'] = array
      (
           'CurrencyCode' => 'USD',
           'MonetaryValue' => '5670'
      );
      $commodity['FreightClass'] = '60';
      $commodity['NMFCCommodityCode'] = '';
      $request['Commodity'] = $commodity;

      $shipmentserviceoptions['PickupOptions'] = array
      (
            'HolidayPickupIndicator' => '',
	  	    'InsidePickupIndicator' => '',
	  		'ResidentialPickupIndicator' => '',
	  		'WeekendPickupIndicator' => '',
	  		'LiftGateRequiredIndicator' => ''
	  );
	  $shipmentserviceoptions['OverSeasLeg'] = array
	  (
	         'Dimensions' => array
	         (
	              'Volume' => '20',
	              'UnitOfMeasurement' => array
	              (
	                  'Code' => 'CF',
	                  'Description' => 'String'
	              )
	         ),
	         'Value' => array
	         (
	              'Cube' => array
	              (
	                   'CurrencyCode' => 'USD',
	                   'MonetaryValue' => '5670'
	              )
	         ),
	         'COD' => array
	         (
	              'CODValue' => array
	              (
	                   'CurrencyCode' => 'USD',
	                   'MonetaryValue' => '363'
	              ),
	              'CODPaymentMethod' => array
	              (
	                   'Code' => 'M',
	                   'Description' => 'For Company Check'
	              ),
	              'CODBillingOption' => array
	              (
	                   'Code' => '01',
	                   'Description' => 'Prepaid'
	              ),
	              'RemitTo' => array
	              (
	                   'Name' => 'RemitToSomebody',
	                   'Address' => array
	                   (
	                         'AddressLine' => '640 WINTERS AVE',
	  						 'City' => 'PARAMUS',
	  					     'StateProvinceCode' => 'NJ',
	  					     'PostalCode' => '07652',
	  					     'CountryCode' => 'US'
	  				   ),
	  				   'AttentionName' => 'C J Parker'
	  			  )
	  		  ),
	  		  'DangerousGoods' => array
	  		  (
					'Name' => 'Very Safe',
					'Phone' => array
					(
						'Number' => '453563321',
						'Extension' => '1111'
					),
					'TransportationMode' => array
					(
						'Code' => 'CARGO',
						'Description' => 'Cargo Mode'
					)
	  		  ),
	  		  'SortingAndSegregating' => array
	  		  (
	  				'Quantity' => '23452'
	  		  ),
	  		  'CustomsValue' => array
	  		  (
	  				'CurrencyCode' => 'USD',
	  				'MonetaryValue' => '23457923'
	  		  ),
	  		  'HandlingCharge' => array
	  		  (
					'Amount' => array
					(
						'CurrencyCode' => 'USD',
						'MonetaryValue' => '450'
					)
	  		  )
	   );
	  $request['ShipmentServiceOptions'] = $shipmentserviceoptions;

      echo "Request.......\n";
      print_r($request);
      echo "\n\n";
      return $request;
  }

  try
  {

    $mode = array
    (
         'soap_version' => 'SOAP_1_1',  // use soap 1.1 client
         'trace' => 1
    );

    // initialize soap client
  	$client = new SoapClient($wsdl , $mode);

  	//set endpoint url
  	$client->__setLocation($endpointurl);


    //create soap header
    $usernameToken['Username'] = $userid;
    $usernameToken['Password'] = $passwd;
    $serviceAccessLicense['AccessLicenseNumber'] = $access;
    $upss['UsernameToken'] = $usernameToken;
    $upss['ServiceAccessToken'] = $serviceAccessLicense;

    $header = new SoapHeader('http://www.ups.com/XMLSchema/XOLTWS/UPSS/v1.0','UPSSecurity',$upss);
    $client->__setSoapHeaders($header);


    //get response
  	$resp = $client->__soapCall($operation ,array(processFreightRate()));

    //get status
    echo "Response Status: " . $resp->Response->ResponseStatus->Description ."\n";

    //save soap request and response to file
    $fw = fopen($outputFileName , 'w');
    fwrite($fw , "Request: \n" . $client->__getLastRequest() . "\n");
    fwrite($fw , "Response: \n" . $client->__getLastResponse() . "\n");
    fclose($fw);

  }
  catch(Exception $ex)
  {
  	print_r ($ex);
  }

?>
