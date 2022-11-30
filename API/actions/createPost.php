<?php
include_once("./helpers/checkForData.php");
include_once("./helpers/setErrorReply.php");
function createPost($connection, $inputs) {
    $reply = new stdClass();

    if (!checkForData($inputs, ["username", "token", "url", "content"])) {
        return setErrorReply("username, token, url, and content required");
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

    if ($inputs->token != $existingToken) {
        return setErrorReply("token invalid");
    }

    $insertStatement = "INSERT INTO posts (username, url, content) VALUES (?, ?, ?)";
    try {
        $queryObj = $connection->prepare($insertStatement);
        $queryObj->execute([$inputs->username, $inputs->url, $inputs->content]);
        $newPostObj = $queryObj->fetchAll();
        $reply->status = "success";
        $reply->message = "post created";
        $reply->post = $newPostObj;
    } catch (PDOException $pe) {
        return setErrorReply("database error");
    }

    return $reply;
}

?>