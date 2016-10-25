<?php

    require '../vendor/autoload.php';

    define('SHOW_GROUPS', false );
    define('SHOW_GROUP', false );

    define('SHOW_PRODUCTS', true );
    
    use Bigcommerce\Api\Client as Bigcommerce;

    // ssc
    $settings = array( 
        'store_url' => 'https://www.shopsaloncity.com/',
        'username' => 'admin',
        'api_key' => '089add57335051f5adcf401584078272f65339fb'
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

		$showOnce = 0;
        $productFilter = array('limit' => $pageLimit);
		echo "Total # of products: ".$totalCount."\r\n";
		echo "<table>";
		echo "<tr>"
		."<th>ID</th><th>Name</th><th>Sku</th><th>Availability</th>"
		."<th>Inventory Level</th><th>Inventory Warning Level</th><th>Tracking</th>"
		."</tr>"; 
				
		while( $totalCount > ( $pageLimit * ($currPage-1) ) ) {
			$productFilter['page'] = $currPage++;
			$products = Bigcommerce::getProducts($productFilter);
			if (!$products) {
				$error = Bigcommerce::getLastError();
				echo $error->code;
				echo $error->message;
			} else {
				// Begin table
				foreach($products as $product) {

					echo "<tr><td>".$product->id
						."</td><td>".$product->name
						."</td><td>".$product->sku
						."</td><td>".$product->availability
						."</td><td>".$product->inventory_level
						."</td><td>".$product->inventory_warning_level
						."</td><td>".$product->inventory_tracking
						
						
						."</td></tr>";
				}
			}
		}    
        echo "</table>";
    } 
    catch( Exception $e) {
        echo "Caught Error: ".$e->getMessage();
        var_dump($e);
    }


?>
