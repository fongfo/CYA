<?php

      //Configuration
      $access = " Add License Key Here";
      $userid = " Add User Id Here";
      $passwd = " Add Password Here";

      $accessSchemaFile = " Add AccessRequest Schema File";
      $requestSchemaFile = " Add ShipConfirmRequest Schema File";
      $responseSchemaFile = " Add ShipConfirmResponse Schema File";
      $ifSchemaFile = " Add IF Schema File";

      $endpointurl = ' Add URL Here';
      $outputFileName = "XOLTResult.xml";


      try
      {
         //create AccessRequest data object
         $das = SDO_DAS_XML::create("$accessSchemaFile");
    	 $doc = $das->createDocument();
         $root = $doc->getRootDataObject();
         $root->AccessLicenseNumber=$access;
         $root->UserId=$userid;
         $root->Password=$passwd;
         $security = $das->saveString($doc);

         //create ShipConfirmRequest data oject
         $das = SDO_DAS_XML::create("$requestSchemaFile");
         $das->addTypes("$ifSchemaFile");
         $requestDO = $das->createDataObject('','RequestType');
         $requestDO->RequestAction='ShipConfirm';
         $requestDO->RequestOption='nonvalidate';

         $doc = $das->createDocument('ShipmentConfirmRequest');
         $root = $doc->getRootDataObject();
         $root->Request = $requestDO;


         $labelSpecificationDO = $das->createDataObject('' , 'LabelSpecificationType');
         $labelPrintMethodDO = $das->createDataObject('' , 'LabelPrintMethodCodeDescriptionType');
         $labelPrintMethodDO->Code = 'GIF';
         $labelPrintMethodDO->Description = 'gif';
         $labelSpecificationDO->LabelPrintMethod = $labelPrintMethodDO;
         $labelSpecificationDO->HTTPUserAgent = 'Mozilla/4.5';
         $labelImageFormatDO = $das->createDataObject('' , 'LabelImageFormatCodeDescriptionType');
         $labelImageFormatDO->Code = 'GIF';
         $labelImageFormatDO->Description = 'gif';
         $labelSpecificationDO->LabelImageFormat = $labelImageFormatDO;
         $root->LabelSpecification = $labelSpecificationDO;

         $shipmentDO = $das->createDataObject('','ShipmentType');
         $rateInfoDO = $das->createDataObject('','RateInformationType');
         $rateInfoDO->NegotiatedRatesIndicator = '';
         $shipmentDO->RateInformation = $rateInfoDO;
         $shipmentDO->Description = '';

         $shipperDO = $das->createDataObject('', 'ShipperType');
         $shipperDO->Name = 'Shipper Name';
         $shipperDO->PhoneNumber = '1234567890';
         $shipperDO->TaxIdentificationNumber = '1234567877';
         $shipperDO->ShipperNumber = 'Your Shipper Number';
         $addressDO = $das->createDataObject('' , 'ShipperAddressType');
         $addressDO->AddressLine1 = '2311 York Rd';
         $addressDO->City = 'Timonium';
         $addressDO->StateProvinceCode = 'MD';
         $addressDO->PostalCode = '21093';
         $addressDO->CountryCode = 'US';
         $shipperDO->Address = $addressDO;
         $shipmentDO->Shipper = $shipperDO;

         $shipToDO = $das->createDataObject('','ShipToType');
         $shipToDO->CompanyName = 'Happy Dog Pet Supply';
         $shipToDO->AttentionName = 'Ship To Attention Name';
         $shipToDO->PhoneNumber = '1234567890';
         $addressToDO = $das->createDataObject('','ShipToAddressType');
         $addressToDO->AddressLine1 = 'GOERLITZER STR.1';
         $addressToDO->City = 'Neuss';
         $addressToDO->PostalCode = '41456';
         $addressToDO->CountryCode = 'DE';
         $shipToDO->Address = $addressToDO;
         $shipmentDO->ShipTo = $shipToDO;

         $shipFromDO = $das->createDataObject('','ShipFromType');
         $shipFromDO->CompanyName = 'Bullwinkle J. Moose';
         $shipFromDO->AttentionName = 'Bull';
         $shipFromDO->PhoneNumber = '1234567890';
         $shipFromDO->TaxIdentificationNumber = '1234567877';
         $addressFromDO = $das->createDataObject('','ShipFromAddressType');
         $addressFromDO->AddressLine1 = '2311 York Rd';
         $addressFromDO->City = 'City';
         $addressFromDO->StateProvinceCode = 'MD';
         $addressFromDO->PostalCode = '21093';
         $addressFromDO->CountryCode = 'US';
         $shipFromDO->Address = $addressFromDO;
         $shipmentDO->ShipFrom = $shipFromDO;

         $soldToDO = $das->createDataObject('','SoldToType');
         $soldToDO->Option = '01';
         $soldToDO->AttentionName = 'Sold To Attn Name';
         $soldToDO->CompanyName = 'Sold To Name';
         $soldToDO->PhoneNumber = '1234567890';
         $addressSoldToDO = $das->createDataObject('','SoldToAddressType');
         $addressSoldToDO->AddressLine1 = 'GOERLITZER STR.1';
		 $addressSoldToDO->City = 'Neuss';
		 $addressSoldToDO->PostalCode = '41456';
		 $addressSoldToDO->CountryCode = 'DE';
         $soldToDO->Address = $addressSoldToDO;
         $shipmentDO->SoldTo = $soldToDO;


         $paymentInfoDO = $das->createDataObject('','PaymentInformationType');
         $prepaidDO = $das->createDataObject('' , 'PrepaidType');
         $billshipperDO = $das->createDataObject('', 'BillShipperType');
         $billshipperDO->AccountNumber = 'Your Account Number';
         $prepaidDO->BillShipper = $billshipperDO;
         $paymentInfoDO->Prepaid = $prepaidDO;
         $shipmentDO->PaymentInformation = $paymentInfoDO;

         $serviceDO = $das->createDataObject('','ServiceType');
         $serviceDO->Code = '08';
         $serviceDO->Description = 'Expedited';
         $shipmentDO->Service = $serviceDO;

         $shipmentServiceOptionsDO = $das->createDataObject('','ShipmentServiceOptionsType');
         $internationalFormDO = $das->createDataObject('','InternationalFormsType');
         $productDO = $das->createDataObject('','ProductType');
         $productDO->Description = 'Product 1';
         $productDO->CommodityCode = '111222AA';
         $productDO->OriginCountryCode = 'US';
         $unitProductDO = $das->createDataObject('','UnitType');
         $unitProductDO->Number = '147';
         $unitProductDO->Value = '478';
         $uomDO = $das->createDataObject('','CodeType');
         $uomDO->Code = 'BOX';
         $uomDO->Description = 'BOX';
         $unitProductDO->UnitOfMeasurement = $uomDO;
         $productDO->Unit = $unitProductDO;
         $productWeightDO = $das->createDataObject('','ProductWeightType');
         $productWeightDO->Weight = '10';
         $uomProductWeightDO = $das->createDataObject('','CodeType');
         $uomProductWeightDO->Code = 'LBS';
         $uomProductWeightDO->Description = 'LBS';
         $productWeightDO->UnitOfMeasurement = $uomProductWeightDO;
         $productDO->ProductWeight = $productWeightDO;
         $internationalFormDO->Product = $productDO;
         $discountDO = $das->createDataObject('','DiscountTpe');
         $discountDO->MonetaryValue = '100';
         $internationalFormDO->Discount = $discountDO;
         $freightDO = $das->createDataObject('','FreightChargesType');
         $freightDO->MonetaryValue = '50';
         $internationalFormDO->FreightCharges = $freightDO;
         $insuranceDO = $das->createDataObject('','InsuranceChargesType');
         $insuranceDO->MonetaryValue = '200';
         $internationalFormDO->InsuranceCharges = $insuranceDO;
         $otherChargesDO = $das->createDataObject('','OtherChargesType');
         $otherChargesDO->MonetaryValue = '50';
         $otherChargesDO->Description = 'Misc';
         $internationalFormDO->OtherCharges = $otherChargesDO;
         $internationalFormDO->CurrencyCode = 'USD';
         $internationalFormDO->InvoiceNumber = 'asdf123';
         $internationalFormDO->InvoiceDate = '20151225';
         $internationalFormDO->PurchaseOrderNumber = '999jjj777';
         $internationalFormDO->TermsOfShipment = 'CFR';
         $internationalFormDO->ReasonForExport = 'Sale';
         $internationalFormDO->Comments = 'Your Comments';
         $internationalFormDO->DeclarationStatement = 'Your Declaration Statement';
         $shipmentServiceOptionsDO->InternationalForms = $internationalFormDO;
         $shipmentDO->ShipmentServiceOptions = $shipmentServiceOptionsDO;


         $packageDO = $das->createDataObject('' , 'PackageType');
         $packagingTypeDO = $das->createDataObject('' , 'PackagingTypeType');
         $packagingTypeDO->Code = '02';
         $packagingTypeDO->Description = 'Customer Supplied';
         $packageDO->PackagingType = $packagingTypeDO;
         $packageDO->Description = 'Package Description';


         $packageWeightDO = $das->createDataObject('' , 'PackageWeightType');
         $unitDO = $das->createDataObject('' , 'UnitOfMeasurementType');
         $packageWeightDO->UnitOfMeasurement = $unitDO;
         $packageWeightDO->Weight = '60.0';
         $packageDO->PackageWeight = $packageWeightDO;
         $shipmentDO->Package = $packageDO;
         $root->Shipment = $shipmentDO;
         $request = $das->saveString($doc);

         //create Post request
         $form = array
         (
             'http' => array
             (
                 'method' => 'POST',
                 'header' => 'Content-type: application/x-www-form-urlencoded',
                 'content' => "$security$request"
             )
         );

         //print form request
         print_r($form);


         $request = stream_context_create($form);
         $browser = fopen($endpointurl , 'rb' , false , $request);
         if(!$browser)
         {
             throw new Exception("Connection failed.");
         }

         //get response
         $response = stream_get_contents($browser);
         fclose($browser);

         if($response == false)
         {
            throw new Exception("Bad data.");
         }
         else
         {
            //save request and response to file
  	    $fw = fopen($outputFileName,'w');
            fwrite($fw , "Response: \n" . $response . "\n");
            fclose($fw);

            //get response status
            $resp = new SimpleXMLElement($response);
            echo $resp->Response->ResponseStatusDescription . "\n";
         }
      }
      catch(SDOException $sdo)
      {
      	 echo $sdo;
      }
      catch(Exception $ex)
      {
      	 echo $ex;
      }

?>

