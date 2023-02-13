<?php
include_once("./helpers/checkForData.php");
include_once("./helpers/setErrorReply.php");
include_once("./helpers/processUrl.php");
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

    // // update users table to remove url from this user's url array
    $goodUrl = processUrl($inputs->url);
    $userRow = $existingUsers[0];
    $following = unserialize($userRow["following"]);
    $idx = null;
    for ($i = 0; $i < count($following); $i++) {
        if ($following[$i][0] == $goodUrl) {
            $idx = $i;
            break;
        }
    }
    if ($idx != null) {
        array_splice($following, $idx, 1);

        $updateStatement = "UPDATE users SET following=? WHERE username=?";
        try {
            $queryObj = $connection->prepare($updateStatement);
            $queryObj->execute([serialize($following), $inputs->username]);
        } catch (PDOException $pe) {
            return setErrorReply("error updating users table");
        }
    }

    // // update urls table to remove user from following this url
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
    $idx = null;
    for ($i = 0; $i < count($followers); $i++) {
        if ($followers[$i] == $inputs->username) {
            $idx = $i;
            break;
        }
    }
    if ($idx != null) {
        array_splice($followers, $idx, 1);

        $updateStatement = "UPDATE urls SET followers=? WHERE url=?";
        try {
            $queryObj = $connection->prepare($updateStatement);
            $queryObj->execute([serialize($followers), $goodUrl]);
        } catch (PDOException $pe) {
            return setErrorReply("error updating urls table");
        }
    }

    $reply->status = "success";
    return $reply;
}

?>