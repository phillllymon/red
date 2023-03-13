<?php
include_once("./helpers/checkForData.php");
include_once("./helpers/setErrorReply.php");
include_once("./helpers/processUrl.php");
include_once("./helpers/followUrl.php");
function follow($connection, $inputs) {
    $reply = new stdClass();

    if (!checkForData($inputs, ["username", "token", "url"])) {
        return setErrorReply("username, token, and url required");
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
    if (!comparePasswordAgainstHash($inputs->token, $existingToken)) {
        return setErrorReply("user not logged in");
    }

    // NEW here ----------------------
    $goodUrl = processUrl($inputs->url);
    $username = $inputs->username;

    $followResult = followUrl($username, $goodUrl, $connection);
    if ($followResult == "success") {
        $reply->status = "success";
        $reply->message = "now following post";
    } else if ($followResult == "already following") {
        $reply->status = "success";
        $reply->message = "already following";
    } else {
        setErrorReply("problem following");
    }
    // END new -----------------------


    $reply->status = "success";
    return $reply;
}

?>