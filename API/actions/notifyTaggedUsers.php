<?php
include_once("./helpers/checkForData.php");
include_once("./helpers/setErrorReply.php");
include_once("./helpers/secretManager.php");
function notifyTaggedUsers($connection, $inputs) {

    $reply = new stdClass();

    if (!checkForData($inputs, ["url", "author", "tags"])) {
        return setErrorReply("url, author, and tags required");
    }

    $reply->status = "success";
    $reply->tags = $inputs->tags;
    return $reply;

    // $getStatement = "SELECT * FROM users WHERE email=?";
    // try {
    //     $queryObj = $connection->prepare($getStatement);
    //     $queryObj->execute([$email]);
    //     $existingUsers = $queryObj->fetchAll();
    // } catch (PDOException $pe) {
    //     return setErrorReply("database error");
    // }

    // if (count($existingUsers) != 1) {
    //     return setErrorReply("user not found");
    // }

    // $username = $existingUsers[0]["username"];
    // $newPassword = generateRandomToken(10);
    // $passHash = createPasswordHash($newPassword);

    // $updateStatement = "UPDATE users SET pass=? WHERE username=?";
    // try {
    //     $queryObj = $connection->prepare($updateStatement);
    //     $queryObj->execute([$passHash, $username]);
    //     $reply->status = "success";
    //     $reply->message = "password reset";
    // } catch (PDOException $pe) {
    //     return setErrorReply("database error");
    // }

    // $to      = $email;
    // $subject = "GRAFFITI password reset";
    // $message = "hello:\n\n
    // Your username is {$username}.\n
    // Your new password is {$newPassword}.\n
    // We suggest you change your password the next time you log in.\n\n
    // Cheers,\n
    // GRAFFITI dev team";
    // $headers = "From: password@graffiti.red" . "\r\n" .
    // "Reply-To: info@graffiti.red" . "\r\n" .
    // "X-Mailer: PHP/" . phpversion();

    // mail($to, $subject, $message, $headers);

    // return $reply;
}

?>