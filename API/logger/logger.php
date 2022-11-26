<?php

function logIncomingRequest($requestData) {
    $logFile = fopen("log_public.txt", "a") or die();
    fwrite($logFile, "-------------------------------------------------\n");
    fwrite($logFile, $requestData);
}

?>