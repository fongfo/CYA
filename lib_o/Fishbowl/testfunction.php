<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

    require_once("fbErrorCodes.class.php");
    require_once("fishbowlAPI.class.php");
    define('FISHBOWL_INI', "fishbowl.ini");
    
    $ini_array;
    $fbApi;
    
    $ini_array = parse_ini_file(FISHBOWL_INI);
    define('APP_KEY', $ini_array['APP_KEY']);
    define('APP_NAME', $ini_array['APP_NAME']);
    define('APP_DESCRIPTION', $ini_array['APP_DESCRIPTION']);
    
    $fbApi = new FishbowlAPI($ini_array['HOST'], $ini_array['PORT']);
    
    $fbApi->Login($ini_array['USER'], $ini_array['PASSWORD']);
            if (!$fbApi->checkAccessRights("Customer", "View")) {
                throw new Exception('You do not have access to use that function.');
            }

            if ($fbApi->statusCode != 1000) {
                throw new Exception('Error connecting to Fishbowl!');
            }
            
   // $producthaha=$fbApi->getProducts($type = 'Get', $productNum = 'KAN-TCHRCVR-03-CFE', $getImage = 0, $upc = null);
    $LocationGroup = 'AYC';
    $producthaha=$fbApi->getSOList($LocationGroup);
    print_r($producthaha);