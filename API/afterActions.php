<?php

include_once("helpers/connectToDatabase.php");
include_once("helpers/processUrl.php");
function followUp($actionName, $inputs) {
    
    $connection = connectToDatabase();

    if ($actionName === "createPost") {

        $goodUrl = processUrl($inputs->url);

        // add as follower to this url
        $getStatement = "SELECT * FROM urls WHERE url=?";
        $queryObj = $connection->prepare($getStatement);
        $queryObj->execute([$goodUrl]);
        $existing = $queryObj->fetchAll();
        if (count($existing) < 1) {
            $newArr = [];
            array_push($newArr, $inputs->username);
            $insertStatement = "INSERT INTO urls (url, pretty, followers) VALUES (?, ?, ?)";
            $insertObj = $connection->prepare($insertStatement);
            // TODO: make pretty input (instead of just copying url)
            $insertObj->execute([$goodUrl, $goodUrl, serialize($newArr)]);
        } else {
            $followers = unserialize($existing[0]["followers"]);
            if (!in_array($inputs->username, $followers)) {
                array_push($followers, $inputs->username);
                $updateStatement = "UPDATE urls SET followers=? WHERE url=?";
                $updateObj = $connection->prepare($updateStatement);
                $updateObj->execute([serialize($followers), $goodUrl]);
            }

            // notify authors that the conversation has continued
            foreach($followers as $follower) {
                if ($follower != $inputs->username) {
                    $getStatement = "SELECT * FROM users WHERE username=?";
                    $getObj = $connection->prepare($getStatement);
                    $getObj->execute([$follower]);
                    $existingUsers = $getObj->fetchAll();
                    if (count($existingUsers) == 1) {
                        $email = $existingUsers[0]["email"];

                        $to      = $email;
                        $subject = "GRAFFITI reply from {$inputs->username}";
                        $message = "hello {$follower}:\n\n
                        {$inputs->username} recently added to your GRAFFITI conversation.\n
                        Open GRAFFITI on the following url to see the new post:\n
                        {$goodUrl}\n\n
                        Cheers,\n
                        GRAFFITI team\n\n
                        Unfollow the conversation here: https://graffiti.red/unfollow.php?username={$follower}&url={$inputs->url}";
                        $headers = "From: notifications@graffiti.red" . "\r\n" .
                        "Reply-To: info@graffiti.red" . "\r\n" .
                        "X-Mailer: PHP/" . phpversion();

                        mail($to, $subject, $message, $headers);
                    }
                }
            }
        }
    }
}

?>