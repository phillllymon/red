<?php
include_once("./helpers/checkForData.php");
include_once("./helpers/setErrorReply.php");
function getPreviews($connection, $inputs) {
    $reply = new stdClass();

    // urls is an array and can contain numbers(IDs) OR strings(webaddresses)
    // returns object with keys that match values given in array
    if (!checkForData($inputs, ["urls"])) {
        return setErrorReply("urls required");
    }

    $urls = json_decode($inputs->urls);
    $previews = new stdClass();

    foreach ($urls as $urlKey) {
        $index = "url";
        if (is_numeric($urlKey)) {
            $index = "id";
        }

        $getStatement = "SELECT * FROM urls WHERE {$index}=?";
        try {
            $queryObj = $connection->prepare($getStatement);
            $queryObj->execute([$urlKey]);
            $allUrlsFound = $queryObj->fetchAll();
        } catch (PDOException $pe) {
            return setErrorReply("database error");
        }
        if (count($allUrlsFound) == 1) {
            $urlRow = $allUrlsFound[0];
            $urlObj = new stdClass();
            if (isset($urlRow["preview"])) {
                $urlObj->preview = $urlRow["preview"];
            }
            $urlObj->id = $urlRow["id"];
            $urlObj->pretty = $urlRow["pretty"];
            $urlObj->url = $urlRow["url"];

            $previews->$urlKey = $urlObj;
        }

    }
    $reply->previews = $previews;

    return $reply;
}

?>