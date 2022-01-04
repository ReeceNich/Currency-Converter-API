<?php
function getCurrency($curCode, $xml) {
    include_once("throwError.php");
    
	// Get to and from rates from the currencies file.
	try {
		$cur = $xml->xpath("//*[ccode='$curCode']");
		if($cur == NULL) {
			// echo "nothing found in local currency store for $curCode";
			return false;
			// throwError(1200);
		} else {
			// If the currency is live, return the currency.
			return $cur[0];
		}
	} catch(Exception $e) {
		echo "Failed on: {$curCode} - $e";
	}
}
?>