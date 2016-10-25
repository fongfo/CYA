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
                                    'appKey' => $ini_array['APPKEY'],
                                    'securityKey'=>$ini_array['SECURITYKEY'],
                                    'xslUri'=>'',
                                    'saleID'=>' 468058337 ',
                                    'status'=>'Imported',
                                    'isCustomStatus'=>'1',
                                    'updateNotes'=>'false',                           
                                        
                                    ); 


        $result = $OrderObjec->updateOrderStatus($search_param);
        print_r($result);
