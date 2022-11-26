<?php

function connectToDatabase() {
    $hostname = "localhost";
    $username = "u906128965_admin";
    $password = "R*$1E=fr8~";
    $database = "u906128965_db_graffiti";

    return new PDO("mysql:host=$hostname;dbname=$database", $username, $password);
}

?>