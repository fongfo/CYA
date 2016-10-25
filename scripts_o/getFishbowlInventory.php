<?php

	chdir(dirname(__FILE__));

	require_once('connection.ini');

	require_once("../lib/fishbowl-php-api/fbErrorCodes.class.php");
	require_once("../lib/fishbowl-php-api/fishbowlAPI.class.php");

	// Create Fishbowl Connection
	$fbapi = new FishbowlAPI(HOST, PORT);

	$fbapi->Login(USER, PASSWORD);


	

?>