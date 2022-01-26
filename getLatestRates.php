<?php

function getLatestRates() {
    // NOTE: THIS SEARCHES FOR CURRENCY CODE AND RETURNS WHOLE PARENT.
    // xpath("//*[ccode='']");
    include_once("config.php");
    include_once("throwError.php");

    // define("LATESTAPI", "test_rates.json");
    define("INXML", CONFIG["currencies"]);
    define("OUTXML", CONFIG["currencies"]);

    // @ suppresses built in warnings so i can handle the error manually.
    $json = @file_get_contents(CONFIG['api_url']) or throwError(1500);
    $latestData = json_decode($json);
    libxml_use_internal_errors(true);
    $latestXML = simplexml_load_file(INXML);
    if($latestXML == false) {
        throwError(1500);
        // Do any logging here if needed.
        exit();
    }


    // $cur = $latestXML->xpath("//*[ccode='USD']");
    // print_r($cur);
    // $cur[0]->addChild("rate", "4200000");
    $root = $latestXML->xpath("/currencies");
    $root[0]->attributes()->base = CONFIG["base"];
    // Updates the timestamp in locally stored rates to match the latest downloaded rates timestamp
    $root[0]->attributes()->ts = $latestData->query->timestamp;


    foreach($latestData->data as $code=>$rate) {
        // echo $code . $rate . "<br/>";
        // print_r($latestXML->xpath("//*[ccode='$code']"));
        try {
            $cur = $latestXML->xpath("//*[ccode='$code']");
            if($cur != NULL && isset($cur[0]->rate)) {
                // If the currency is live, update the rate.
                $cur[0]->rate = $rate;
            }
        } catch(Exception $e) {
            throwError(1500);
            // echo "Failed on: {$code} - $e";
        }
        
    }
    $latestXML->saveXML(OUTXML);
}
?>