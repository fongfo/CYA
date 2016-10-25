<?php

    require 'vendor/autoload.php';

    use Bigcommerce\Api\Client as Bigcommerce;

    $settings = array(
        'store_url' => 'https://store-1tqeqc.mybigcommerce.com/',
        'username' => 'admin',
        'api_key' => '3862350575b7f18792d2d14e605aef51'
    );

    $importFile = "/home/cAdmin/product_prices.csv";

    $product_prices = array();

    try
    {
        Bigcommerce::configure($settings);
        Bigcommerce::setCipher('RC4-SHA');
        Bigcommerce::failOnError(true);

        $totalCount = Bigcommerce::getProductsCount();

        $row=1;
        if (($handle = fopen($importFile, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $num = count($data);
                $product_prices[$data[0]] = $data[1];
            }
            fclose($handle);
        }

        // Update product prices
        foreach( $product_prices as $_productId => $_price ) {
            Bigcommerce::updateProduct($_productId, array('price' => $_price));
        }
    }
    catch( Exception $e) {
        echo "Caught Error: ".$e->getMessage();
        var_dump($e);
    }


?>
