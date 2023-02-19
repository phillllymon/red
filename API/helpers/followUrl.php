<?php

    /*
    1. Parent function responsible for processing url and verifying user is logged in
    2. This simple goes to the table and inserts row if not already there
    3. Defaults to seen=true - If already following, does not affect seen.
    */
    function followUrl($username, $url, $connection) {

        $getStatement = "SELECT * FROM following WHERE username=? AND url=?";
        try {
            $queryObj = $connection->prepare($getStatement);
            $queryObj->execute([$username, $url]);
            $existingFollows = $queryObj->fetchAll();
        } catch (PDOException $pe) {
            return "database error";
        }

        if (count($existingFollows) == 0) {

            $insertStatement = "INSERT INTO following (username, url, seen) VALUES (?, ?, ?)";
            try {
                $queryObj = $connection->prepare($insertStatement);
                $queryObj->execute([$username, $url, true]);
            } catch (PDOException $pe) {
                return "database error";
            }
            return "success";
        } else {
            return "already following";
        }
    }

?>