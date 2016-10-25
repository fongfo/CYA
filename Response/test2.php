<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

        

header('Content-Type: text/xml; charset=utf-8');


    $client = new SoapClient('http://webservices.solidcommerce.com/ws.asmx?WSDL');

    print($client->__getFunctions());
