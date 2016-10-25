<?php

    require_once('application_top.php');

    // Get Tax Rate List
    $fbsdk->GetTaxRates();
    if ($fbsdk->statusCode != 1000) {
        debug($fbsdk->statusMsg, 2, $fbsdk->result);
    } else {
	    echo "\n\n<br/><br/>\n\nThe Tax List is -><br/>\n";
	    print_r($fbsdk->result);
    }

?>