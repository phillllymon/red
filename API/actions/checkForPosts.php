<?php
require("./helpers/checkForData.php");
require("./helpers/setErrorReply.php");

function checkForPosts($connection, $inputs) {
    $reply = new stdClass();

    if (!checkForData($inputs, ["url"])) {
        return setErrorReply("url required");
    }

    $getStatement = "SELECT * FROM posts WHERE url=?";
    try {
        $queryObj = $connection->prepare($getStatement);
        $queryObj->execute([$inputs->url]);
        $reply->status = "success";
        $reply->message = "checked for posts";
        $reply->answer = count($queryObj->fetchAll()) > 0;
    } catch (PDOException $pe) {
        return setErrorReply("database error");
    }

    return $reply;
}

?>