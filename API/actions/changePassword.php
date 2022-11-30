<?php
include_once("./helpers/checkForData.php");
include_once("./helpers/setErrorReply.php");
include_once("./helpers/secretManager.php");
function changePassword($connection, $inputs) {
    $reply = new stdClass();

    if (!checkForData($inputs, ["username", "oldPass", "token", "newPass"])) {
        return setErrorReply("username, oldPass, token, and newPass required");
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
        return setErrorReply("old password invalid");
    }

    $existingToken = $existingUsers[0]->token;
    if ($inputs->token != $existingToken) {
        return setErrorReply("token invalid");
    }

    $passHash = createPasswordHash($inputs->newPass);
    $updateStatement = "UPDATE users SET pass=? WHERE username=?";
    try {
        $queryObj = $connection->prepare($updateStatement);
        $queryObj->execute([$passHash, $inputs->username]);
        $reply->status = "success";
        $reply->message = "password changed";
    } catch (PDOException $pe) {
        return setErrorReply("database error");
    }

    return $reply;
}

?>