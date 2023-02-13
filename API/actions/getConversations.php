<?php
include_once("./helpers/checkForData.php");
include_once("./helpers/setErrorReply.php");
include_once("./helpers/processUrl.php");
function getConversations($connection, $inputs) {
    $reply = new stdClass();

    if (!checkForData($inputs, ["username", "token"])) {
        return setErrorReply("username and token required");
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
        return setErrorReply("token invalid");
    }

    $info = new stdClass();
    $info->icons = new stdClass();

    $following = unserialize($existingUsers[0]["following"]);
    foreach($following as $urlRow) {
        $url = $urlRow[0];
        $getStatement = "SELECT * FROM urls WHERE url=?";
        try {
            $queryObj = $connection->prepare($getStatement);
            $queryObj->execute([$url]);
            $existingUrls = $queryObj->fetchAll();
        } catch (PDOException $pe) {
            return setErrorReply("database error");
        }
        if (count($existingUrls) == 1) {
            // cool, we're in business
            $urlTableRow = $existingUrls[0];
            $info->$url = new stdClass();
            $info->$url->pretty = $urlTableRow["pretty"];
            $info->$url->icon = determineIcon($url);
        }
    }

    $reply->info = $info;
    $reply->conversations = $following;
    
    return $reply;
}

function determineIcon($url) {
    if (str_has($url, ".craigslist.org")) {
        return "cl.png";
    }
    if (str_has($url, ".youtube.com")) {
        return "youtube.png";
    }
    return null;
}

?>