<?php

    require 'vendor/autoload.php';

    use Bigcommerce\Api\Client as Bigcommerce;

    $settings = array(
        'store_url' => 'https://store-1tqeqc.mybigcommerce.com/',
        'username' => 'admin',
        'api_key' => '3862350575b7f18792d2d14e605aef51'
    );

    $importFile = "/home/cAdmin/group_d_discount_rules.csv";

    $groupid = 4;
    $product_prices = array();
    $discount_rules = array();
    $rules_count = 0;
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

        $rules_count = count( $product_prices );
        foreach( $product_prices as $_id => $_price ) {
            array_push( $discount_rules,
                (object) array(
                    "type" => "product",
                    "product_id" => (string) $_id,
                    "method" => "fixed",
                    "amount" => (double) $_price
            ) );
        }

        $updObj = array( 'id' => $groupid, 'discount_rules' => $discount_rules );
        Bigcommerce::updateCustomerGroup( $groupid, $updObj );

        echo "Group ".$groupid." updated with ".$rules_count." rules.\r\n";
    }
    catch( Exception $e) {
        echo "Caught Error: ".$e->getMessage();
        var_dump($e);
    }


?>
