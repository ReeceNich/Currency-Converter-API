<?php
// retrieve latest API data. done
// Open local XML file. done
// Find Currency in XML. done
// Update the value in the XML done
// Save as XML done
// Return all the info about the currency, including old+new rate

function getLatestOneRate($ccode, $action) {
    include_once("../config.php");
    include_once("../throwError.php");

    if($ccode == CONFIG['base']) {
        throwError(2400);
    }

    // define("LATESTAPI", "test_rates.json");
    define("INXML", "../" . CONFIG["currencies"]);
    define("OUTXML", "../" . CONFIG["currencies"]);

    $json = file_get_contents(CONFIG['api_url']);
    $latestData = json_decode($json);
    libxml_use_internal_errors(true);
    $latestXML = simplexml_load_file(INXML);
    if($latestXML == false) {
        throwError(2500);
        exit();
    }

    try {
        $latestCur = $latestData->data->$ccode;
        // print_r($latestCur);
    } catch(Exception $e) {
        throwError(2500);
    }

    // Find currency
    $root = $latestXML->xpath("/currencies");

    try {
        $cur = $latestXML->xpath("//*[ccode='$ccode']");
        if($cur == NULL) {
            // echo "nothing found in local currency store for $ccode";
            throwError(2200);
        } else {
            // If the currency is live, update the rate.
            if(isset($cur[0]->rate) && $action == "put") {
                $old_rate = $cur[0]->rate;
                $cur[0]->rate = $latestCur;
            } elseif(!isset($cur[0]->rate) && $action == "put") {
                // Trying to update a value of a not-added currency
                throwError(2500);
            } elseif(isset($cur[0]->rate) && $action == "post") {
                // Trying to add a new currency but it already exists
                throwError(2500);
            } elseif(!isset($cur[0]->rate) && $action == "post") {
                // Trying to add a new currency
                $cur[0]->rate = $latestCur;
            } else {
                // Unexplained error
                throwError(2500);
            }
        }
        $latestXML->saveXML(OUTXML);
        
    } catch(Exception $e) {
        // echo "Failed on: {$code} - $e";
        throwError(2500);
    }
    
    // print_r($cur);


    $response = new SimpleXMLElement('<action/>');
    $response->addAttribute("type", $action);
	$response->addChild('at', date("d M Y G:i", time()));
	$response->addChild('rate', strval($latestCur));
    if($action == "put") {
        $response->addChild('old_rate', strval($old_rate));
    }

	$responseCur = $response->addChild('curr');
	$responseCur->addChild("code", strval($cur[0]->ccode));
	$responseCur->addChild("name", strval($cur[0]->cname));
	$responseCur->addChild("loc", strval($cur[0]->cntry));
	header('Content-type: application/xml');
	echo $response->saveXML();


}

?>