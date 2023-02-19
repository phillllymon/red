<?php
include_once("./helpers/checkForData.php");
include_once("./helpers/setErrorReply.php");
include_once("./helpers/secretManager.php");
include_once("./helpers/getNumUnreads.php");
function checkLogin($connection, $inputs) {
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
        $reply->status = "success";
        $reply->message = "user not found";
        $reply->answer = false;
        return $reply;
    }

    $existingToken = $existingUsers[0]["token"];
    if ($existingToken == null || !comparePasswordAgainstHash($inputs->token, $existingToken)) {
        $reply->status = "success";
        $reply->message = "user not logged in";
        $reply->answer = false;
    } else {

        // NEW here -----------------------
        $reply->numUnreads = getNumUnreads($inputs->username, $connection);
        // end NEW ------------------------

        // $following = unserialize($existingUsers[0]["following"]);
        // $numUnreads = 0;
        // foreach($following as $urlRow) {
        //     if (!$urlRow[1]) {
        //         $numUnreads++;
        //     }
        // }
        // $reply->numUnreads = $numUnreads;

        $reply->status = "success";
        $reply->message = "user logged in";
        $reply->answer = true;
    }

    return $reply;
}

?>