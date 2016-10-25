<?php

    require dirname(__file__) . "/../classes/UPS/UPS.php";
    
    $upsObj = new UPS();
    
    $request = array(
        "context" => "This is a test of the Track API",
        "number" => "1Z2Y263A9099970604"
    );
    echo "<pre>";
    try {
        $upsObj->setTrackRequest($request);
        
        var_dump( $upsObj->getTrackRequest());
        $response = $upsObj->invokeTrackRequest($request);
        var_dump($response);
    } catch( Exception $e) {
        var_dump( $e );
    }

?>