<?php
    require_once('application_top.php');

    echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">' . "\n";

    // Get Customer Name List
    $fbsdk->getCustomer();
	$names = $fbsdk->result['FbiMsgsRs']['CustomerNameListRs']['Customers']['Name'];
	echo "Customer List: <br/>";

	foreach ($names AS $key=>$value) {
    	echo "{$value}<br/>";
	}
	
    if ($fbsdk->statusCode != 1000) {
        debug($fbsdk->statusMsg, 2);
    }
?>