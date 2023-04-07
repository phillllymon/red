<?php

header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Origin: *");


// $testStrings = [
//     "graffiti.red",
//     "google.com",
//     "5.324",
//     "https://www.onehundredmain.com",
//     "onehundredmain.com"
// ];

// for ($i = 0; $i < count($testStrings); $i++) {
//     echo "<br>";
//     if (filter_var($testStrings[$i], FILTER_VALID_URL)) {
//         echo "true";
//     } else {
//         echo "false";
//     }
// }

// /*

$headers  = "From: notifications@graffiti.red\r\n"; 
$headers .= "X-Sender: notifications@graffiti.red\r\n";
// $headers .= "X-Priority: 1\n"; // Urgent message!
$headers .= "Return-Path: info@graffiti.red\r\n";
// $headers .= "MIME-Version: 1.0\r\n";
// $headers .= "Content-Type: text/html; charset=utf-8\r\n";
// $headers .= "Content-Transfer-Encoding: base64\r\n";
// $headers .= 'X-Mailer: PHP/\r\n' . phpversion();

$to = "rparkerharris@gmail.com";
$sub = "fancy subject";
$content = "This is my message\r\nThis is the second line";

if (mail($to, $sub, $content, $headers)) {
    echo "SUCCESS";
} else {
    echo "FAIL";
}

// include_once("API/helpers/sendAlert.php");
// include_once("API/helpers/sendEmail.php");


// // echo sendAlert("test alert", "SUCCESS", serialize([1, 2, 3]));

// $emails = [
//     "rparkerharris@gmail.com"
// ];

// $user = "PoopyFace";

// $content = "Hello {$user}:\r\n\r\nSomeone recently tagged you in a post on the following url:\r\n\r\nhttps://www.google.com\r\n\r\nCheers,\r\nGRAFFITI team";

// $bodies = [
//     $content
// ];
// $subjects = [
//     "3"
// ];

// sendEmail($emails, $bodies, $subjects);

// */

?>