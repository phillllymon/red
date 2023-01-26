<?php

header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Origin: *");

require "helpers/sanitize.php";
require "actions.php";
require "afterActions.php";

// TEST ONLY
// $reply = new stdClass();
// $reply->temp = "different new response!";
// echo json_encode($reply);
// die();
// END TEST

$inputs = sanitizeAll(json_decode(file_get_contents('php://input')));

$dealbreakers = [
    $_SERVER["REQUEST_METHOD"] != "POST",
    !(isset($inputs->action) && in_array($inputs->action, $availableActions))
];

// -----

foreach($dealbreakers as $breaker) {
    if ($breaker) {
        echo "invalid request\n";
        die("dealbreaker encountered");
    }
}

// ----------- real work begins here -------------

$actionToTake = $inputs->action;

echo json_encode(executeAction($actionToTake, $inputs));
followUp($actionToTake, $inputs);


?>