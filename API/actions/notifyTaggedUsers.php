<?php
include_once("./helpers/checkForData.php");
include_once("./helpers/setErrorReply.php");
include_once("./helpers/followUrl.php");
include_once("./helpers/processUrl.php");
include_once("./helpers/sendEmail.php");
function notifyTaggedUsers($connection, $inputs) {


    // !!!!!!!!!!!!!!! janky hack to lessen chance that previous request(where we posted the post) will fuck with this
    sleep(20);
    // TODO: in next version of graffiti - combine notify followers and notify tags



    $reply = new stdClass();

    if (!checkForData($inputs, ["url", "author", "tags"])) {
        return setErrorReply("url, author, and tags required");
    }

    $tags = json_decode($inputs->tags);


    $emailRecipients = [];
    $emailSubjects = [];
    $emailContents = [];

    foreach ($tags as $user) {

        followUrl($user, processUrl($inputs->url), $connection);

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
            $subject = "You've been tagged in a GRAFFITI post";

            // Note: We intentionally do not processUrl here in case the poster has relevent variables set - the tagged user will be taken
            // to EXACTLY the same url - the post is still stored under the processed url.
            // $message = "hello {$user}:\n\n
            // {$inputs->author} recently tagged you in a post.\n
            // Open GRAFFITI on the following url to see the post where you've been tagged:\n
            // {$inputs->url}\n\n
            // Cheers,\n
            // GRAFFITI team";

            $message = "Hello {$user}:\n
            {$inputs->author} recently tagged you in a post on the following url:\n 
            {$inputs->url}\n\nCheers,\n GRAFFITI team";

            array_push($emailRecipients, $email);
            array_push($emailSubjects, $subject);
            array_push($emailContents, $message);

        }
    }
    sendEmail($emailRecipients, $emailContents, $emailSubjects);

    $reply->status = "success";
    $reply->message = "email notifcations attempted";
    return $reply;
}

?>