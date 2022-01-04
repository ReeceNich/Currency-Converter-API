<?php

function paramChecks() {
    // Checks parameter values match the keys in $_GET
    if(count(array_intersect(PARAMS, array_keys($_GET))) < 4) {
        throwError(1000);
        exit();
    }

    // Checks no extra parameters are passed
    if(count($_GET) > 4) {
        throwError(1100);
        exit();
    }

    // TODO: *** ADD ERROR 1200!!! ***
    // Error 1200 is handled in the getRate function.

    // Checks currency amount is a decimal number in correct currency format
    if(!preg_match("/^\d+(\.\d{1,2})?$/", $_GET['amnt'])) {
        throwError(1300);
        exit();
    }

    if($_GET['format'] != "json" && $_GET['format'] != "xml") {
        throwError(1400);
        exit();
    }
}
?>