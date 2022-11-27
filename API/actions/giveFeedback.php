<?php

/*
inputs: feedback [text], email [string]
-make email empty string if not defined
-add feedback and email into table
-status fail or success
-message "feedback submitted" or "database error"
*/
function giveFeedback($connection, $inputs) {

    $reply = new stdClass();

    if (!isset($inputs->email)) {
        $inputs->email = "";
    }

    if (!isset($inputs->username)) {
        $inputs->username = "";
    }

    $hostname = "localhost";
    $username = "u906128965_admin";
    $password = "R*$1E=fr8~";
    $database = "u906128965_db_graffiti";

    $newConnect = new PDO("mysql:host=$hostname;dbname=$database", $username, $password);

    $insertStatement = "INSERT INTO feedback (feedback, username, email) VALUES (?, ?, ?)";
    try {
        // $queryObj = $connection->prepare($insertStatement);
        $queryObj = $newConnect->prepare($insertStatement);
        $queryObj->execute([$inputs->feedback, $inputs->username, $inputs->email]);
        $reply->query = $queryObj;
        $reply->status = "success";
        $reply->message = "feedback submitted";
    } catch (PDOException $pe) {
        $reply->status = "fail";
        $reply->message = "error submitting feedback";
    }

    return $reply;
}

?>