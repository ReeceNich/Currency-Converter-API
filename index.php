<?php
@date_default_timezone_set("GMT"); 

include_once("config.php");
include_once("throwError.php");
include_once("paramChecks.php");
include_once("getCurrency.php");

define('PARAMS', array('to', 'from', 'amnt', 'format'));


// Set a default value for format if not specified.
if(!isset($_GET['format']) || empty($_GET['format'])) {
	$_GET['format'] = 'xml';
}

// Check all the parameters are valid, else throw an error.
paramChecks();
// echo "Valdation passed so far ....";

// Load the locally stored currencies
libxml_use_internal_errors(true);
$currenciesXML = simplexml_load_file(CONFIG["currencies"]);
if($currenciesXML == false) {
	throwError(1500);
	exit();
}
$currencies = $currenciesXML->xpath("/currencies");


// Update the locally stored rates if >2 hours old
if(time() - $currencies[0]->attributes()->ts >= 7200) {
	include_once("getLatestRates.php");
	getLatestRates();
	// reopen the file
	$currenciesXML = simplexml_load_file(CONFIG["currencies"]);
	if($currenciesXML == false) {
		throwError(1500);
		exit();
	}
	$currencies = $currenciesXML->xpath("/currencies");
	
} else {
	$lastUpdate = time() - $currencies[0]->attributes()->ts;
	// echo "<p>Time since last update: " . $lastUpdate . " seconds</p>";
}


$to = getCurrency($_GET['to'], $currenciesXML);
if($to != false && isset($to->rate)) {
	$toRate = $to->rate;
} else {
	throwError(1200);
}

$from = getCurrency($_GET['from'], $currenciesXML);
if($from != false && isset($from->rate)) {
	$fromRate = $from->rate;
} else {
	throwError(1200);
}

// Do conversion
$convAmount = (1/$fromRate) * $_GET['amnt'] * $toRate;
// Format conversion into currency format (e.g. with 2 decimal places).
$convAmount = number_format($convAmount, 2, ".", "");

if($_GET['format'] == "json") {
	$responseFrom = array("code" => strval($from->ccode),
						"curr" => strval($from->cname),
						"loc" => strval($from->cntry),
						"amnt" => $_GET['amnt']);

	$responseTo = array("code" => strval($to->ccode),
						"curr" => strval($to->cname),
						"loc" => strval($to->cntry),
						"amnt" => $convAmount);

	$responseConv = array("at" => date("d M Y G:i", strval($currencies[0]->attributes()->ts)),
						"rate" => strval($to->rate),
						"from" => $responseFrom,
						"to" => $responseTo);

	$response = array("conv" => $responseConv);

	$json = json_encode($response, JSON_PRETTY_PRINT);
	header('Content-type: application/json');
	echo $json;

} else {
	$response = new SimpleXMLElement('<conv/>');
	$repsonseAt = $response->addChild('at', date("d M Y G:i", strval($currencies[0]->attributes()->ts)));
	$responseRate = $response->addChild('rate', strval($to->rate));

	$responseFrom = $response->addChild('from');
	$responseFrom->addChild("code", strval($from->ccode));
	$responseFrom->addChild("curr", strval($from->cname));
	$responseFrom->addChild("loc", strval($from->cntry));
	$responseFrom->addChild("amnt", $_GET['amnt']);

	$responseTo = $response->addChild('to');
	$responseTo->addChild("code", strval($to->ccode));
	$responseTo->addChild("curr", strval($to->cname));
	$responseTo->addChild("loc", strval($to->cntry));
	$responseTo->addChild("amnt", $convAmount);

	header('Content-type: application/xml');
	echo $response->saveXML();
}

?>