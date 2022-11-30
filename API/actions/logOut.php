<?php
// require("./helpers/checkForData.php");
require("./helpers/setErrorReply.php");
function logOut($connection, $inputs) {

    $reply = new stdClass();

    // TEST ONLY
    $reply->message = "testing here";
    return $reply;
    // END TEST

    // if (!checkForData($inputs, ["username", "token"])) {
    //     return setErrorReply("username and token required");
    // }

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

    // $updateStatement = "UPDATE users SET token=? WHERE username=?";
    // try {
    //     $queryObj = $connection->prepare($updateStatement);
    //     $queryObj->execute([null, $inputs->username]);
    //     $reply->status = "success";
    //     $reply->message = "user logged out";
    // } catch (PDOException $pe) {
    //     return setErrorReply("database error");
    // }

    // return $reply;
}

?>