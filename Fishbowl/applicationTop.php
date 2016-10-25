<?php
	ob_start();
	set_time_limit('3600');
	session_start();

	// Fishbowl App Info
	define('APP_KEY', '1000');
	define('APP_NAME', 'AYC Fishbowl SDK');
	define('APP_DESCRIPTION', 'AYC Fishbowl');

	require_once("fbErrorCodes.class.php");
	require_once("importOrder.php");
	
	// Create Fishbowl Connection
	$fbapi = new FishbowlAPI("192.168.0.6", "28192");
	
	if (isset($_SESSION['username'])) {
		$fbapi->Login($_SESSION['username'], $_SESSION['password']);
	}
?>
