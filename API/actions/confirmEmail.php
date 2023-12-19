<?php
include_once("./helpers/checkForData.php");
include_once("./helpers/setErrorReply.php");
include_once("./helpers/secretManager.php");
include_once("./helpers/getNumUnreads.php");
function confirmEmail($connection, $inputs) {
    $reply = new stdClass();

    if (!checkForData($inputs, ["username", "confirmCode"])) {
        return setErrorReply("username and confirmCode required");
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

    $codeHash = $existingUsers[0]["accountStatus"];
    if (comparePasswordAgainstHash($inputs->confirmCode, $codeHash)) {

        $updateStatement = "UPDATE users SET accountStatus=? WHERE username=?";
        try {
            $queryObj = $connection->prepare($updateStatement);
            $queryObj->execute(["confirmed", $inputs->username]);
        } catch (PDOException $pe) {
            return setErrorReply("database error");
        }

        $token = generateRandomToken();
        $tokenHash = createPasswordHash($token);

        $updateStatement = "UPDATE users SET token=? WHERE username=?";
        try {
            $queryObj = $connection->prepare($updateStatement);
            $queryObj->execute([$tokenHash, $inputs->username]);
        } catch (PDOException $pe) {
            return setErrorReply("database error");
        }

        $reply->status = "success";
        $reply->message = "user confirmed and logged in";
        $reply->token = $token;
        $reply->avatar = $existingUsers[0]["avatar"];
        $reply->numUnreads = getNumUnreads($inputs->username, $connection);


    } else {
        $reply->status = "fail";
        $reply->message = "incorrect code";
    }

    //make sure there are no other pending users with the same email address
    $email = $existingUsers[0]["email"];

    $getStatement = "SELECT * FROM users WHERE email=?";
    try {
        $queryObj = $connection->prepare($getStatement);
        $queryObj->execute([$email]);
        $offendingUsers = $queryObj->fetchAll();
    } catch (PDOException $pe) {
        return setErrorReply("database error");
    }

    $usernamesToGo = [];
    foreach ($offendingUsers as $userRow) {
        $thisUsername = $userRow["username"];
        if ($thisUsername != $inputs->username) {
            array_push($usernamesToGo, $thisUsername);
        }
    }

    foreach ($usernamesToGo as $offender) {
        $deleteStatement = "DELETE FROM users WHERE username=?";
        try {
            $queryObj = $connection->prepare($deleteStatement);
            $queryObj->execute([$offender]);
        } catch (PDOException $pe) {
            return setErrorReply("error with redundant emails");
        }
    }

    return $reply;
}

?>