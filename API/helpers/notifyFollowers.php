<?php

include_once("secretManager.php");
include_once("sendEmail.php");
function notifyFollowers($url, $author, $connection) {

    sendEmail($emailRecipients, $emailContents, $emailSubjects);
    
    $getStatement = "SELECT * FROM following WHERE url=?";
    try {
        $queryObj = $connection->prepare($getStatement);
        $queryObj->execute([$url]);
        $existingFollows = $queryObj->fetchAll();
    } catch (PDOException $pe) {
        return "database error";
    }

    $emailRecipients = [];
    $emailSubjects = [];
    $emailContents = [];

    foreach ($existingFollows as $followingRow) {
        $username = $followingRow["username"];

        if ($username != $author) {
            $getStatement = "SELECT * FROM users WHERE username=?";
            try {
                $queryObj = $connection->prepare($getStatement);
                $queryObj->execute([$username]);
                $existingUsers = $queryObj->fetchAll();
            } catch (PDOException $pe) {
                return "database error";
            }

            if (count($existingUsers) == 1) {
                $userRow = $existingUsers[0];
                $unfollowTokens = unserialize($userRow["unfollowTokens"]);
                $newToken = generateRandomToken(10);
                array_push($unfollowTokens, $newToken);

                if (count($unfollowTokens) > 5) {
                    array_splice($unfollowTokens, 0, 1);
                }

                $updateStatement = "UPDATE users SET unfollowTokens=? WHERE username=?";
                try {
                    $queryObj = $connection->prepare($updateStatement);
                    $queryObj->execute([serialize($unfollowTokens), $username]);
                } catch (PDOException $pe) {
                    return setErrorReply("database error");
                }

                $email = $userRow["email"];
                $subject = "GRAFFITI reply from {$author}";
                $message =
                "hello {$username}:\n\n
                {$author} recently added to your GRAFFITI conversation.\n
                Open GRAFFITI on the following url to see the new post:\n
                {$url}\n\n
                Cheers,\n
                GRAFFITI team\n\n
                Unfollow the conversation here: https://graffiti.red/unfollow.php?username={$username}&url={$url}&token={$newToken}";

                // $message = "{$author} has added to your conversation";

                array_push($emailRecipients, $email);
                array_push($emailSubjects, $subject);
                array_push($emailContents, $message);

            }
        }
        
    }
    sendEmail($emailRecipients, $emailContents, $emailSubjects);

}

?>