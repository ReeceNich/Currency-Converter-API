<?php
include_once("paramChecks.php");
define('PARAMS', array('cur', 'action'));

// Run parameter checks
paramChecks();
// 



if($_GET['action'] == "put" || $_GET['action'] == "post") {
    include_once("getLatestOneRate.php");
    getLatestOneRate($_GET['cur'], $_GET['action']);
    exit();
}

if($_GET['action'] == "del") {
    include_once("deleteRate.php");
    deleteRate($_GET['cur']);
    exit();
}



?>