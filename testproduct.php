<?php


header("content-type:text/html;charset=utf-8");
$wsdl = 'http://webservices.solidcommerce.com/ws.asmx?WSDL';

$client = new SoapClient($wsdl, array( 'soap_version'=>SOAP_1_2, 'appKey'=>'appKey', 'xslUri' => 'xslUri','productID'=>'productID', 'includeExtendedImages'=>'includeExtendedImages' ));


$ap_param = array(
                    'appKey' => '4*B{5CDd:TZT2Pb(',
                    'xslUri'=>'',
                    'productID'=>'I1-4MHJ-6MOJ',
                    'includeExtendedImages'=>'False'
                    ); 
try {
$result = $client ->__soapCall('GetProduct',array($ap_param));

//$b = get_object_vars($result);
$a=$result->GetProductResult->any;  
$b=simplexml_load_string($a);
//$a = $b['GetProductResult'];
print_r($b);



/*$xml = '';
foreach($result as $key => $value) {
                
                $xml = "<{$key}>";
                $xml .= $value;
                $xml .= "</{$key}>\n";
        }

echo($xml);*/
        
}catch (SOAPFault $e) {
    print $e;
}

?>