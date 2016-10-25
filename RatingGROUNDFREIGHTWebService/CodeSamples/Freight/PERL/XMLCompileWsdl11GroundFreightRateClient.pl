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
 $operation = "ProcessFreightRate";
 $endpointurl = " Add URL Here";
 $wsdlfile = " Add Wsdl File Here ";
 $schemadir = "Add Schema Location Here";
 $outputFileName = "XOLTResult.xml";
 
 sub processFreightRate
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
	  		RequestOption => 'RateChecking Option'
	  	},
	  	ShipFrom =>
	  	{
	  		Name => 'Goods Incorporation',
	  		Address =>
	  		{
	  			AddressLine => '2010 WARSAW ROAD',
	  			City => 'Roswell',
	  			StateProvinceCode => 'GA',
	  			PostalCode => '30076',
	  			CountryCode => 'US'
	  		}
	  	},
	  	ShipTo =>
	  	{
	  		Name => 'Sony Company Incorporation',
	  		Address =>
	  		{
	  			AddressLine => '2311 YORK ROAD',
	  			City => 'TIMONIUM',
	  			StateProvinceCode => 'MD',
	  			PostalCode => '21093',
	  			CountryCode => 'US'
	  		}
	  	},
	  	PaymentInformation =>
	  	{
	  		Payer =>
	  		{
	  			Name => 'Payer Inc',
	  			Address =>
	  			{
	  				AddressLine => '435 SOUTH STREET',
	  				City => 'RIS TOWNSHIP',
	  				StateProvinceCode => 'NJ',
	  				PostalCode => '07960',
	  				CountryCode => 'US'
	  			}
	  		},
	  		ShipmentBillingOption =>
	  		{
	  			Code => '10',
	  			Description => 'PREPAID'
	  		}
	  	},
	  	Service =>
	  	{
	  		Code =>'308',
	  		Description => 'UPS Freight LTL'
	  	},
	  	HandlingUnitOne => 
	  	{
	  		Quantity => '20',
	  		Type =>
	  		{
	  			Code => 'PLT',
	  			Description => 'PALLET'
	  		}
	  	},
	  	Commodity =>
	  	{
	  		CommodityID => '',
	  		Description => 'No Description',
	  		Weight => 
	  		{
	  			UnitOfMeasurement =>
	  			{
	  				Code => 'LBS',
	  				Description => 'Pounds'
	  			},
	  			Value => '750'
	  		},
	  		Dimensions =>
	  		{
	  			UnitOfMeasurement =>
	  			{
	  				Code => 'IN',
	  				Description => 'Inches'
	  			},
	  			Length => '23',
	  			Width => '17',
	  			Height => '45'
	  		},
	  		NumberOfPieces => '45',
	  		PackagingType => 
	  		{
	  			Code => 'BAG',
	  			Description => 'BAG'
	  		},
	  		DangerousGoodsIndicator => '',
	  		CommodityValue =>
	  		{
	  			CurrencyCode => 'USD',
	  			MonetaryValue => '5670'
	  		},
	  		FreightClass => '60',
	  		NMFCCommodityCode => ''
	  	},
	  	ShipmentServiceOptions =>
	  	{
	  		PickupOptions =>
	  		{
	  			HolidayPickupIndicator => '',
	  			InsidePickupIndicator => '',
	  			ResidentialPickupIndicator => '',
	  			WeekendPickupIndicator => '',
	  			LiftGateRequiredIndicator => ''
	  		},
	  		
	  		OverSeasLeg =>
	  		{
	  			Dimensions =>
	  			{
	  				Volume => '20',
	  				UnitOfMeasurement =>
	  				{
	  					Code => 'CF',
	  					Description => 'String'
	  				}
	  			},
	  			Value => 
  				{
  					Cube =>
  					{
  						CurrencyCode => 'USD',
  						MonetaryValue => '5670'
  					}
  				}
	  		},
	  		COD =>
	  		{
	  			CODValue =>
	  			{
	  				CurrencyCode => 'USD',
	  				MonetaryValue => '363'
	  			},
	  			CODPaymentMethod =>
	  			{
	  				Code => 'M',
	  				Description => 'For Company Check'
	  			},
	  			CODBillingOption =>
	  			{
	  				Code => '01',
	  				Description => 'Prepaid'
	  			},
	  			RemitTo =>
	  			{
	  				Name => 'RemitToSomebody',
	  				Address =>
	  				{
	  					AddressLine => '640 WINTERS AVE',
	  					City => 'PARAMUS',
	  					StateProvinceCode => 'NJ',
	  					PostalCode => '07652',
	  					CountryCode => 'US'
	  				},
	  				AttentionName => 'C J Parker'
	  			}
	  		},
	  		DangerousGoods =>
	  		{
	  			Name => 'Very Safe',
	  			Phone =>
	  			{
	  				Number => '453563321',
	  				Extension => '1111'
	  			},
	  			TransportationMode =>
	  			{
	  				Code => 'CARGO',
	  				Description => 'Cargo Mode'
	  			}
	  		},
	  		SortingAndSegregating =>
	  		{
	  			Quantity => '23452'
	  		},
	  		CustomsValue =>
	  		{
	  			CurrencyCode => 'USD',
	  			MonetaryValue => '23457923'
	  		},
	  		HandlingCharge =>
	  		{
	  			Amount =>
	  			{
	  				CurrencyCode => 'USD',
	  				MonetaryValue => '450'
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
 
 ($answer , $trace) = $call->(processFreightRate() , 'UTF-8');	
 
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
 