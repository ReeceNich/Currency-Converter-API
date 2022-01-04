<?php
define('ERRORS', array(1000 => "Required parameter is missing",
                        1100 => "Parameter not recognised",
                        1200 => "Currency type not recognised",
                        1300 => "Currency amount must be a decimal number",
                        1400 => "Format must be xml or json",
                        1500 => "Error in service",
                        2000 => "Action not recognized or is missing",
                        2100 => "Currency code in wrong format or is missing",
                        2200 => "Currency code not found for update",
                        2300 => "No rate listed for this currency",
                        2400 => "Cannot update base currency",
                        2500 => "Error in service"));

function throwError($errorCode) {
    if(isset($_GET['format']) && strtolower($_GET['format']) == "json") {
        $error = array('code' => $errorCode, 'msg' => ERRORS[$errorCode]);
        $conv = array('error' => $error);
        $root = array('conv' => $conv);

        header('Content-type: application/json');
        echo json_encode($root, JSON_PRETTY_PRINT);
        exit();
    } else {
        // Create XML document
        $xml = new SimpleXMLElement('<conv/>');
        $error = $xml->addChild('error');
        $error->addChild('code', $errorCode);
        $error->addChild('msg', ERRORS[$errorCode]);

        header('Content-type: application/xml');
        echo $xml->saveXML();
        exit();
    }
}
?>