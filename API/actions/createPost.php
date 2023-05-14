<?php
include_once("./helpers/checkForData.php");
include_once("./helpers/setErrorReply.php");
include_once("./helpers/secretManager.php");
include_once("./helpers/processUrl.php");
include_once("./helpers/processContent.php");
function createPost($connection, $inputs) {

    $reply = new stdClass();

    if (!checkForData($inputs, ["username", "token", "url", "content"])) {
        return setErrorReply("username, token, url, and content required");
    }

    $goodUrl = processUrl($inputs->url);

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

    if (!comparePasswordAgainstHash($inputs->token, $existingToken)) {
        return setErrorReply("token invalid");
    }

    $postContent = processContent($inputs->content);

    $insertStatement = "INSERT INTO posts (username, url, content) VALUES (?, ?, ?)";
    try {
        $queryObj = $connection->prepare($insertStatement);
        $queryObj->execute([$inputs->username, $goodUrl, $postContent]);
        $newPostId = $connection->lastInsertId();
        $reply->status = "success";
        $reply->message = "post created";
    } catch (PDOException $pe) {
        return setErrorReply("database error");
    }

    $getStatement = "SELECT * FROM posts WHERE id=?";
    try {
        $queryObj = $connection->prepare($getStatement);
        $queryObj->execute([$newPostId]);
        $newPost = $queryObj->fetchAll()[0];
    } catch (PDOException $pe) {
        return setErrorReply("new post could not be retrieved");
    }

    // make sure there's an entry in urls
    $getStatement = "SELECT * FROM urls WHERE url=?";
    try {
        $queryObj = $connection->prepare($getStatement);
        $queryObj->execute([$goodUrl]);
        $existingUrls = $queryObj->fetchAll()[0];
    } catch (PDOException $pe) {
        return setErrorReply("database error");
    }

    if (count($existingUrls) != 1) {
        $insertStatement = "INSERT INTO urls (url, pretty) VALUES (?, ?)";
        try {
            $queryObj = $connection->prepare($insertStatement);
            $queryObj->execute([$goodUrl, makePretty($goodUrl)]);
        } catch (PDOException $pe) {
            return setErrorReply("database error");
        }
    }

    $reply->post = $newPost;

    return $reply;
}

?>