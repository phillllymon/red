<?php
include_once("./helpers/checkForData.php");
include_once("./helpers/setErrorReply.php");
include_once("./helpers/secretManager.php");
include_once("./helpers/getNumUnreads.php");
function logIn($connection, $inputs) {
    $reply = new stdClass();

    if (!checkForData($inputs, ["username", "pass"])) {
        return setErrorReply("username and pass required");
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

    $userRow = $existingUsers[0];

    $existingPassHash = $userRow["pass"];
    if (!comparePasswordAgainstHash($inputs->pass, $existingPassHash)) {
        return setErrorReply("invalid password");
    }

    if ($userRow["accountStatus"] != "original" && $userRow["accountStatus"] != "confirmed") {
        return setErrorReply("account not confirmed");
    }

    $token = generateRandomToken();
    $tokenHash = createPasswordHash($token);
    $updateStatement = "UPDATE users SET token=? WHERE username=?";
    try {
        $queryObj = $connection->prepare($updateStatement);
        $queryObj->execute([$tokenHash, $inputs->username]);
        $reply->status = "success";
        $reply->message = "user logged in";
        $reply->username = $existingUsers[0]["username"];
        $reply->avatar = $existingUsers[0]["avatar"];
        $reply->token = $token;

        // NEW here -----------------------
        $reply->numUnreads = getNumUnreads($inputs->username, $connection);
        // end NEW ------------------------

    } catch (PDOException $pe) {
        return setErrorReply("database error");
    }

    return $reply;
}

?>