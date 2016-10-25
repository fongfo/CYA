<?php
	require_once('application_top.php');

	// Get part by Part Num
    echo "<h3>Get Part by Part Num</h3>";
	$fbsdk->getPart('B201', null);
    if ($fbsdk->statusCode != 1000) {
        debug($fbsdk->statusMsg, 2, $fbsdk->result);
    } else {
    	$part = $fbsdk->result['FbiMsgsRs']['PartGetRs']['Part'];
    	foreach($part AS $key=>$value) {
    		echo "<div style=\"width: 10em; font-weight: bold; float: left;\">{$key}</div>";
    		if (!is_array($value)) {
    			echo "<div style=\"float: left;\">{$value}</div>";
    		} else {
    			echo "<div style=\"float: left;\">";
    			if ($key == "UOM") {
    				echo $value['Name'];
    			} elseif ($key == "WeightUOM" || $key == "SizeUOM") {
    				echo $value['UOM']['Name'];
    			} elseif ($key == "VendorPartNums") {
    				if (count($value)) {
    					foreach ($value AS $junk=>$object) {
    						for ($i=0; $i < count($object); $i++) {
	    						$vpn = (array) $object[$i];
	    						echo "{$vpn['Number']}<br/>";
    						}
    					}
    				}
    			} else {
    				if (count($value)) {
    					print_r($value);
    				}
    			}
    			echo "</div>";
    		}
    		echo "<br style=\"clear: both;\" />";
    	}
    }

    // Get part by UPC
    echo "<h3>Get Part by UPC</h3>";
	$fbsdk->getPart(null, '12345');
    if ($fbsdk->statusCode != 1000) {
        debug($fbsdk->statusMsg, 2, $fbsdk->result);
    } else {
    	$part = $fbsdk->result['FbiMsgsRs']['PartGetRs']['Part'];
    	foreach($part AS $key=>$value) {
    		echo "<div style=\"width: 10em; font-weight: bold; float: left;\">{$key}</div>";
    		if (!is_array($value)) {
    			echo "<div style=\"float: left;\">{$value}</div>";
    		} else {
    			echo "<div style=\"float: left;\">";
    			if ($key == "UOM") {
    				echo $value['Name'];
    			} elseif ($key == "WeightUOM" || $key == "SizeUOM") {
    				echo $value['UOM']['Name'];
    			} elseif ($key == "VendorPartNums") {
    				if (count($value)) {
    					foreach ($value AS $junk=>$object) {
    						for ($i=0; $i < count($object); $i++) {
	    						$vpn = (array) $object[$i];
	    						echo "{$vpn['Number']}<br/>";
    						}
    					}
    				}
    			} else {
    				if (count($value)) {
    					print_r($value);
    				}
    			}
    			echo "</div>";
    		}
    		echo "<br style=\"clear: both;\" />";
    	}
    }
    
    // Get inventory quantity by part
    echo "<h3>Get Inventory Quantity by Part Number</h3>";
    $fbsdk->getInvQty('B201');
    print_r($fbsdk->result);
    if ($fbsdk->statusCode != 1000) {
        debug($fbsdk->statusMsg, 2, $fbsdk->result);
    } else {
    	$invQty = $fbsdk->result['FbiMsgsRs']['InvQtyRs']['InvQty'];
    }
    die();
?>