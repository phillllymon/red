<?php

header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Origin: *");

// TEST ONLY
$reply = new stdClass();
$reply->temp = "testing";
echo json_encode($reply);
die();
// END TEST

require "helpers/sanitize.php";
require "actions.php";

$inputs = sanitizeAll(json_decode(file_get_contents('php://input')));

$dealbreakers = [
    $_SERVER["REQUEST_METHOD"] != "POST",
    !(isset($inputs->action) && in_array($inputs->action, $availableActions))
];

foreach($dealbreakers as $breaker) {
    if ($breaker) {
        echo "invalid request\n";
        die("dealbreaker encountered");
    }
}

// ----------- real work begins here -------------

$actionToTake = $inputs->action;

echo json_encode(executeAction($actionToTake, $inputs));




// $myArray = [
//     "poopface",
//     "facepoop",
//     "hello world!"
// ];

// $myData = json_decode(file_get_contents('php://input'));

// $myObj = new stdClass();

// $myObj->key1 = "hello";
// $myObj->key2 = "goodbye";
// $myObj->key3 = $actions;
// $myObj->key4 = $myData;
// $myObj->key5 = $_SERVER["REQUEST_METHOD"];
// $myObj->key6 = $_POST["myAction"];

// echo json_encode($myObj);

// $myObj = "{key1:poopface, key2:facepoop}";

// echo json_encode($myObj);





?>