<?php
include_once("./helpers/checkForData.php");
include_once("./helpers/setErrorReply.php");
include_once("./helpers/processUrl.php");
include_once("./helpers/unFollowUrl.php");
function unFollow($connection, $inputs) {
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

    $unFollowResult = unFollowUrl($username, $goodUrl, $connection);
    if ($unFollowResult == "success") {
        $reply->status = "success";
        $reply->message = "no longer following url";
    } else if($unFollowResult == "was not following") {
        $reply->status = "success";
        $reply->message = "was not following";
    } else {
        setErrorReply("problem unfollowing");
    }
    // END new -----------------------

    $reply->status = "success";
    return $reply;
}

?>