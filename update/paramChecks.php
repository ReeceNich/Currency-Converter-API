<?php
function paramChecks() {
    include_once("../config.php");
    include_once("../throwError.php");
    include_once("../getCurrency.php");
    
    // Load stored currencies data.
    libxml_use_internal_errors(true);
    $currenciesXML = simplexml_load_file("../" . CONFIG["currencies"]);
    if($currenciesXML == false) {
        throwError(2500);
        exit();
    }

    if(isset($_GET["action"])) {
        $_GET['action'] = strtolower($_GET['action']);
    }

    // Checks parameter values match the keys in $_GET
    if(!in_array($_GET["action"], array("put", "post", "del")) || !isset($_GET["action"]) || empty($_GET["action"])) {
        throwError(2000);
        exit();
    }

    // Check currency code is a valid format
    if(!isset($_GET["cur"]) || strlen($_GET["cur"]) != 3 || $_GET["cur"] != strtoupper($_GET["cur"])) {
        throwError(2100);
        exit();
    }

    // if cur not in XML rates file
    if(getCurrency($_GET["cur"], $currenciesXML) == false) {
        throwError(2200);
        exit();
    }

    // 2300: no rate listed for this currency
    // E.g., trying to update a currency not currently added.
    if($_GET["action"] == "put" && !isset(getCurrency($_GET["cur"], $currenciesXML)->rate)) {
        throwError(2300);
        exit();
    }

    // 2400: cannot update base currency
    if($_GET["cur"] == CONFIG["base"]) {
        throwError(2400);
        exit();
    }


}
?>