<?php

require("importOrder.php");

// Get sales order list
        $fbapi = new importOrder();
	$fbapi->saveSO();   
	
	// Check for error messages
	if ($fbapi->fbApi->statusCode != 1000) {
		// Display error messages if it's not blank
		if (!empty($fbapi->fbApi->statusMsg)) {
			echo $fbapi->fbApi->statusMsg;
		}
	}

	print_r($fbapi->fbApi->result);

