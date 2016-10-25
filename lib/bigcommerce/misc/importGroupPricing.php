<?php

    include '/opt/PHPExcel_1.7.8/Classes/PHPExcel.php'; 
    require 'vendor/autoload.php';
    use Bigcommerce\Api\Client as Bigcommerce;

    $settings = array(
        'store_url' => 'https://store-1tqeqc.mybigcommerce.com/',
        'username' => 'admin',
        'api_key' => '3862350575b7f18792d2d14e605aef51'
    );

    Bigcommerce::configure($settings);
    Bigcommerce::setCipher('RC4-SHA');
    Bigcommerce::failOnError(true);

    // Variables
    $importFile = '/home/cAdmin/group_discount_rules.xlsx';
    $groupColumnMapping = array();
    $groupDiscountRules = array();
    $customerGroups = array();
    $pageLimit = 100;
    $currPage = 1;

    $groups = Bigcommerce::getCustomerGroups();
    foreach($groups as $grp)
    {
        $customerGroups[$grp->name] = $grp->id;
    }
    // Extract group prices from file: 
    $objPHPExcel = PHPExcel_IOFactory::load($importFile);
    $objWorksheet = $objPHPExcel->getActiveSheet();
   
    $firstRow = $objWorksheet->getRowIterator()->current()->getCellIterator();
    $colNum = 0;
    foreach( $firstRow as $column ) {
        $colNum++;
        // First column is ProductID, ignore
        if( $colNum == 1 ) {
            continue;
        }
        $groupNameVal = $column->getValue();
        if( in_array( $groupNameVal, array_keys($customerGroups) ) ) {
            $groupDiscountRules[$customerGroups[$groupNameVal]] = array();
            $groupColumnMapping[$colNum] = $customerGroups[$groupNameVal];
        }
        
    }

var_dump($groupDiscountRules);
exit;
    foreach ($objWorksheet->getRowIterator() as $row) 
    {
        $cellIterator = $row->getCellIterator();
        $colNum=0;
        foreach ($cellIterator as $cell) 
        {
            $colNum++;
            if( $colNum == 1 ) {
            }
            print $cell->getValue() . "\t";   
        }
    }
    
    $objPHPExcel->disconnectWorksheets();
    unset($objPHPExcel);

    foreach( $product_prices as $_id => $_price ) {
        array_push( $discount_rules,
            (object) array(
                "type" => "product",
                "product_id" => (string) $_id,
                "method" => "fixed",
                "amount" => (double) $_price
            ) 
        );
    }
    
//            $updObj = array( 'id' => 1, 'discount_rules' => $discount_rules );
//            Bigcommerce::updateCustomerGroup( 1, $updObj );
//            var_dump( $discount_rules );
 

?>
