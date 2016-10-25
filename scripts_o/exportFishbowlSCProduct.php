<?php
	
    echo "EXPORT SCRIPT - Fishbowl Products to SolidCommerce FTP <br/>";
    $ini_array = parse_ini_file("fishbowlSolidcommerce.ini", true);
    require_once( realpath(dirname(__FILE__)).'/../lib/Fishbowl/Product.php' );

    $productObj = new Product();
    
    $dateStr = date("Ymd-His");
    $fileName = $ini_array['SC']['FILE_PRODUCT_PREFIX'] . $dateStr . ".txt";
    $outgoingDir = realpath(dirname(__FILE__))."\\".$ini_array['SC']['OUTGOING_DIR'];
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

    $connId = ftp_connect($ini_array['SC']['FTP_HOST']);

    $login_result = ftp_login($connId, $ini_array['SC']['FTP_USER'], $ini_array['SC']['FTP_PASSWORD']);
    ftp_chdir($connId, $ini_array['SC']['INCOMING_DIR']);
    $remoteFile = $ini_array['SC']['INCOMING_DIR'] . $fileName;
    // Connect to FTP Server
    echo "\t...Connected to FTP Server<br/>";

    // upload a file
    if (ftp_put($connId, $fileName, $outgoingFile, FTP_ASCII)) {
        echo "\t...Successfully uploaded $fileName<br/>";
    } else {
        echo "\t...There was a problem while uploading $fileName<br/>";
    }
    ftp_close($connId);   

    echo "END EXPORT SCRIPT<br/>";
	
?>
