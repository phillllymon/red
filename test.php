<?php

header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Origin: *");

include_once("API/helpers/sendEmail.php");

$emails = [
    "rparkerharris@gmail.com",
    "rparkerharris@gmail.com",
    "rparkerharris@gmail.com"
];
$bodies = [
    "one",
    "two",
    "three"
];
$subjects = [
    "1",
    "2",
    "3"
];

sendEmail($emails, $bodies, $subjects);

?>