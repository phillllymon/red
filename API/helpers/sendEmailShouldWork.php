<?php

    include_once('vendor/autoload.php');
    use PHPMailer\PHPMailer\PHPMailer;

// returns true if email sent successfully, false otherwise
function sendEmailShouldWork($recipient, $content, $subject, $headers = null) {

    $mail = new PHPMailer(true);
    $mail->isSMTP();
    // $mail->SMTPDebug = 2;
    $mail->Host = 'smtp.titan.email';
    $mail->Port = 587;
    $mail->SMTPAuth = true;
    $mail->Username = 'info@graffiti.red';
    $mail->Password = 'InfoMcDoogle!50';
    $mail->setFrom('info@graffiti.red', 'notifications');
    $mail->addReplyTo('info@graffiti.red', 'Info');
    $mail->addAddress($recipient, '');
    $mail->Subject = $subject;
    // $mail->msgHTML(file_get_contents('message.html'), __DIR__);
    $mail->Body = $content;

    // $mail->addAttachment('attachment.txt');
    if (!$mail->send()) {
        return false;
    } else {
        return true;
    }

}

?>