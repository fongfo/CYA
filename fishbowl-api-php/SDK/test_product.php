<?php

    require_once('application_top.php');

    // Get Product List
    $sdk->GetProducts();
    $products = GetXMLTree($sdk->Result());
    $sdk->Key($products['FBIXML']['TICKET'][0]['KEY'][0]['VALUE']);

    if ($products['FBIXML']['FBIMSGSRS'][0]['ATTRIBUTES']['STATUSCODE'] != 1000) {
        debug($products['FBIXML']['FBIMSGSRS'][0]['ATTRIBUTES']['STATUSCODE'], 2, $products);
    }

    // Get Specific Products
    $sdk->GetProducts('Query');
    $productQuery = GetXMLTree($sdk->Result());
    $sdk->Key($productQuery['FBIXML']['TICKET'][0]['KEY'][0]['VALUE']);

    if ($productQuery['FBIXML']['FBIMSGSRS'][0]['ATTRIBUTES']['STATUSCODE'] != 1000) {
        debug($productQuery['FBIXML']['FBIMSGSRS'][0]['ATTRIBUTES']['STATUSCODE'], 2, $productQuery);
    }

    echo "\n\n<br/><br/>\n\nThe Product List is -><br/>\n";
    print_r($products);

    echo "\n\n<br/><br/>\n\nThe Product Query is -><br/>\n";
    print_r($productQuery);

?>