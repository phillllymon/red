<?php

include_once("API/helpers/unFollowUrl.php");

header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Origin: *");

$hostname = "localhost";
$username = "u906128965_admin";
$password = "R*$1E=fr8~";
$database = "u906128965_db_graffiti";

$connection = new PDO("mysql:host=$hostname;dbname=$database", $username, $password);

$url = $_GET["url"];
$username = $_GET["username"];
$token = $_GET["token"];

$getStatement = "SELECT * FROM users WHERE username=?";
$queryObj = $connection->prepare($getStatement);
$queryObj->execute([$username]);
$existing = $queryObj->fetchAll();

if (count($existing) != 1) {
    echo "Seems to be an error. Sorry about that.";
    die();
}

$tokens = unserialize($existing[0]["unfollowTokens"]);
$tokenValid = false;
foreach($tokens as $userToken) {
    if ($token == $userToken) {
        $tokenValid = true;
    }
}
if (!$tokenValid) {
    echo "Seems to be an error or this link has expired. Sorry about that. You can still unfollow any conversation in the GRAFFITI extension.";
    die();
}

unFollowUrl($username, $url, $connection);

echo "Success! Unless you rejoin the conversation, you will no longer receive notifications for this page.";

?>