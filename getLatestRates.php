<?php

function getLatestRates() {
    // NOTE: THIS SEARCHES FOR CURRENCY CODE AND RETURNS WHOLE PARENT.
    // xpath("//*[ccode='']");
    include_once("config.php");

    // define("LATESTAPI", "test_rates.json");
    define("INXML", CONFIG["currencies"]);
    define("OUTXML", CONFIG["currencies"]);

    $json = file_get_contents(CONFIG['api_url']);
    $latestData = json_decode($json);
    libxml_use_internal_errors(true);
    $latestXML = simplexml_load_file(INXML);
    if($latestXML == false) {
        throwError(1500);
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
            if($cur == NULL) {
                // echo "nothing found in local currency store for $code";
            } else {
                // If the currency is live, update the rate.
                if(isset($cur[0]->rate)) {
                    $cur[0]->rate = $rate;
                }
            }
            
        } catch(Exception $e) {
            echo "Failed on: {$code} - $e";
        }
        
    }
    $latestXML->saveXML(OUTXML);
}
?>