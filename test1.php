<?php
require_once('SCOrders.php');
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


        $OrderObjec = new order();
        $ini_array = parse_ini_file(SOLID_INI);
        $search_param = array(
                                    'appKey' => $this->ini_array['APPKEY'],
                                    'securityKey'=>$this->ini_array['SECURITYKEY'],
                                    'searchFilter'=>array(
                                        'page'=>'0',
                                        'recordsPerPageCount'=>'3',
                                        'FilterByDates'=>true,
                                        'FilterByOrderStatus'=>true,
                                        'OrderStatus'=>'PAID',
                                        'StartDate'=>date("Y-m-d H:i:s",strtotime("-1 day")),
                                        'EndDate'=>date("Y-m-d H:i:s"),
                                        )
                                    ); 


        $order = $this->OrderObjec->searchOrder($search_param);
        //$res=$order->GetOrderResult;
        $re = $this->OrderObjec->searchObjToXml($order);
        $oo = $this->OrderObjec->xmlToArr($re,false);
        //$ee=$OrderObjec['GetOrder'];
        //print_r($oo);
        //echo"<br/>-----------------<br/>";
        //echo count($oo ['SearchOrders']['Orders']['Order']);
        //echo"<br/>";
        //print_r($oo ['SearchOrders']['Orders']['Order']);
        $this->saleID = $oo['SearchOrders']['Orders']['Order'];
