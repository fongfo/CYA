<?php

    require_once('application_top.php');

    // Get Export List
    $sdk->SDKExportList();
    $sdkexportlist = GetXMLTree($sdk->Result());
    $sdk->Key($sdkexport['FBIXML']['TICKET'][0]['KEY'][0]['VALUE']);
	
	$list2=$sdkexportlist['FBIXML']['FBIMSGSRS'][0]['EXPORTLISTRS'][0]['EXPORTS'][0]['EXPORTNAME'];
	$total=count($list2);
		
	echo "ExportList: <br/>";

	for($i=0; $i < $total; $i++){
    	$list=$sdkexportlist['FBIXML']['FBIMSGSRS'][0]['EXPORTLISTRS'][0]['EXPORTS'][0]['EXPORTNAME'][$i]['VALUE'];
    	print_r($list);
		
    	echo "<br/>";
	}
	if ($sdkexportlist['FBIXML']['FBIMSGSRS'][0]['ATTRIBUTES']['STATUSCODE'] != 1000) {
        debug($sdkexport['FBIXML']['FBIMSGSRS'][0]['ATTRIBUTES']['STATUSCODE'], 2, $sdkexportlist);
    }
    

    echo "\n\n<br/><br/>\n\n You can export the following lists -><br/>\n";
    print_r($sdkexportlist);
?>