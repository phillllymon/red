<?php

function updateUrlTimestamp($url, $connection) {
    $updateStatement = "UPDATE urls SET modified=now() WHERE url=?";
    try {
        $queryObj = $connection->prepare($updateStatement);
        $queryObj->execute([$url]);
    } catch (PDOException $pe) {
        return "error updating url time";
    }
    return "success";
}

?>