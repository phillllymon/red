<?php
include_once("./helpers/checkForData.php");
include_once("./helpers/setErrorReply.php");
include_once("./helpers/processUrl.php");
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

    // update users table to add url to this user's url array
    $goodUrl = processUrl($inputs->url);
    $userRow = $existingUsers[0];
    $following = unserialize($userRow["following"]);
    array_push($following, [$goodUrl, true]);

    // see if we're following too many posts
    if (count($following) > 50) {
        array_splice($following, count($following) - 50);
    }
    
    $updateStatement = "UPDATE users SET following=? WHERE username=?";
    try {
        $queryObj = $connection->prepare($updateStatement);
        $queryObj->execute([serialize($following), $inputs->username]);
    } catch (PDOException $pe) {
        return setErrorReply("error updating users table");
    }
    

    // update urls table to ad user to following this url
    $getStatement = "SELECT * FROM urls WHERE url=?";
    try {
        $queryObj = $connection->prepare($getStatement);
        $queryObj->execute([$goodUrl]);
        $existingUrls = $queryObj->fetchAll();
    } catch (PDOException $pe) {
        return setErrorReply("database error");
    }

    if (count($existingUrls) != 1) {
        return setErrorReply("error getting url row from table");
    }

    $urlRow = $existingUrls[0];
    $followers = unserialize($urlRow["followers"]);
    
    array_push($followers, $inputs->username);

    $updateStatement = "UPDATE urls SET followers=? WHERE url=?";
    try {
        $queryObj = $connection->prepare($updateStatement);
        $queryObj->execute([serialize($followers), $goodUrl]);
    } catch (PDOException $pe) {
        return setErrorReply("error updating urls table");
    }


    $reply->status = "success";
    return $reply;
}

?>