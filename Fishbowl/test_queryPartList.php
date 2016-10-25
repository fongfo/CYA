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
	
	// the Details field has bad chars 
	$fbapi->executeQuery("Query", "Select id, num, description from part");

	if ($fbapi->statusCode != 1000) {
		// Display error messages if it's not blank
		if (!empty($fbapi->statusMsg)) {
			echo $fbapi->statusMsg;
		}
	}
	echo "<pre>";
	$res = $fbapi;
	print_r($res); //['FbiMsgsRs']['ExecuteQueryRs']);
	echo "</pre>";
//echo "<pre>",var_dump($fbapi->result),"</pre>";
?>
