<?php

    include_once("sendAlert.php");

/*
This function attempts to send all requested emails with PHP's mail function. Any emails that it fails to send it sends to
the top level mail funtion to try there.
*/

// $recipients, $contents, and $subjects should all be corresponding arrays of the same length
function sendEmail($recipients, $contents, $subjects) {

    $emailReport = [];

    $failedRecipients = [];
    $failedContents = [];
    $failedSubjects = [];

    $headers  = "From: notifications@graffiti.red\r\n"; 
    $headers .= "X-Sender: notifications@graffiti.red\r\n";
    // $headers .= "X-Priority: 1\n"; // Urgent message!
    $headers .= "Return-Path: info@graffiti.red\r\n";
    // $headers .= "MIME-Version: 1.0\r\n";
    // $headers .= "Content-Type: text/html; charset=utf-8\r\n";
    // $headers .= "Content-Transfer-Encoding: base64\r\n";
    // $headers .= 'X-Mailer: PHP/\r\n' . phpversion();

    for ($i = 0; $i < count($recipients); $i++) {

        $to = $recipients[$i];
        $cont = $contents[$i];
        $sub = $subjects[$i];

        if (mail($to, $sub, $cont, $headers)) {
            array_push($emailReport, ["SUCCESS", $to, $cont]);
        } else {
            array_push($failedRecipients, $to);
            array_push($failedContents, $cont);
            array_push($failedSubjects, $sub);

            array_push($emailReport, ["FAIL", $to, $cont]);
        }
    }

    sendAlert("email attempt", "php mail function", serialize($emailReport));

    if (count($failedRecipients) > 0) {

        $emailInfo = new stdClass();
        $emailInfo->recipients = $failedRecipients;
        $emailInfo->contents = $failedContents;
        $emailInfo->subjects = $failedSubjects;


        $post = array(
            'emailInfo' => serialize($emailInfo)
        );

        sendAlert("curl call attempt", "IN PROGRESS ".count($failedRecipients)." recipients", serialize($post));

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
            sendAlert("curl call attempt", "FAIL", serialize($post));
        }
    }
}

?>