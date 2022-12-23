<?php

function resetPassword($connection, $inputs) {

    $reply = new stdClass();

    // the message
    $msg = "First line of text\nSecond line of text";

    // use wordwrap() if lines are longer than 70 characters
    $msg = wordwrap($msg,70);

    // send email
    mail("rparkerharris@gmail.com","My subject",$msg);

    $reply->message = "email sent";

    return $reply;
}

?>