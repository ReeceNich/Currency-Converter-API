<?php

function deleteRate($ccode) {
    include_once("../config.php");
    include_once("../throwError.php");

    if($ccode == CONFIG['base']) {
        throwError(2400);
    }

    define("INXML", "../" . CONFIG["currencies"]);
    define("OUTXML", "../" . CONFIG["currencies"]);

    libxml_use_internal_errors(true);
    $latestXML = simplexml_load_file(INXML);
    if($latestXML == false) {
        throwError(2500);
        exit();
    }

    // Find currency
    $root = $latestXML->xpath("/currencies");

    try {
        $cur = $latestXML->xpath("//*[ccode='$ccode']");
        if($cur == NULL) {
            // echo "nothing found in local currency store for $ccode";
            throwError(2200);
        } elseif(!isset($cur[0]->rate)) {
            throwError(2300);
        } else {
            // Delete the rate
            unset($cur[0]->rate);
        }

        $latestXML->saveXML(OUTXML);
        
    } catch(Exception $e) {
        // echo "Failed on: {$code} - $e";
        throwError(2500);
    }
    

    $response = new SimpleXMLElement('<action/>');
    $response->addAttribute("type", "del");
    $response->addChild('at', date("d M Y G:i", time()));
    $response->addChild('code', strval($ccode));

    header('Content-type: application/xml');
    echo $response->saveXML();


}


?>