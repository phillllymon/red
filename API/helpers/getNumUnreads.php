<?php

function getNumUnreads($username, $connection) {
    $getStatement = "SELECT * FROM following WHERE username=?";
    try {
        $queryObj = $connection->prepare($getStatement);
        $queryObj->execute([$username]);
        $allFollowing = $queryObj->fetchAll();
    } catch (PDOException $pe) {
        return "database error";
    }
    $numUnreads = 0;
    foreach ($allFollowing as $follow) {
        if ($follow["seen"] == false) {
            $numUnreads++;
        }
    }

    return $numUnreads;
}

?>