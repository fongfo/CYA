<?php
	
    echo "EXPORT SCRIPT - Fishbowl Inventory to SolidCommerce FTP <br/>";
    $ini_array = parse_ini_file("/../../scripts/fishbowlSolidcommerce.ini", true);

    require_once( realpath(dirname(__FILE__)).'/test_product.php' );

    $productObj = new test_Product();

    $dateStr = date("Ymd-His");
    $fileName = $ini_array['SC']['FILE_PRODUCT_PREFIX'] . $dateStr . ".txt";
    $outgoingDir = realpath(dirname(__FILE__))."\\";
    $outgoingFile = $outgoingDir . $fileName;

    $products = $productObj->GetProducts();
    
    $outHandle = fopen($outgoingFile, 'w');
    // Go through results
    $showHeaderOnce = true;
    foreach( $products as $product ) {
        if( $showHeaderOnce ) {
            $showHeaderOnce = false;
            fwrite($outHandle, implode(",", array_keys($product)) . "\n");
        }
        $productStr = implode(",", $product);
        fwrite($outHandle, $productStr . "\n");
    }
    fclose($outHandle);
    echo "\t...File to be exported: ".$outgoingFile."<br/>";
    
?>