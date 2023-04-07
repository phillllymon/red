<?php

header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Origin: *");

include_once("API/helpers/sendEmailShouldWork.php");
include_once("API/helpers/sendAlert.php");

sendAlert("top level call", "starting", $_POST["emailInfo"]);

$response = new stdClass();
$response->status = "it worked I guess";

$inputs = json_decode(file_get_contents('php://input'));

$emailData = unserialize($_POST["emailInfo"]);
$recipients = $emailData->recipients;
$subjects = $emailData->subjects;
$contents = $emailData->contents;

// $response->inputs = $inputs;
// $recipient = $inputs->recipient;
// $subject = $inputs->subject;
// $content = $inputs->content;

// $recipient = "rparkerharris@gmail.com";
// $subject = "hardCodedSubject";
// $content = "hardCodedContent";

for ($i = 0; $i < count($recipients); $i++) {
    sendEmailShouldWork($recipients[$i], $contents[$i], $subjects[$i]);
}

echo json_encode($response);




?>