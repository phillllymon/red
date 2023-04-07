<?php
include_once("./helpers/checkForData.php");
include_once("./helpers/setErrorReply.php");
include_once("./helpers/secretManager.php");
function signUp($connection, $inputs) {

    $reply = new stdClass();

    if (!checkForData($inputs, ["username", "avatar", "pass", "email"])) {
        return setErrorReply("username, email, avatar, and pass required");
    }

    $getStatement = "SELECT * FROM users WHERE username=?";
    try {
        $queryObj = $connection->prepare($getStatement);
        $queryObj->execute([$inputs->username]);
        $existingUsers = $queryObj->fetchAll();
    } catch (PDOException $pe) {
        return setErrorReply("database error");
    }

    if (count($existingUsers) > 0) {
        return setErrorReply("user already exists");
    }

    $passHash = createPasswordHash($inputs->pass);
    $token = generateRandomToken();
    $tokenHash = createPasswordHash($token);
    $insertStatement = "INSERT INTO users (username, email, avatar, pass, token) VALUES (?, ?, ?, ?, ?)";
    try {
        $queryObj = $connection->prepare($insertStatement);
        $queryObj->execute([$inputs->username, $inputs->email, $inputs->avatar, $passHash, $tokenHash]);
        $reply->status = "success";
        $reply->message = "user added";
        $reply->token = $token;
    } catch (PDOException $pe) {
        return setErrorReply("database error");
    }

    return $reply;
}

?>