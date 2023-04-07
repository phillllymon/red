<?php

    include_once("connectToDatabase.php");

    function sendAlert($kind, $value, $data) {

        $connection = connectToDatabase();

        $insertStatement = "INSERT INTO alerts (kind, value, data) VALUES (?, ?, ?)";
        try {
            $queryObj = $connection->prepare($insertStatement);
            $queryObj->execute([$kind, $value, $data]);
        } catch (PDOException $pe) {
            return "database error";
        }
        return "success";
    }

?>