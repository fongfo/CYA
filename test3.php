<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:v1="http://www.ups.com/XMLSchema/XOLTWS/UPSS/v1.0" xmlns:v11="http://www.ups.com/XMLSchema/XOLTWS/Rate/v1.1" xmlns:v12="http://www.ups.com/XMLSchema/XOLTWS/Common/v1.0">
   <soapenv:Header>
      <v1:UPSSecurity>
         <v1:UsernameToken>
            <v1:Username>deanchou</v1:Username>
            <v1:Password>Anderson9729266488</v1:Password>
         </v1:UsernameToken>
         <v1:ServiceAccessToken>
            <v1:AccessLicenseNumber>5D16906D343745DE</v1:AccessLicenseNumber>
         </v1:ServiceAccessToken>
      </v1:UPSSecurity>
   </soapenv:Header>
   <soapenv:Body>
      <v11:RateRequest>
         <v12:Request>
            <!--Zero or more repetitions:-->
            <v12:RequestOption>Rate</v12:RequestOption>
            <!--Optional:-->

            <!--Optional:-->
            <v12:TransactionReference>
               <!--Optional:-->
               <v12:CustomerContext>TEST</v12:CustomerContext>
               <!--Optional:-->
               <v12:TransactionIdentifier></v12:TransactionIdentifier>
            </v12:TransactionReference>
         </v12:Request>
         <v11:Shipment>
            <v11:Shipper>
               <!--Optional:-->
               <v11:Name>AYC Group, LLC</v11:Name>
               <!--Optional:-->
               <v11:ShipperNumber>2Y263A</v11:ShipperNumber>
               <v11:Address>
                  <!--0 to 3 repetitions:-->
                  <v11:AddressLine>4009 Distribution Dr,STE# 225</v11:AddressLine>
                  <!--Optional:-->
                  <v11:City>Garland</v11:City>
                  <!--Optional:-->
                  <v11:StateProvinceCode>TX</v11:StateProvinceCode>
                  <!--Optional:-->
                  <v11:PostalCode>75041</v11:PostalCode>
                  <v11:CountryCode>US</v11:CountryCode>
               </v11:Address>
            </v11:Shipper>
            <v11:ShipTo>
               <!--Optional:-->
               <v11:Name>Mary Ann Catena</v11:Name>
               <v11:Address>
                  <!--0 to 3 repetitions:-->
                  <v11:AddressLine>Mary Ann Catena</v11:AddressLine>
                  <!--Optional:-->
                  <v11:City>Austin</v11:City>
                  <!--Optional:-->
                  <v11:StateProvinceCode>TX</v11:StateProvinceCode>
                  <!--Optional:-->
                  <v11:PostalCode>78745</v11:PostalCode>
                  <v11:CountryCode>US</v11:CountryCode>
                  <!--Optional:-->
                  
               </v11:Address>
            </v11:ShipTo>
            <v11:ShipFrom>
               <!--Optional:-->
               <v11:Name>AYC Group, LLC</v11:Name>
               <v11:Address>
                  <!--0 to 3 repetitions:-->
                  <v11:AddressLine>4009 Distribution Dr,STE# 225</v11:AddressLine>
                  <!--Optional:-->
                  <v11:City>Garland</v11:City>
                  <!--Optional:-->
                  <v11:StateProvinceCode>TX</v11:StateProvinceCode>
                  <!--Optional:-->
                  <v11:PostalCode>75041</v11:PostalCode>
                  <v11:CountryCode>US</v11:CountryCode>
               </v11:Address>
            </v11:ShipFrom>
            <v11:Service>
               <v11:Code>03</v11:Code>
               <!--Optional:-->
               <v11:Description></v11:Description>
            </v11:Service>

            <v11:Package>
               <!--Optional:-->
               <v11:PackagingType>
                  <v11:Code>02</v11:Code>
                  <!--Optional:-->
                  <v11:Description></v11:Description>
               </v11:PackagingType>
               <!--Optional:-->
               <v11:Dimensions>
                  <v11:UnitOfMeasurement>
                     <v11:Code>IN</v11:Code>
                     <!--Optional:-->
                     <v11:Description></v11:Description>
                  </v11:UnitOfMeasurement>
                  <!--Optional:-->
                  <v11:Length>10.5</v11:Length>
                  <!--Optional:-->
                  <v11:Width>8.5</v11:Width>
                  <!--Optional:-->
                  <v11:Height>3</v11:Height>
               </v11:Dimensions>
               <!--Optional:-->
               <v11:PackageWeight>
                  <v11:UnitOfMeasurement>
                     <v11:Code>LBS</v11:Code>
                     <!--Optional:-->
                     <v11:Description></v11:Description>
                  </v11:UnitOfMeasurement>
                  <v11:Weight>6</v11:Weight>
               </v11:PackageWeight>
               <!--Optional:-->


            </v11:Package>

            <v11:ShipmentRatingOptions>
               <v11:NegotiatedRatesIndicator/>
            </v11:ShipmentRatingOptions>
            <!--Optional:-->

         </v11:Shipment>
      </v11:RateRequest>
	
   </soapenv:Body>
</soapenv:Envelope>