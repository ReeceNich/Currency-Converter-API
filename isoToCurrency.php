<?php
// This script converts the list of ISO 4217 currencies to a smaller XML file
// of currencies to be used in my currency converter microservice.
// THIS ONLY NEEDS TO BE RUN ONCE IN THE SERVICE LIFE TIME.
// It combines all the countries that use the same currency into one.
// E.g., many countries use the Euro, so this stops euro being repeated.

function isoToCurrency() {
    include_once("config.php");
    
    define("SOURCEXML", CONFIG["source_currencies"]);
    define("OUTPUTXML", CONFIG["currencies"]);
    define("DEFAULTCURRENCIES", array("AUD", "BRL", "CAD", "CHF", "CNY", "DKK", "EUR", "GBP", "HKD", "HUF", "INR", "JPY", "MXN", "MYR", "NOK", "NZD", "PHP", "RUB", "SEK", "SGD", "THB", "TRY", "USD", "ZAR"));

    // Loads the XML into a variable.
    $source = simplexml_load_file(SOURCEXML);
    // Query the XML and find all elements with CcyNtry as the parent.
    $entries = $source->xpath("/ISO_4217/CcyTbl/CcyNtry");


    // HELP: https://stackoverflow.com/questions/143122/using-simplexml-to-create-an-xml-object-from-scratch
    // Create an XML file for the output.
    $output = new SimpleXMLElement("<currencies/>");
    $output->addAttribute("base", "GBP");
    $output->addAttribute("ts", time());


    // Some countries have the same currency code. This loop combines all countries with the same currencies into the same item.
    $allCurrenciesCombined = [];
    foreach($entries as $entry) {
        if(isset($allCurrenciesCombined[strval($entry->Ccy)])) {
            // Found a duplicate Ccode, append country to existing values
            // echo "<br/>Duplicate found: $entry->Ccy, $entry->CtryNm";
            $allCurrenciesCombined[strval($entry->Ccy)]["cntry"] .= ", " . strval($entry->CtryNm);
        } else {
            $allCurrenciesCombined[strval($entry->Ccy)] = ["ccode"=>strval($entry->Ccy), "cname"=>strval($entry->CcyNm), "cntry"=>strval($entry->CtryNm)];
        }

    }

    // print_r($allCurrenciesCombined['USD']);

    // For each currency entry, convert the values into XML.
    foreach($allCurrenciesCombined as $entry) {

        $outputCurrency = $output->addChild("currency");
        $outputCurrency->addChild("ccode", $entry["ccode"]);
        $outputCurrency->addChild("cname", $entry["cname"]);
        $outputCurrency->addChild("cntry", $entry["cntry"]);

        if(in_array($entry["ccode"], DEFAULTCURRENCIES)) {
            $outputCurrency->addChild("rate", "");
        }

    }

    // Header('Content-type: text/xml');
    // print $output->asXML();
    $output->asXML(OUTPUTXML);
}
?>