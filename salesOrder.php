<?php
	require_once("applicationTop.php");
	
	// Check access rights
	if (!$fbapi->checkAccessRights("Sales Order", "View")) {
		$_SESSION['msg'] = 'You do not have access to use that function.';
		header("Location: /login.php");
	}

	// Get sales order list
	/*$fbapi->saveSO();
	
	// Check for error messages
	if ($fbapi->statusCode != 1000) {
		// Display error messages if it's not blank
		if (!empty($fbapi->statusMsg)) {
			echo $fbapi->statusMsg;
		}
	}

	print_r($fbapi->result);*/
	
	echo "\n\n<br/><br/>------------------------<br/><br/>\n\n";
	
	// Get specific sales order
	$fbapi->getSO("99999");
	
	// Check for error messages
	if ($fbapi->statusCode != 1000) {
		// Display error messages if it's not blank
		if (!empty($fbapi->statusMsg)) {
			echo $fbapi->statusMsg;
		}
	}
        $res=$fbapi->result;
        $rowArray = $res['FbiMsgsRs']['LoadSORs']['SalesOrder'];
        //$row=$fbapi->extractRowArray($rowArray);
        /*$fh = fopen('file.csv','w') or die("Can't open file.csv");
        foreach ($rowArray as $rowArray_line) {
            if (fputcsv($fh, $rowArray_line) === false) {
            die("Can't write CSV line");
          }
        }
        fclose($fh) or die("Can't close file.csv");*/
	print_r($rowArray);
        
        echo "\n\n<br/><br/>------------------------<br/><br/>\n\n";
        
        /*$fbapi->getPart("MIN-CUCHR-IS-BLK","740000000000");
                
                if ($fbapi->statusCode != 1000) {
		// Display error messages if it's not blank
		if (!empty($fbapi->statusMsg)) {
			echo $fbapi->statusMsg;
		}
	}

	print_r($fbapi->result);*/
?>