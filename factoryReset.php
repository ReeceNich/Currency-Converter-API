<?php

function factoryReset() {
    include_once("getLatestRates.php");
    include_once("isoToCurrency.php");
    include_once("throwError.php");

    try { 
        isoToCurrency();
        getLatestRates();
        // echo "<h1>Reset complete!</h1>";
    } catch(Exception $e){
        // echo "Error whilst resetting: " . $e;
        throwError(1500);
    }

}
?>