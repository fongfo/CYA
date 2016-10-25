<?php
    require_once('application_top.php');

    echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">' . "\n";

    // Get Specific Customer
    $fbsdk->getCustomer('Get', 'Bethany Bolk');
	echo "the customer junk:";
	print_r($fbsdk->result);
	
	debug($fbsdk->result['FbiMsgsRs']['CustomerGetRs']);

    if ($fbsdk->statusCode != 1000) {
        debug($fbsdk->statusMsg, 2);
    }
?>
