<?php
include_once("./helpers/checkForData.php");
include_once("./helpers/setErrorReply.php");
include_once("./helpers/secretManager.php");
function changeAvatar($connection, $inputs) {
    $reply = new stdClass();

    if (!checkForData($inputs, ["username", "token", "avatar"])) {
        return setErrorReply("username, token, and avatar required");
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

    $existingToken = $existingUsers[0]["token"];
    if ($existingToken == null) {
        return setErrorReply("user not logged in");
    }

    $updateStatement = "UPDATE users SET avatar=? WHERE username=?";
    try {
        $queryObj = $connection->prepare($updateStatement);
        $queryObj->execute([$inputs->avatar, $inputs->username]);
        $reply->status = "success";
        $reply->message = "avatar changed";
    } catch (PDOException $pe) {
        return setErrorReply("database error");
    }

    $reply->avatar = $inputs->avatar;

    return $reply;
}

?>