<?php
	require_once("applicationTop.php");
	
	// Check access rights
	if (!$fbapi->checkAccessRights("Sales Order", "View")) {
		$_SESSION['msg'] = 'You do not have access to use that function.';
		header("Location: /login.php");
	}

	// Get sales order list

	$fbapi->getSO("50611");
	
	// Check for error messages
	if ($fbapi->statusCode != 1000) {
		// Display error messages if it's not blank
		if (!empty($fbapi->statusMsg)) {
			echo $fbapi->statusMsg;
		}
	}

	print_r($fbapi->result);
?>