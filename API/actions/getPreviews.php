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
                // $urlObj->preview = $urlRow["preview"];
                $urlObj->preview = unserialize($urlRow["preview"]);

                // test only
                $reply->title = unserialize($urlRow["preview"])->title;
                $reply->image = unserialize($urlRow["preview"])->image;

                $target = urlencode($urlKey);
                $key = "1f6a67dccd0d0a62be891ebe9a9618da";

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "https://api.linkpreview.net?key={$key}&q={$target}");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $output = json_decode(curl_exec($ch));
                $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                $reply->test = $output;

                // end test
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