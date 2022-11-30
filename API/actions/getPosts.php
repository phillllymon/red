<?php
include_once("./helpers/checkForData.php");
include_once("./helpers/setErrorReply.php");
function getPosts($connection, $inputs) {
    $reply = new stdClass();

    if (!checkForData($inputs, ["url"])) {
        return setErrorReply("url required");
    }

    $skip = isset($inputs->skip) ? $inputs->skip : 0;
    $limit = isset($inputs->limit) ? $inputs->limit : 1000;

    $getStatement = "SELECT * FROM posts WHERE url=? ORDER BY created desc LIMIT {$limit} OFFSET {$skip}";
    try {
        $queryObj = $connection->prepare($getStatement);
        $queryObj->execute([$inputs->url]);
        $reply->status = "success";
        $reply->message = "posts fetched";
        $reply->posts = $queryObj->fetchAll();
    } catch (PDOException $pe) {
        return setErrorReply("database error");
    }

    // if (isset($inputs->skip) && isset($inputs->limit)) {
    //     $reply->posts = array_slice($existingPosts, $inputs->start, $inputs->limit);
    // } else {
    //     $reply->posts = $existingPosts;
    // }

    return $reply;
}

?>