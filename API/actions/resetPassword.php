<?php
include_once("./helpers/checkForData.php");
include_once("./helpers/setErrorReply.php");
include_once("./helpers/secretManager.php");
function resetPassword($connection, $inputs) {

    $reply = new stdClass();

    if (!checkForData($inputs, ["email"])) {
        return setErrorReply("email required");
    }

    $email = $inputs->email;

    // $getStatement = "SELECT * FROM users WHERE username=?";
    // try {
    //     $queryObj = $connection->prepare($getStatement);
    //     $queryObj->execute([$inputs->username]);
    //     $existingUsers = $queryObj->fetchAll();
    // } catch (PDOException $pe) {
    //     return setErrorReply("database error");
    // }

    // if (count($existingUsers) != 1) {
    //     return setErrorReply("user not found");
    // }

    $to      = $email;
    $subject = "GRAFFITI password reset";
    $message = "hello";
    $headers = "From: graffitiPassword@graffiti.red" . "\r\n" .
    "Reply-To: info@graffiti.red" . "\r\n" .
    "X-Mailer: PHP/" . phpversion();

    mail($to, $subject, $message, $headers);

    $reply->message = $email;

    return $reply;
}

?>