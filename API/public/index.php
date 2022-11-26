<?php

header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");

$hostname = "localhost";
$username = "u906128965_worker";
$password = "5>Lw7Jw~";
$database = "u906128965_db_public";

$answer = new stdClass();

$answer->message = "";
$answer->value = "";

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    echo "request mode must be POST";
    die();
}

$inputs = evaluateInput(json_decode(file_get_contents('php://input')));

if (!isset($inputs->action)) {
    echo ("no action specified");
    die();
}

try {
    $connection = new PDO("mysql:host=$hostname;dbname=$database", $username, $password);
    $answer->message = "connected successfully";
} catch (PDOException $pe) {
    $answer->message = "database error";
}


$getStatement = "SELECT * FROM public WHERE name=?";
try {
    $queryObj = $connection->prepare($getStatement);
    $queryObj->execute([$inputs->name]);
    $existingValues = $queryObj->fetchAll();
} catch (PDOException $pe) {
    $answer->message = "database error";
}

if (count($existingValues) > 1) {
    $answer->message = "ERROR multiple values in database";
    echo json_encode($answer);
    die();
}

if ($inputs->action == "retrieve") {
    if (count($existingValues) == 1) {
        $answer->value = $existingValues[0]["value"];
        $answer->message = "successfully retrieved";
    } else {
        $answer->message = "not found";
        echo json_encode($answer);
    }
} else if (count($existingValues) === 1){
    $setStatement = "UPDATE public SET value=? WHERE name=?";
    try {
        $connection->prepare($setStatement)->execute([$inputs->value, $inputs->name]);
    } catch (PDOException $pe) {
        $answer->message = "database error";
    }
    $answer->message = "successfully updated";
    $answer->value = $inputs->value;
} else {
    $insertStatement = "INSERT INTO public (name, value) VALUES (?, ?)";
    try {
        $connection->prepare($insertStatement)->execute([$inputs->value, $inputs->name]);
    } catch (PDOException $pe) {
        $answer->message = "database error";
    }
    $answer->message = "successfully set new value";
    $answer->value = $inputs->value;
}

// ---- end ----
echo json_encode($answer);

// ---- helpers below ----
function evaluateInput($data) {
    if ($data->action == "retrieve") {
        if (checkTypes([
            [$data->name, "string"]
        ])) {
            // SUCCESS
            $usefulInput = new stdClass();
            $usefulInput->action = "retrieve";
            $usefulInput->name = $data->name;

            return $usefulInput;
        } else {
            echo "invalid";
            die();
        }
    } else if ($data->action == "set") {
        if (checkTypes([
            [$data->name, "string"],
            [$data->value, "string"]
        ])) {
            // SUCCESS
            $usefulInput = new stdClass();
            $usefulInput->action = "retrieve";
            $usefulInput->name = $data->name;
            $usefulInput->value = $data->value;

            return $usefulInput;
        } else {
            echo "invalid";
            die();
        }
    } else {
        echo "action must be set or retrieve";
        die();
    }
}

function checkTypes($valueTypePair) {
    foreach($valueTypePair as $pair) {
        if (!gettype($pair[0] !== $pair[1])) {
            return false;
        }
    }
    return true;
}

?>