<?php

    require_once('application_top.php');

    // Get Vendor Name List
    $sdk->GetVendor();
	$vendornames = GetXMLTree($sdk->Result());
    $sdk->Key($vendornames['FBIXML']['TICKET'][0]['KEY'][0]['VALUE']);

    if ($vendornames['FBIXML']['FBIMSGSRS'][0]['ATTRIBUTES']['STATUSCODE'] != 1000) {
        debug($vendornames['FBIXML']['FBIMSGSRS'][0]['ATTRIBUTES']['STATUSCODE'], 2, $customernames);
    }

    // Get Specific Vendor
    $sdk->GetVendor('Get', 'Johnson Manufacturing');
    $vendor = GetXMLTree($sdk->Result());
    $sdk->Key($vendor['FBIXML']['TICKET'][0]['KEY'][0]['VALUE']);

    if ($vendor['FBIXML']['FBIMSGSRS'][0]['ATTRIBUTES']['STATUSCODE'] != 1000) {
        debug($vendor['FBIXML']['FBIMSGSRS'][0]['ATTRIBUTES']['STATUSCODE'], 2, $customer);
    }

    echo "\n\n<br/><br/>\n\nThe Vendor Name List is -><br/>\n";
    print_r($vendornames);

    echo "\n\n<br/><br/>\n\nThe Vendor Info is -><br/>\n";
    print_r($vendor);

    echo "\n\n<br/><br/>\n\nThe Vendor List is -><br/>\n";
    print_r($vendors);

?>