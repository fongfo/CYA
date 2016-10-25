<env:Envelope xmlns:env="http://schemas.xmlsoap.org/soap/envelope/"
xmlns:xsd="http://www.w3.org/2001/XMLSchema"
xmlns:upss="http://www.ups.com/XMLSchema/XOLTWS/UPSS/v1.0"
xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
xmlns:common="http://www.ups.com/XMLSchema/XOLTWS/Common/v1.0">
<env:Header>
<upss:UPSSecurity>
<upss:UsernameToken>
<upss:Username>deanchou</upss:Username>
<upss:Password>Anderson9729266488</upss:Password>
</upss:UsernameToken>
<upss:ServiceAccessToken>
<upss:AccessLicenseNumber>5D16906D343745DE</upss:AccessLicenseNumber>
</upss:ServiceAccessToken>
</upss:UPSSecurity>
</env:Header>
<env:Body>
<rate:RateRequest xmlns:rate="http://www.ups.com/XMLSchema/XOLTWS/Rate/v1.1"
xmlns:common="http://www.ups.com/XMLSchema/XOLTWS/Common/v1.0"
xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
<common:Request>
<common:RequestOption>Rate</common:RequestOption>
<common:TransactionReference>
<common:CustomerContext>Your Customer Context</common:CustomerContext>
</common:TransactionReference>
</common:Request>
<rate:Shipment>
<rate:Shipper>
<rate:Name>AYC Group, LLC</rate:Name>
<rate:ShipperNumber>2Y263A</rate:ShipperNumber>
<rate:Address>
<rate:AddressLine>4009 Distribution Dr</rate:AddressLine>
<rate:AddressLine>STE# 225</rate:AddressLine>

<rate:City>Garland</rate:City>
<rate:StateProvinceCode>TX</rate:StateProvinceCode>
<rate:PostalCode>75041</rate:PostalCode>
<rate:CountryCode>US</rate:CountryCode>
</rate:Address>
</rate:Shipper>
<rate:ShipTo>
<rate:Name>Mary Ann Catena</rate:Name>
<rate:Address>
<rate:AddressLine>Mary Ann Catena</rate:AddressLine>

<rate:City>Austin</rate:City>
<rate:StateProvinceCode>TX</rate:StateProvinceCode>
<rate:PostalCode>78745</rate:PostalCode>
<rate:CountryCode>US</rate:CountryCode>
</rate:Address>
</rate:ShipTo>
<rate:ShipFrom>
<rate:Name>AYC Group, LLC</rate:Name>
<rate:Address>
<rate:AddressLine>4009 Distribution Dr</rate:AddressLine>
<rate:AddressLine>STE# 225</rate:AddressLine>

<rate:City>Garland</rate:City>
<rate:StateProvinceCode>TX</rate:StateProvinceCode>
<rate:PostalCode>75041</rate:PostalCode>
<rate:CountryCode>US</rate:CountryCode>
</rate:Address>
</rate:ShipFrom>
<rate:Service>
<rate:Code>03</rate:Code>
<rate:Description>Service Code Description</rate:Description>
</rate:Service>
<rate:Package>
<rate:PackagingType>
<rate:Code>02</rate:Code>
<rate:Description>Rate</rate:Description>
</rate:PackagingType>
<rate:Dimensions>
<rate:UnitOfMeasurement>
<rate:Code>IN</rate:Code>
<rate:Description>inches</rate:Description>
</rate:UnitOfMeasurement>
<rate:Length>11</rate:Length>
<rate:Width>8</rate:Width>
<rate:Height>3</rate:Height>
</rate:Dimensions>
<rate:PackageWeight>
<rate:UnitOfMeasurement>
<rate:Code>Lbs</rate:Code>
<rate:Description>pounds</rate:Description>
</rate:UnitOfMeasurement>
<rate:Weight>6</rate:Weight>
</rate:PackageWeight>
</rate:Package>
<rate:ShipmentRatingOptions>
<rate:NegotiatedRatesIndicator/>
</rate:ShipmentRatingOptions>
</rate:Shipment>
</rate:RateRequest>