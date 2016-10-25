<?php

    require_once('application_top.php');

    
    // Get Specific so
    $fbsdk->GetSO('50032');
    $soorder = $fbsdk->result['FbiMsgsRs']['LoadSORs']['SalesOrder'];
    //$fbsdk->Key($soorder['FBIXML']['TICKET'][0]['KEY'][0]['VALUE']);

    foreach($soorder as $key => $res)
    

    echo "The so stuff List is ->\n";
    
    
    print_r($res);

?>