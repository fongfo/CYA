<?php

require_once('TEST5.php');

$order_param = array(
                            'appKey' => '4*B{5CDd:TZT2Pb(',
                            'securityKey'=>':=2|_U6T8;:h^2%n!+G.~_k+7y;:_|8a=++|D!_*6-^pY*S!af',
                            'xslUri'=>'',
                            'saleID'=>471243903  ,
                            'isLoadPayments'=>'True',
                            'isLoadWarehouseName'=>'True'
                            ); 

/*$xml = "<FbiXml>\n".
							"    <Ticket/>\n" .
							"    <FbiMsgsRq>\n" .
							"        <LoginRq>\n" .
							"            <IAID>" . APP_KEY . "</IAID>\n" .
							"            <IAName>" . APP_NAME . "</IAName>\n" .
							"            <IADescription>" . APP_DESCRIPTION . "</IADescription>\n" .
							"            <UserName>" . $this->user . "</UserName>\n" .
							"            <UserPassword>" . $this->pass . "</UserPassword>\n" .
							"        </LoginRq>\n" .
							"    </FbiMsgsRq>\n" .
							"</FbiXml>";*/

$OrderObjec = new order();

$order = $OrderObjec->getOrder($order_param);
$res=$order->GetOrderResult;
$re = $OrderObjec->objToXml($order);
$oo = $OrderObjec->xmlToArr($re,true);
//$ee=$OrderObjec['GetOrder'];
//header("Content-Type:text/xml");

print_r($oo);

//echo'---------/n';

//$OrderObjec->toValue($oo);

//$OrderObjec->xmlEncode($res);