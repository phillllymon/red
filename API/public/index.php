<?php

header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");

// $hostname = "localhost";
// $username = "u906128965_worker";
// $password = "5>Lw7Jw~";
// $database = "u906128965_db_public";

// test creds
$hostname = "localhost:9000";
$username = "sa";
$password = "reallyStrongPwd123";
$database = "testDB";

$answer = new stdClass();

$answer->message = "";
$answer->value = "";
// $connection = null;

// $inputs = evaluateInput(json_decode(file_get_contents('php://input')));

// testing only
$goodData = file_get_contents('php://input');
$phpObj = json_decode($goodData);

$testReturn = new stdClass();
$testReturn->change = "good";
$testReturn->yourAction = $phpObj->action;

// $connection = new PDO("mysql:host=$hostname;dbname=$database", $username, $password);

// echo json_encode($testReturn);
// die();

try {
    

    $connection = new PDO("mysql:host=$hostname;dbname=$database", $username, $password);
    $testReturn->status = "connected successfully!";
    // echo json_encode($testReturn);
    // die();

} catch (PDOException $pe) {

    // bad here
    // $testReturn->error = $pe->errorInfo[2];
    $testReturn->error = $pe;
    echo json_encode($testReturn);
    die();

}

echo json_encode($testReturn);
die();
// end testing

if (!isset($inputs->action)) {
    echo ("no action specified");
    die();
}

try {
    $connection = new PDO("mysql:host=$hostname;dbname=$database", $username, $password);
} catch (PDOException $pe) {
    $answer->message = "database error";
}

$getStatement = "SELECT * FROM public WHERE name=?";
// $existingValues = null;
try {
    $connection->prepare($getStatement)->execute([$inputs->name]);
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
    // make sure mode is POST
    // if ($_SERVER["REQUEST_METHOD"] != "POST") {
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

            echo "jjj";
            $myVar = file_get_contents('php://input');
            $myOtherVar = json_decode($myVar);
            if (isset($myVar)) {
                echo $myOtherVar;
            } else {
                echo "no";
            }
            
            // echo "action must be set or retrieve";
            die();
        }
    // } else {
    //     echo "request mode must be POST";
    //     die();
    // }
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