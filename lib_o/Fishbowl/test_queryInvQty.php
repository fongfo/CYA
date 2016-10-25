<?php
	require_once("applicationTop.php");
	
        $ini_array = parse_ini_file("fishbowlSolidcommerce.ini");
        print_r($ini_array);
exit();
	$dateStr = date("Ymd-His");
	$fileName = "inventory-".$dateStr.".txt";
	$outgoingDir = realpath(dirname(__FILE__))."\\..\\..\\solidcommerce\\outgoing\\";
//	echo "Realpath: ".$outgoingDir;


	$fbapi->Login("achen", "theone23");
	if (!$fbapi->checkAccessRights("Customer", "View")) {
		$_SESSION['msg'] = 'You do not have access to use that function.';
	}

	if ($fbapi->statusCode != 1000) {
		echo "Login Failed";
	} else {
		echo "Login Successful";
	}
	
	//$query = "Select * from qtyinventory";
	//$query = "Select PartID, QtyOnHand from qtyinventory";
	//$query = "SELECT i.PartID, p.Num, p.Description, i.QtyOnHand "
	$query = "SELECT p.Num, i.QtyOnHand "
		. " FROM qtyinventory i"
		. " JOIN Part p on i.PartID = p.Id";
		
	$fbapi->executeQuery("Query", $query);

	if ($fbapi->statusCode != 1000) {
		// Display error messages if it's not blank
		if (!empty($fbapi->statusMsg)) {
			echo $fbapi->statusMsg;
		}
	}
	echo "<pre>";
	$res = $fbapi->result;
	//print_r($res['FbiMsgsRs']['ExecuteQueryRs']);
	//print_r($res['FbiMsgsRs']['ExecuteQueryRs']['Rows']['Row']);
	echo "</pre>";
//echo "<pre>",var_dump($fbapi->result),"</pre>";

	$outHandle = fopen($outgoingDir.$fileName, 'w');
	// Go through results
	$rowArray = $res['FbiMsgsRs']['ExecuteQueryRs']['Rows']['Row'];
	foreach( $rowArray as $row ) {
		$cols = explode(",", $row);
		$rowStr = implode(" ", $cols);
		echo $rowStr;
		echo "<br/>";
		fwrite($outHandle, $row . "\n");
	}
	fclose($outHandle);
	echo "File to be created: ".$fileName."<br/>";
	
	// Connect to FTP Server
	
	// Upload file
?>
