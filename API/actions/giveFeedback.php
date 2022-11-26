<?php

/*
inputs: feedback [text], email [string]
-make email empty string if not defined
-add feedback and email into table
-status fail or success
-message "feedback submitted" or "database error"
*/
function giveFeedback($connection) {

    $reply = new stdClass();

    $reply->status = "fail";
    $reply->message = "action not yet implemented";

    return $reply;
}

?>