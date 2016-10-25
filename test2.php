<?php
require_once('SCOrders.php');
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//$StartDate=date("Y-m-dTH:i:s",strtotime("-10 hour"));


                                        $EndDate=date("Y-m-d H:i:s");
                                        
                                        echo $EndDate;
                                                                               
                                        $time = strtotime("-14 hour");
                                        echo date("Y-m-d",$time)."T".date("H:i:s",$time);
                                        
                                        
                                        
                                                $OrderObjec = new order();
        $ini_array = parse_ini_file(SOLID_INI);
        
        $sTime = strtotime("-12 hour");

        $search_param = array(
                                    'appKey' => $ini_array['APPKEY'],
                                    'securityKey'=>$ini_array['SECURITYKEY'],
                                    'searchFilter'=>array(
                                        'page'=>'0',
                                        'recordsPerPageCount'=>'100',
                                        'FilterByDates'=>true,
                                        'FilterByOrderStatus'=>true,
                                        'OrderStatus'=>'Imported',
                                        'isCustomStatus'=>'1',
                                        'StartDate'=>date("Y-m-d",$sTime)."T".date("H:i:s",$sTime),
                                        'EndDate'=>date("Y-m-d")."T".date("H:i:s"),
                                        )
                                    ); 


        $order = $OrderObjec->searchOrder($search_param);
        //$res=$order->GetOrderResult;
        $re = $OrderObjec->searchObjToXml($order);
        $oo = $OrderObjec->xmlToArr($re,false);
        //$ee=$OrderObjec['GetOrder'];
        //print_r($oo);
        //echo"<br/>-----------------<br/>";
        //echo count($oo ['SearchOrders']['Orders']['Order']);
        //echo"<br/>";
        //print_r($oo ['SearchOrders']['Orders']['Order']);
        $saleID = $oo['SearchOrders']['Orders']['Order'];
        print_r($saleID);
                                        
?>
