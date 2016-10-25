<?php
	require_once("applicationTop.php");
	
	$fbapi->Login("achen", "theone23");
	if (!$fbapi->checkAccessRights("Customer", "View")) {
		$_SESSION['msg'] = 'You do not have access to use that function.';
	}

	if ($fbapi->statusCode != 1000) {
		echo "Login Failed";
	} else {
		echo "Login Successful";
	}
	
	
	//$fbapi->export("ExportProduct");
	$fbapi->executeQuery("Query", "Select * from product");
	//$this->fbErrorCodes
	//echo "<pre>",var_dump($fbapi),"</pre>";
		// Check for error messages
	if ($fbapi->statusCode != 1000) {
		// Display error messages if it's not blank
		if (!empty($fbapi->statusMsg)) {
			echo $fbapi->statusMsg;
		}
	}
	echo "<pre>";
	print_r($fbapi);
	echo "</pre>";
//echo "<pre>",var_dump($fbapi->result),"</pre>";
?>
