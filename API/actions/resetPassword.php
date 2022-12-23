<?php

function resetPassword($connection, $inputs) {

    $reply = new stdClass();

    $to      = 'rparkerharris@gmail.com';
    $subject = 'the subject';
    $message = 'hello';
    $headers = 'From: info@graffiti.red' . "\r\n" .
    'Reply-To: info@graffiti.red' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

    mail($to, $subject, $message, $headers);

    $reply->message = "email sent";

    return $reply;
}

?>