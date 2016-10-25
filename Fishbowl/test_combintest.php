<?php
	
    echo "EXPORT SCRIPT - Fishbowl Inventory to SolidCommerce FTP <br/>";
    $ini_array = parse_ini_file("/../../scripts/fishbowlSolidcommerce.ini", true);

    require_once( realpath(dirname(__FILE__)).'/test_inventory.php' );

    $inventoryObj = new Inventory();

    $dateStr = date("Ymd-His");
    $fileName = $ini_array['SC']['FILE_INVENTORY_PREFIX'] . $dateStr . ".txt";
    $outgoingDir = realpath(dirname(__FILE__))."\\";
    $outgoingFile = $outgoingDir . $fileName;

    $inventory = $inventoryObj->GetInventory();
    
    $outHandle = fopen($outgoingFile, 'w');
    // Go through results
    $showHeaderOnce = true;
    foreach( $inventory as $_inv ) {
        if( $showHeaderOnce ) {
            $showHeaderOnce = false;
            fwrite($outHandle, implode(",", array_keys($_inv)) . "\n");
        }
        $_inv["AVAILABLE"] = intval($_inv["AVAILABLE"]);
        $invStr = implode(",", $_inv);
        fwrite($outHandle, $invStr . "\n");
    }
    fclose($outHandle);
    echo "\t...File to be exported: ".$outgoingFile."<br/>";
    
?>