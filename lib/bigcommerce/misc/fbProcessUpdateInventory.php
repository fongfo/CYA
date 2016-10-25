<?php

    require '../vendor/autoload.php';

    use Bigcommerce\Api\Client as Bigcommerce;
    // ssc
    $settings = array( 
        'store_url' => 'https://www.shopsaloncity.com/',
        'username' => 'admin',
        'api_key' => '089add57335051f5adcf401584078272f65339fb'
    );

	$pageLimit = 100;
    $currPage = 1;
	$fileMatch = "InvQty*.csv";
	$incomingDir = dirname(__FILE__).'\..\incoming';
    $productInventory = array();
    $productNoMatch = array();
    $array_index = 0;
    $array_data = 

	// Search for Incoming Inventory File
	chdir($incomingDir);
	foreach (glob($fileMatch) as $filename) 
	{
		if (($handle = fopen($filename, "r")) !== FALSE) 
		{
			// Header row
			$header = fgetcsv($handle, 1000, ",");
			// Save Product Sku and Qty into array
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
            {
                $num = count($data);
                $productInventory[$data[0]] = 
					array( "Sku" => $data[0], "Qty" => $data[3] );
            }
            fclose($handle);
            
            // Get All BigCommerce Products
            Bigcommerce::configure($settings);
			Bigcommerce::verifyPeer(false);
			Bigcommerce::setCipher('RC4-SHA');
			Bigcommerce::failOnError(true);

			$totalCount = Bigcommerce::getProductsCount();
            while( $totalCount > ( $pageLimit * ($currPage-1) ) ) 
            {
				$productFilter['page'] = $currPage++;
				$products = Bigcommerce::getProducts($productFilter);
				if (!$products) 
				{
					$error = Bigcommerce::getLastError();
					echo $error->code;
					echo $error->message;
				} 
				else 
				{
					// Find FB Inventory match and update inventory
					foreach( $products as $product ) 
					{
						if( array_key_exists($product->sku, $productInventory) ) 
						{
							// Update Product Inventory
							echo "<br>Updating ".$product->sku." from "
								.$product->inventory_level." to ".$productInventory[$product->sku]["Qty"];
							Bigcommerce::updateProduct($product->id, 
								array('inventory_level' => $productInventory[$product->sku]["Qty"]));
						}
						else 
						{
							$productNoMatch[] = $product;
						}
					}
				}
			}
				
			// Product Inventory Summary
			echo "<br><br>Total Number of BigCommerce Products :".	$totalCount;
			echo "<br>Products with no match: ".count($productNoMatch);
			echo "<br>------------------------------------------";
			foreach($productNoMatch as $product) {
				echo "<br>".$product->sku.", ".$product->name.", ".$product->inventory_level;
			}
			
			// TODO: Move file from incoming to processed
        }
	}

?>
