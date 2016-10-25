<?php
require_once('SCOrders.php');
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class searchOrder{
    
public $OrderObjec;
public $res;
public $scOrder;
public $saleID;
public $ini_array;

    function __construct() {

        $this->OrderObjec = new order();
        $this->ini_array = parse_ini_file(SOLID_INI);
        
        $sTime = strtotime("-25 hour");

        $search_param = array(
                                    'appKey' => $this->ini_array['APPKEY'],
                                    'securityKey'=>$this->ini_array['SECURITYKEY'],
                                    'searchFilter'=>array(
                                        'page'=>'0',
                                        'recordsPerPageCount'=>'100',
                                        'FilterByDates'=>true,
                                        'FilterByOrderStatus'=>true,
                                        'OrderStatus'=>'PAID',
                                        'StartDate'=>date("Y-m-d",$sTime)."T".date("H:i:s",$sTime),
                                        'EndDate'=>date("Y-m-d")."T".date("H:i:s"),
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

        }

    public function getSCOrder($saleID){
   
            $order_param = array(
                                    'appKey' => $this->ini_array['APPKEY'],
                                    'securityKey'=>$this->ini_array['SECURITYKEY'],
                                    'xslUri'=>'',
                                    'saleID'=>$saleID,
                                    'isLoadPayments'=>'True',
                                    'isLoadWarehouseName'=>'True'
                                    ); 
            $SCOrder = $this->OrderObjec->getOrder($order_param);
            //$res=$SCOrder->GetOrderResult;
            $re = $this->OrderObjec->objToXml($SCOrder);
            $this->res=$this->OrderObjec->xmlToArr($re,false);
            $this->scOrder=$this->res['GetOrder']['Order'];
            return ($this->scOrder);
        //print_r($res);

        }
        
        
    public function updateSCOder($saleID){
        
            $search_param = array(
                                    'appKey' => $this->ini_array['APPKEY'],
                                    'securityKey'=>$this->ini_array['SECURITYKEY'],
                                    'xslUri'=>'',
                                    'saleID'=>$saleID,
                                    'status'=>'Imported',
                                    'isCustomStatus'=>'1',
                                    'updateNotes'=>'false',                           
                                        
                                    );     
            $result = $this->OrderObjec->updateOrderStatus($search_param);
            print_r($result);

    }
    }

