 no warnings; # turn off warnings
 
 use XML::Compile::WSDL11;
 use XML::Compile::SOAP11;
 use XML::Compile::Transport::SOAPHTTP;
 use HTTP::Request;
 use HTTP::Response;
 use Data::Dumper;
 
 #Configuration
 $access = " Add License Key Here";
 $userid = " Add User Id Here";
 $passwd = " Add Password Here";
 $operation = "ProcessShipment";
 $endpointurl = " Add URL Here";
 $wsdlfile = " Add Wsdl File Here ";
 $schemadir = "Add Schema Location Here";
 $outputFileName = "XOLTResult.xml";
 
 sub processShipment
 {
 	my $request =
 	{
 		UPSSecurity =>  
	  	{
		   UsernameToken =>
		   {
			   Username => "$userid",
			   Password => "$passwd"
		   },
		   ServiceAccessToken =>
		   {
			   AccessLicenseNumber => "$access"
		   }
	  	},
	    
	    Request =>
	    {
	    	RequestOption => ['1' , 'Shipping'],
	    	TransactionReference =>
	    	{
	    		CustomerContext => 'Add description'
	    	}
	    },
	    Shipment =>
	    {
	    	ShipFrom =>
	    	{
	    		Name => 'Pat Stewart',
	    		TaxIdentificationNumber => '1234567890',
	    		Address =>
	    		{
	    			 AddressLine => '2311 York Road',
	    			 City => 'Timonium',
	    			 StateProvinceCode => 'MD',
	    			 PostalCode => '21093',
	    			 CountryCode => 'US'
	    		},
	    		AttentionName => 'String',
	    		Phone =>
	    		{
	    			Number => '6785851000',
	    			Extension => '123'
	    		},
	    	},
	    	ShipperNumber => 'Your Shipper Number',
	    	ShipTo =>
	    	{
	    		Name => 'Superman',
	    		Address =>
	    		{
	    			AddressLine => '2010 Warsaw Road',
	    			StateProvinceCode => 'ON',
	    			PostalCode => 'M4C2M6',
	    			CountryCode => 'CA',
	    			
	    			City => 'Roswell',
	    			StateProvinceCode => 'GA',
	    			PostalCode => '30076',
	    			CountryCode => 'US'
	    		},
	    		AttentionName => 'String',
	    		Phone =>
	    		{
	    			Number => '6785851000',
	    			Extension => '111'
	    		}
	    	},
	    	PaymentInformation =>
	    	{
	    		Payer => 
	    		{
	    			Name => 'Superman',
	    			Address =>
	    			{
	    				AddressLine => '2010 Warsaw Road',
	    				City => 'Roswell',
	    				StateProvinceCode => 'GA',
	    				PostalCode => '30075',
	    				CountryCode => 'US'
	    			},
	    			ShipperNumber => 'Your Shipper Number',
	    			AttentionName => 'String',
	    			Phone =>
	    			{
	    				Number => '6785851000'
	    			},
	    		},
	    		ShipmentBillingOption =>
	    		{
	    			Code => '10',
	    			Description => 'String'
	    		}
	    	},
	    	Service =>
	    	{
	    		Code => '308',
	    		Description => 'String'
	    	},
	    	HandlingUnitOne =>
	    	{
	    		Quantity => '16',
	    		Type =>
	    		{
	    			Code => 'PLT',
	    			Description => 'String'
	    		}
	    	},
	    	Commodity =>
	    	{
	    		CommodityID => '22',
	    		Description => 'BUGS',
	    		Weight =>
	    		{
	    			UnitOfMeasurement =>
	    			{
	    				Code => 'LBS',
	    				Description => 'String'
	    			},
	    			Value => '511.25'
	    		},
	    		Dimensions =>
	    		{
	    			UnitOfMeasurement =>
	    			{
	    				Code => 'IN',
	    				Description => 'String'
	    			},
	    	        Length => '1.25',
	    	        Width => '1.2',
	    	        Height => '5'
	    		},
	    		NumberOfPieces => '1',
	    		PackagingType =>
	    		{
	    			Code => 'PLT',
	    			Description => 'String'
	    		},
	    		CommodityValue =>
	    		{
	    			CurrencyCode => 'USD',
	    			MonetaryValue => '265.2'
	    		},
	    		FreightClass => '60',
	    		NMFCCommodityCode => '566'
	    	},
	    	Reference =>
	    	{
	    		Number =>
	    		{
	    			Code => 'PM',
	    			Value => '1651651616'
	    		},
	    		BarCodeIndicator =>
	    		{
	    			NumberOfCartons => '5',
	    			Weight =>
	    			{
	    				UnitOfMeasurement =>
	    				{
	    					Code => 'LBS',
	    					Description => 'String'
	    				},
	    				Value => '2'
	    			}
	    		}
	    	}
	    }
 	};
 	
 	return $request;
 }
 
 my $wsdl = XML::Compile::WSDL11->new( $wsdlfile );
 my @schemas = glob "$schemadir/*.xsd";
 $wsdl->importDefinitions(\@schemas) if scalar(@schemas) > 0;
 my $operation = $wsdl->operation($operation);
 my $call = $operation->compileClient(endpoint => $endpointurl);
 
 ($answer , $trace) = $call->(processShipment() , 'UTF-8');	
 
 if($answer->{Fault})
 {
	print $answer->{Fault}->{faultstring} ."\n";
	print Dumper($answer);
	print "See XOLTResult.xml for details.\n";
		
	# Save Soap Request and Response Details
	open(fw,">$outputFileName");
	$trace->printRequest(\*fw);
	$trace->printResponse(\*fw);
	close(fw);
 }
 else
 {
	# Get Response Status Description
    print "Description: " . $answer->{Body}->{Response}->{ResponseStatus}->{Description} . "\n"; 
        
    # Print Request and Response
    my $req = $trace->request();
 	print "Request: \n" . $req->content() . "\n";
	my $resp = $trace->response();
	print "Response: \n" . $resp->content();
		
	# Save Soap Request and Response Details
	open(fw,">$outputFileName");
	$trace->printRequest(\*fw);
	$trace->printResponse(\*fw);
	close(fw);
}
 