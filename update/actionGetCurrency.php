<?php
    include_once("../config.php");
    include_once("../throwError.php");
    define("INXML", "../" . CONFIG["currencies"]);
    libxml_use_internal_errors(true);
    
    $_GET['action'] = strtolower($_GET['action']);


    function getAllLiveCurrencies() {
        $latestXML = simplexml_load_file(INXML);
        if($latestXML == false) {
            throwError(2500);
            exit();
        }
        $root = $latestXML->xpath("/currencies");

        $ccodes = [];

        foreach($root[0] as $cur) {
            if(isset($cur->rate) && $cur->ccode != "GBP") {
                $ccodes[] = array("ccode"=>strval($cur->ccode), "cname"=>strval($cur->cname));
            }
        }
        
        // header('Content-Type: application/json');
        echo json_encode($ccodes);
    }


    function getAllDeadCurrencies() {
        $latestXML = simplexml_load_file(INXML);
        if($latestXML == false) {
            throwError(2500);
            exit();
        }
        $root = $latestXML->xpath("/currencies");

        $ccodes = [];

        // @ suppresses built in warnings so i can handle the error manually.
        $json = @file_get_contents(CONFIG['api_url']) or throwError(2500);
        $latestData = json_decode($json);
    
        
        foreach($root[0] as $cur) {
            // COMPARE CURRENCY API AGAINST THESE RATES TO SEE IF THEY EXIST.
            if(!isset($cur->rate) && $cur->ccode != "GBP" && array_key_exists(strval($cur->ccode), $latestData->data)) {
                $ccodes[] = array("ccode"=>strval($cur->ccode), "cname"=>strval($cur->cname));
            }
        }
        
        // header('Content-Type: application/json');
        echo json_encode($ccodes);
        
    }

    
    if($_GET['action'] == "put" || $_GET['action'] == "del") {
        // Get all the currencies with a 'rate' and return them
        getAllLiveCurrencies();
    }

    if($_GET['action'] == "post") {
        // Get all the currencies without a 'rate' AND which are in the currencyapi list
        getAllDeadCurrencies();
        
    }
?>