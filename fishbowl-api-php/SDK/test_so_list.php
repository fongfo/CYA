<?php

    require_once('application_top.php');

	
    // Get SO List
    $sdk->GetSOList('MAIN');
    $solist = GetXMLTree($sdk->Result());
    $sdk->Key($solist['FBIXML']['TICKET'][0]['KEY'][0]['VALUE']);

    if ($solist['FBIXML']['FBIMSGSRS'][0]['ATTRIBUTES']['STATUSCODE'] != 1000) {
      debug($solist['FBIXML']['FBIMSGSRS'][0]['ATTRIBUTES']['STATUSCODE'], 2, $solist);
   }
echo "It did this much";
		
    echo "The SO List is ->\n";
	
    print_r($solist);

?>