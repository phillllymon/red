<?php
include_once("./helpers/checkForData.php");
include_once("./helpers/setErrorReply.php");
include_once("./helpers/secretManager.php");
include_once("./helpers/sendEmail.php");
function signUp($connection, $inputs) {

    $reply = new stdClass();

    if (!checkForData($inputs, ["username", "avatar", "pass", "email"])) {
        return setErrorReply("username, email, avatar, and pass required");
    }

    // test only
    // $message = "Confirm email here: graffiti.red/terms";
    // return setErrorReply($message);
    // end test

    $overwritePendingUser = false;

    $getStatement = "SELECT * FROM users WHERE email=?";
    try {
        $queryObj = $connection->prepare($getStatement);
        $queryObj->execute([$inputs->email]);
        $existingUsers = $queryObj->fetchAll();
    } catch (PDOException $pe) {
        return setErrorReply("database error");
    }

    if (count($existingUsers) > 0) {
        $accountStatus = $existingUsers[0]["accountStatus"];
        if ($accountStatus == "confirmed" || $accountStatus == "original") {
            return setErrorReply("email already in use");
        } else {
            $overwritePendingUser = true;
        }
    }

    $getStatement = "SELECT * FROM users WHERE username=?";
    try {
        $queryObj = $connection->prepare($getStatement);
        $queryObj->execute([$inputs->username]);
        $existingUsers = $queryObj->fetchAll();
    } catch (PDOException $pe) {
        return setErrorReply("database error");
    }

    if (count($existingUsers) > 0) {
        $accountStatus = $existingUsers[0]["accountStatus"];
        if ($accountStatus == "confirmed" || $accountStatus == "original") {
            return setErrorReply("user already exists");
        } else {
            $overwritePendingUser = true;
        }
    }

    $passHash = createPasswordHash($inputs->pass);

    // TODO: get rid of token (so don't log new users in) once new version of extension is released
    $token = generateRandomToken();
    $tokenHash = createPasswordHash($token);

    // generate token for email confirmation
    $confirmToken = generateFriendlyCode(8);
    $confirmTokenHash = createPasswordHash($confirmToken);

    if ($overwritePendingUser) {
        $deleteStatement = "DELETE FROM users WHERE username=?";
        try {
            $queryObj = $connection->prepare($deleteStatement);
            $queryObj->execute([$inputs->username]);
        } catch (PDOException $pe) {
            return setErrorReply("database error");
        }
    }

    $insertStatement = "INSERT INTO users (username, email, avatar, pass, token, accountStatus) VALUES (?, ?, ?, ?, ?, ?)";
    try {
        $queryObj = $connection->prepare($insertStatement);
        $queryObj->execute([$inputs->username, $inputs->email, $inputs->avatar, $passHash, $tokenHash, $confirmTokenHash]);
        $reply->status = "success";
        $reply->message = "user added";
        $reply->token = $token; // TODO: remove this when we remove auto login for new users

        // send welcome email with confirmation code
        $subject = "Welcome to GRAFFITI!";
        $message = "Hello {$inputs->username}:\r\n
        Welcome to GRAFFITI! Please confirm your email with the following code:\r\n 
        {$confirmToken}\r\n\r\nCheers,\r\n GRAFFITI team";

        sendEmail([$inputs->email], [$message], [$subject]);

    } catch (PDOException $pe) {
        return setErrorReply("database error");
    }

    return $reply;
}

?>