<?php
	
    echo "TEST SCRIPT - Fishbowl Order<br/>";
    $ini_array = parse_ini_file("../fishbowlSolidcommerce.ini", true);

    require_once( realpath(dirname(__FILE__)).'/../../lib/Fishbowl/Order.php' );

    $orderObj = new Order();

    $dateStr = date("Ymd-His");
    $fileName = $ini_array['SC']['FILE_INVENTORY_PREFIX'] . $dateStr . ".txt";
    $outgoingDir = realpath(dirname(__FILE__))."\\".$ini_array['SC']['OUTGOING_DIR'];
    $outgoingFile = $outgoingDir . $fileName;

    $orders = $orderObj->GetOrders();
    
    $showHeaderOnce = true;
    foreach( $orders as $order ) {
        if( $showHeaderOnce ) {
            $showHeaderOnce = false;
            echo implode(",", array_keys($order)) . "</br>";
        }
        $orderStr = implode(",", $order);
        echo $orderStr . "</br>";
    }
    
?>
