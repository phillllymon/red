<?php
include_once("./helpers/checkForData.php");
include_once("./helpers/setErrorReply.php");
include_once("./helpers/secretManager.php");
function deleteAccount($connection, $inputs) {
    $reply = new stdClass();

    if (!checkForData($inputs, ["username", "pass", "token"])) {
        return setErrorReply("username, pass, and token required");
    }

    $getStatement = "SELECT * FROM users WHERE username=?";
    try {
        $queryObj = $connection->prepare($getStatement);
        $queryObj->execute([$inputs->username]);
        $existingUsers = $queryObj->fetchAll();
    } catch (PDOException $pe) {
        return setErrorReply("database error");
    }

    if (count($existingUsers) != 1) {
        return setErrorReply("user not found");
    }

    $existingPassHash = $existingUsers[0]["pass"];
    if (!comparePasswordAgainstHash($inputs->oldPass, $existingPassHash)) {
        return setErrorReply("password invalid");
    }

    $existingToken = $existingUsers[0]["token"];
    if (!comparePasswordAgainstHash($inputs->token, $existingToken)) {
        return setErrorReply("user not logged in");
    }

    $deleteStatement = "DELETE FROM users WHERE username=?";
    try {
        $queryObj = $connection->prepare($deleteStatement);
        $queryObj->execute([$inputs->username]);
        $reply->status = "success";
        $reply->message = "account deleted";
    } catch (PDOException $pe) {
        return setErrorReply("database error");
    }

    return $reply;
}

?>