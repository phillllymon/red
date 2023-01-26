<?php

header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Origin: *");

$hostname = "localhost";
$username = "u906128965_admin";
$password = "R*$1E=fr8~";
$database = "u906128965_db_graffiti";

$connection = new PDO("mysql:host=$hostname;dbname=$database", $username, $password);

$getStatement = "SELECT * FROM urls WHERE url=?";
$queryObj = $connection->prepare($getStatement);
$queryObj->execute([$_GET["url"]]);
$existing = $queryObj->fetchAll();

if (count($existing) == 1) {
    $followers = unserialize($existing[0]["followers"]);
    $newArr = [];
    foreach($followers as $follower) {
        if ($follower != $_GET["username"]) {
            array_push($newArr, $follower);
        }
    }
    $updateStatement = "UPDATE urls SET followers=? WHERE url=?";
    $updateObj = $connection->prepare($updateStatement);
    $updateObj->execute([serialize($newArr), $_GET["url"]]);
    echo "Success! <br><br> Unless you rejoin the conversation, you will no longer receive notifications for this page.";
} else {
    echo "Seems to be an error. Sorry about that.";
}

?>