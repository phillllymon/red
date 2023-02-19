<?php

function markUrlAsUnread($url, $author, $connection) {
    $getStatement = "SELECT * FROM following WHERE url=?";
    try {
        $queryObj = $connection->prepare($getStatement);
        $queryObj->execute([$url]);
        $existingFollows = $queryObj->fetchAll();
    } catch (PDOException $pe) {
        return "database error";
    }
    foreach ($existingFollows as $followingRow) {
        $username = $followingRow["username"];
        if ($username != $author) {
            $updateStatement = "UPDATE following SET seen=? WHERE username=? AND url=?";
            try {
                $queryObj = $connection->prepare($updateStatement);
                $queryObj->execute([false, $username, $url]);
            } catch (PDOException $pe) {
                return "database error";
            }
        }
    }
}

?>