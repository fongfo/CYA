<?php

    require_once('application_top.php');

    // Get Export List Types
    $fbsdk->SDKExportList();
    if ($fbsdk->statusCode != 1000) {
        debug($fbsdk->statusMsg, 2, $fbsdk->result);
    } else {
	    echo "\n\n<br/><br/>\n\nThe Export List Types -><br/>\n";
	    print_r($fbsdk->result);
    }

    // Get Export List
    $fbsdk->SDKExport('ExportPartProductAndVendorPricing');
    print_r($fbsdk);
    die();
    
    if ($fbsdk->statusCode != 1000) {
        debug($fbsdk->statusMsg, 2, $fbsdk->result);
    } else {
	    echo "\n\n<br/><br/>\n\nThe Export -><br/>\n";
	    print_r($fbsdk->result);
    }
/*
    // Get Customer List
    $sdk->GetCustomer('List');
    $customers = $xmlclass->parse($sdk->Result());
    $sdk->Key($customers['Ticket']['Key']);

    if ($customers['FbiMsgsRs-ATTR']['statusCode'] != 1000) {
        debug($customers['FbiMsgsRs-ATTR']['statusCode'], 2, $customers);
    }
*/

?>