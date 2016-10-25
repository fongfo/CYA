<?php

    require 'vendor/autoload.php';

    define('SHOW_GROUPS', true );
    define('SHOW_GROUP', false );

    define('SHOW_PRODUCTS', false );
    
    use Bigcommerce\Api\Client as Bigcommerce;

	
    $settings = array( 
        'store_url' => 'https://store-1tqeqc.mybigcommerce.com/',
        'username' => 'admin',
        'api_key' => '3862350575b7f18792d2d14e605aef51'
    );

    $pageLimit = 100;
    $currPage = 1;
    
    try 
    {
        Bigcommerce::configure($settings);
        Bigcommerce::verifyPeer(false);
        Bigcommerce::setCipher('RC4-SHA');
        Bigcommerce::failOnError(true);

        $totalCount = Bigcommerce::getProductsCount();


        if( SHOW_GROUPS ) {
            $groups = Bigcommerce::getCustomerGroups();
            foreach($groups as $grp) 
            {
                echo $grp->id
                .",".$grp->name
                ."\r\n";
            }
        }
    
        if( SHOW_GROUP ) {
            $product_prices = array();

            $groupid=3;
            $group = Bigcommerce::getCustomerGroup($groupid);
            var_dump($group);
            
            $row=1;
            if (($handle = fopen("/home/cAdmin/group_c_discount_rules.csv", "r")) !== FALSE) {
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    $num = count($data);
                    //echo "<p> $num fields in line $row: <br /></p>\n";
                    //$row++;
                    $product_prices[$data[0]] = $data[1];
                    //for ($c=0; $c < $num; $c++) {
                        
                    //    echo $data[$c] . ",";
                    //}
                }
                fclose($handle);
            }
            $discount_rules = array();
echo "Read csv file into price array\r\n";            
            //    '376' => '15.00',
            //    '377' => '10.00',
            //    '378' => '12.00',
            //    '379' => '32.00'
            //); 
            foreach( $product_prices as $_id => $_price ) {
                array_push( $discount_rules,
                    (object) array( 
                        "type" => "product",
                        "product_id" => (string) $_id,
                        "method" => "fixed",
                        "amount" => (double) $_price
                ) );
            }
//var_dump($discount_rules);
//exit;
echo "Updating rules for $groupid";           
            $updObj = array( 'id' => $groupid, 'discount_rules' => $discount_rules );
            Bigcommerce::updateCustomerGroup( $groupid, $updObj );  
            var_dump( $discount_rules );           
        }

        if( SHOW_PRODUCTS ) {
            $showOnce = 0;
        $productFilter = array('limit' => $pageLimit);
//    echo "Total # of products: ".$totalCount."\r\n";
            while( $totalCount > ( $pageLimit * ($currPage-1) ) ) {
                $productFilter['page'] = $currPage++;
                $products = Bigcommerce::getProducts($productFilter);
                if (!$products) {
                    $error = Bigcommerce::getLastError();
                    echo $error->code;
                    echo $error->message;
                } else {
                    foreach($products as $product) {
if( !$showOnce ) {
//var_dump($product);
$showOnce = 1;
}
                        echo $product->id
                            .",".$product->name
                            .",",$product->sku
                            .",",$product->availability
                            ."\r\n";
                        //echo $product->name.", ".
                        //echo $product->sku;
                        //.", ".$product->price;
                        //echo "\r\n";
                    }
                }
            }    
        }
    } 
    catch( Exception $e) {
        echo "Caught Error: ".$e->getMessage();
        var_dump($e);
    }


?>
