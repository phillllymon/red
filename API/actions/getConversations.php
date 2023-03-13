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

    // new HERE ------------------------------
    $following = [];
    $getStatement = "SELECT * FROM following WHERE username=?";
    try {
        $queryObj = $connection->prepare($getStatement);
        $queryObj->execute([$inputs->username]);
        $allFollowing = $queryObj->fetchAll();
    } catch (PDOException $pe) {
        return setErrorReply("database error");
    }
    foreach($allFollowing as $followingRow) {
        $seen = $followingRow["seen"] == 1 ? true : false;
        array_push($following, [$followingRow["url"], $seen]);
    }
    $info = new stdClass();

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
        $info->$url = new stdClass();
        if (count($existingUrls) == 1) {
            // cool, we're in business
            $urlTableRow = $existingUrls[0];
            $info->$url->pretty = $urlTableRow["pretty"];
            $info->$url->icon = determineIcon($url);
            $info->$url->modified = $urlTableRow["modified"];
        } else { // backup in case not in urls for some reason
            $info->$url->pretty = $url;
            $info->$url->icon = determineIcon($url);
            $info->$url->modified = 0;
        }
    }

    // end NEW -------------------------------

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
    if (str_has($url, "amazon.com")) {
        return "amazon.png";
    }
    if (str_has($url, "chrome.google.com")) {
        return "chrome.png";
    }
    if (str_has($url, "duckduckgo.com")) {
        return "duckduckgo.png";
    }
    if (str_has($url, "github.com")) {
        return "github.png";
    }
    if (str_has($url, "mozilla.org")) {
        return "mozilla.png";
    }
    if (str_has($url, "stackoverflow.co")) {
        return "stackOverflow.png";
    }
    if (str_has($url, "twitter.com")) {
        return "twitter.png";
    }
    if (str_has($url, "wafflegame.net")) {
        return "waffle.png";
    }
    if (str_has($url, "wikipedia.org")) {
        return "wikipedia.png";
    }
    if (str_has($url, "ycombinator.com")) {
        return "yCombinator.png";
    }
    if (str_has($url, "facebook.com")) {
        return "facebook.png";
    }
    return null;
}

?>