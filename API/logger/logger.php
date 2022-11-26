<?php

function logIncomingRequest($requestData, $filePath) {
    $logFile = fopen($filePath, "a") or die();
    fwrite($logFile, "-------------------------------------------------\n");
    fwrite($logFile, $requestData);
    fclose($logFile);
}

?>