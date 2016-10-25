<?php
	set_time_limit('3600');
	session_start();

	// Setup autoload
	function __autoload($className) {
	    require_once DIR_OBJECTS . $className . '.php';
	}

    // Include classes
    require_once('classes/sdk.class.php');

    // Include functions
    require_once('functions/general.php');

    // IntApp Definitions
    define('APP_KEY', '1000');
    define('APP_NAME', 'AYC Fishbowl SDK');
    define('APP_DESCRIPTION', 'AYC Fishbowl');

    // Setup classes
    $fbsdk = new FishbowlSDK("192.168.0.5", "28192","achen","theone23");

    // Generate key, will need a new key for each time it's passed
    $fbsdk->Login();
?>