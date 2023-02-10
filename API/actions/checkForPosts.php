<?php
include_once("./helpers/checkForData.php");
include_once("./helpers/setErrorReply.php");
include_once("./helpers/processUrl.php");

function checkForPosts($connection, $inputs) {
    $reply = new stdClass();

    if (!checkForData($inputs, ["url"])) {
        return setErrorReply("url required");
    }

    $goodUrl = processUrl($inputs->url);

    $getStatement = "SELECT * FROM posts WHERE url=?";
    try {
        $queryObj = $connection->prepare($getStatement);
        $queryObj->execute([$goodUrl]);
        $reply->status = "success";
        $reply->message = "checked for posts";
        $reply->answer = count($queryObj->fetchAll()) > 0;
    } catch (PDOException $pe) {
        return setErrorReply("database error");
    }

    return $reply;
}

?>