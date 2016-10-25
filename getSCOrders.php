<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



require_once('SCOrders.php');

/*$order_param = array(
                            'appKey' => '4*B{5CDd:TZT2Pb(',
                            'securityKey'=>':=2|_U6T8;:h^2%n!+G.~_k+7y;:_|8a=++|D!_*6-^pY*S!af',
                            'xslUri'=>'',
                            'saleID'=> 	468056385 ,
                            'isLoadPayments'=>'True',
                            'isLoadWarehouseName'=>'True'
                            ); */


        $search_param = array(
                                    'appKey' => '4*B{5CDd:TZT2Pb(',
                                    'securityKey'=>':=2|_U6T8;:h^2%n!+G.~_k+7y;:_|8a=++|D!_*6-^pY*S!af',
                                    'searchFilter'=>array(
                                        'page'=>'0',
                                        'recordsPerPageCount'=>'3',
                                        'FilterByDates'=>true,
                                        'FilterByOrderStatus'=>true,
                                        'OrderStatus'=>'PAID',
                                        'StartDate'=>date("Y-m-d",strtotime("-1 day")),
                                        'EndDate'=>date("Y-m-d"),
                                        )
                                    ); 

$OrderObjec = new order();

$order = $OrderObjec->searchOrder($search_param);
//$res=$order->GetOrderResult;
$re = $OrderObjec->searchObjToXml($order);
$oo = $OrderObjec->xmlToArr($re,false);
$ORD=$oo['SearchOrders']['Orders']['Order'];

print_r($ORD);

