<?php
include_once("./helpers/checkForData.php");
include_once("./helpers/setErrorReply.php");
function checkForUser($connection, $inputs) {
    $reply = new stdClass();

    if (!checkForData($inputs, ["username"])) {
        return setErrorReply("username required");
    }

    $getStatement = "SELECT * FROM users WHERE username=?";
    try {
        $queryObj = $connection->prepare($getStatement);
        $queryObj->execute([$inputs->username]);
        $existingUsers = $queryObj->fetchAll();
    } catch (PDOException $pe) {
        return setErrorReply("database error");
    }

    $reply->status = "success";
    if (count($existingUsers) == 1) {
        $reply->exists = true;
        $reply->username = $existingUsers[0]["username"];
    } else { 
        $reply->exists = false;
    }

    return $reply;
}

?>