<?php

/* 
The commented out stuff at the bottom doesn't work unless the original call to the server was to the top level.
I have no idea why. The hack is to use this function as a proxy which re-calls the server at the top level where
topLevelEmail.php which then calls the old function, now located in the file sendEmailShouldWork.php.
*/ 

// $recipients, $contents, and $subjects should all be corresponding arrays of the same length
function sendEmail($recipients, $contents, $subjects) {
    $emailInfo = new stdClass();
    $emailInfo->recipients = $recipients;
    $emailInfo->contents = $contents;
    $emailInfo->subjects = $subjects;


    $post = array(
        'emailInfo' => serialize($emailInfo)
    );

    try {
        $ch = curl_init('https://graffiti.red/topLevelEmail.php');
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        

        // sets max time in seconds to wait for response - 1 is min value
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, 1000);

        curl_exec($ch);
        curl_close($ch);
        sleep(1);
    } catch (exception $err) {

    }

    $ch = curl_init('https://graffiti.red/topLevelEmail.php');
    curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    

    // sets max time in seconds to wait for response - 1 is min value
    curl_setopt($ch, CURLOPT_TIMEOUT_MS, 100);

    curl_exec($ch);
    curl_close($ch);
    sleep(1);
    return true;
}

//     include_once('vendor/autoload.php');
//     use PHPMailer\PHPMailer\PHPMailer;

// // returns true if email sent successfully, false otherwise
// function sendEmail($recipient, $content, $subject, $headers = null) {

//     $mail = new PHPMailer(true);
//     $mail->isSMTP();
//     // $mail->SMTPDebug = 2;
//     $mail->Host = 'smtp.titan.email';
//     $mail->Port = 587;
//     $mail->SMTPAuth = true;
//     $mail->Username = 'info@graffiti.red';
//     $mail->Password = 'InfoMcDoogle!50';
//     $mail->setFrom('info@graffiti.red', 'notifications');
//     $mail->addReplyTo('info@graffiti.red', 'Info');
//     $mail->addAddress($recipient, '');
//     $mail->Subject = $subject;
//     // $mail->msgHTML(file_get_contents('message.html'), __DIR__);
//     $mail->Body = $content;

//     // $mail->addAttachment('attachment.txt');
//     if (!$mail->send()) {
//         return false;
//     } else {
//         return true;
//     }

// }

?>