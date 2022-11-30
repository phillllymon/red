<?php
include_once("./helpers/checkForData.php");
include_once("./helpers/setErrorReply.php");
include_once("./helpers/secretManager.php");
function logIn($connection, $inputs) {
    $reply = new stdClass();

    if (!checkForData($inputs, ["username", "pass"])) {
        return setErrorReply("username and pass required");
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

    $existingPassHash = $existingUsers[0]->pass;
    if (!comparePasswordAgainstHash($inputs->pass, $existingPassHash)) {
        return setErrorReply("invalid password");
    }

    $token = generateRandomToken();
    $updateStatement = "UPDATE users SET token=? WHERE username=?";
    try {
        $queryObj = $connection->prepare($updateStatement);
        $queryObj->execute([$token, $inputs->username]);
        $reply->status = "success";
        $reply->message = "user logged in";
        $reply->token = $token;
    } catch (PDOException $pe) {
        return setErrorReply("database error");
    }

    return $reply;
}

?>