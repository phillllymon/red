<?php
include_once("./helpers/checkForData.php");
include_once("./helpers/setErrorReply.php");

function notifyTaggedUsers($connection, $inputs) {

    $reply = new stdClass();

    if (!checkForData($inputs, ["url", "author", "tags"])) {
        return setErrorReply("url, author, and tags required");
    }

    $tags = json_decode($inputs->tags);
    foreach ($tags as $user) {
        $getStatement = "SELECT * FROM users WHERE username=?";
        try {
            $queryObj = $connection->prepare($getStatement);
            $queryObj->execute([$user]);
            $existingUsers = $queryObj->fetchAll();
        } catch (PDOException $pe) {
            return setErrorReply("database error");
        }

        if (count($existingUsers) == 1) {
            $email = $existingUsers[0]["email"];

            $to      = $email;
            $subject = "You've been tagged in a GRAFFITI post";

            // Note: We intentionally do not processUrl here in case the poster has relevent variables set - the tagged user will be taken
            // to EXACTLY the same url - the post is still stored under the processed url.
            $message = "hello {$user}:\n\n
            {$inputs->author} recently tagged you in a post.\n
            Open GRAFFITI on the following url to see the post where you've been tagged:\n
            {$inputs->url}\n\n
            Cheers,\n
            GRAFFITI team";
            $headers = "From: notifications@graffiti.red" . "\r\n" .
            "Reply-To: info@graffiti.red" . "\r\n" .
            "X-Mailer: PHP/" . phpversion();

            mail($to, $subject, $message, $headers);

        }
    }

    $reply->status = "success";
    $reply->message = "email notifcations attempted";
    return $reply;
}

?>