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
	$fbapi->getProducts('Get', '00-CON-NTBL-AMRST-BLK', 0, null) ;

	if ($fbapi->statusCode != 1000) {
		// Display error messages if it's not blank
		if (!empty($fbapi->statusMsg)) {
			echo $fbapi->statusMsg;
		}
	}
	echo "<pre>";
	print_r($fbapi->result['FbiMsgsRs']['ProductGetRs']['Product']['Weight']);
	echo "</pre>";
//echo "<pre>",var_dump($fbapi->result),"</pre>";
?>
