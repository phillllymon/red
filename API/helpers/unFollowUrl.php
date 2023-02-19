<?php

    /*
    1. Parent function responsible for processing url and verifying user is logged in
    2. This simply goes to the table deletes all relevant rows it finds
    */
    function unFollowUrl($username, $url, $connection) {
        $getStatement = "SELECT * FROM following WHERE username=? AND url=?";
        try {
            $queryObj = $connection->prepare($getStatement);
            $queryObj->execute([$username, $url]);
            $existingFollows = $queryObj->fetchAll();
        } catch (PDOException $pe) {
            return "database error";
        }
        if (count($existingFollows) > 0) {
            $deleteStatement = "DELETE FROM following WHERE username=? AND url=?";
            try {
                $queryObj = $connection->prepare($deleteStatement);
                $queryObj->execute([$username, $url]);
            } catch (PDOException $pe) {
                return setErrorReply("database error");
            }
            return "success";
        } else {
            return "was not following";
        }
    }

?>