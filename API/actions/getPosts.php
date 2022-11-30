<?php
require("./helpers/checkForData.php");
require("./helpers/setErrorReply.php");
function getPosts($connection, $inputs) {
    $reply = new stdClass();

    if (!checkForData($inputs, ["url"])) {
        return setErrorReply("url required");
    }

    $getStatement = "SELECT * FROM posts WHERE url=?";
    try {
        $queryObj = $connection->prepare($getStatement);
        $queryObj->execute([$inputs->url]);
        $reply->status = "success";
        $reply->message = "posts fetched";
        $existingPosts = $queryObj->fetchAll();
    } catch (PDOException $pe) {
        return setErrorReply("database error");
    }

    if (isset($inputs->start) && isset($inputs->limit)) {
        $reply->posts = array_slice($existingPosts, $inputs->start, $inputs->limit);
    } else {
        $reply->posts = $existingPosts;
    }

    return $reply;
}

?>