<?php
include_once("./helpers/checkForData.php");
include_once("./helpers/setErrorReply.php");
include_once("./helpers/secretManager.php");
include_once("./helpers/sendEmail.php");
function resendEmail($connection, $inputs) {

    $reply = new stdClass();

    if (!checkForData($inputs, ["username"])) {
        return setErrorReply("username required");
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

    $accountStatus = $existingUsers[0]["accountStatus"];
    if ($accountStatus == "confirmed" || $accountStatus == "original") {
        setErrorReply("user already confirmed");
    }

    $confirmToken = generateFriendlyCode(8);
    $confirmTokenHash = createPasswordHash($confirmToken);

    $updateStatement = "UPDATE users SET accountStatus=? WHERE username=?";
    try {
        $queryObj = $connection->prepare($updateStatement);
        $queryObj->execute([$confirmTokenHash, $inputs->username]);
        $reply->info = "confirm token updated";
    } catch (PDOException $pe) {
        return setErrorReply("database error");
    }

    $email = $existingUsers[0]["email"];
    $subject = "New GRAFFITI confirmation code";
    $message = "Hello {$inputs->username}:\r\n
    Welcome to GRAFFITI! Please confirm your email with the following code:\r\n 
    {$confirmToken}\r\n\r\nCheers,\r\n GRAFFITI team";

    sendEmail([$email], [$message], [$subject]);

    $reply->status = "success";
    $reply->message = "new email sent";

    return $reply;
}

?>