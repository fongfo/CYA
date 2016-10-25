<?php

        require_once("importOrder.php");

// Get sales order list
        
        $fbapi = new importOrder();
	
        
	$fbapi->saveSO();
	
	// Check for error messages
	if ($fbapi->statusCode != 1000) {
		// Display error messages if it's not blank
		if (!empty($fbapi->statusMsg)) {
			echo $fbapi->statusMsg;
		}
	}

	print_r($fbapi->fbApi->result);

