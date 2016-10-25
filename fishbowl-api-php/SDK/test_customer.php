<?php
    require_once('application_top.php');

    // Get Customer Name List
    $fbsdk->getCustomer();
    if ($fbsdk->statusCode != 1000) {
        debug($fbsdk->statusMsg, 2, $fbsdk->result);
    } else {
	    echo "\n\n<br/><br/>\n\n The Customer Name List is -><br/>\n";
	    print_r($fbsdk->result);
    }

    // Get Specific Customer
    $fbsdk->getCustomer('Get', 'Bethany Bolk');
    if ($fbsdk->statusCode != 1000) {
        debug($fbsdk->statusMsg, 2, $fbsdk->result);
    } else {
	    echo "\n\n<br/><br/>\n\n The Customer Info is -><br/>\n";
	    print_r($fbsdk->result);
    }
/*
    // Get Customer List
    $fbsdk->getCustomer('List');
    if ($fbsdk->result['FbiMsgsRs']['@attributes']['statusCode'] != 1000) {
        debug($fbsdk->result['FbiMsgsRs']['@attributes']['statusCode'], 2, $fbsdk->result);
    } else {
    	echo "\n\n<br/><br/>\n\n The Customer List is -><br/>\n";
    	print_r($fbsdk->result);
    }
*/
?>