<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


    require "models/FBSalesOrder.php";
    
        $fbSalesOrder = new FBSalesOrder();
        $fbSalesOrder->getProducts('YAN-TSAPP-48');
        print_r($fbSalesOrder->result);