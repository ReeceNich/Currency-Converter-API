<?php
include_once("getLatestRates.php");
include_once("isoToCurrency.php");

try { 
    isoToCurrency();
    getLatestRates();
    echo "<h1>Reset complete!</h1>";
} catch(Exception $e){
    echo "Error whilst resetting: " . $e;
}
?>