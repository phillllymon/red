<?php

include_once("helpers/connectToDatabase.php");
include_once("helpers/processUrl.php");
include_once("helpers/updateUrlTimestamp.php");
include_once("helpers/followUrl.php");
include_once("helpers/notifyFollowers.php");
include_once("helpers/markUrlAsUnread.php");
function followUp($actionName, $inputs) {
    
    $connection = connectToDatabase();

    if ($actionName === "getPosts") {
        /*
        not using helper because this is only place we mark as read
        */

        if (isset($inputs->username) && isset($inputs->token)) {

            $getStatement = "SELECT * FROM users WHERE username=?";
            $queryObj = $connection->prepare($getStatement);
            $queryObj->execute([$inputs->username]);
            $existingUsers = $queryObj->fetchAll();
            
            if (count($existingUsers) == 1) {

                $userRow = $existingUsers[0];
                if (comparePasswordAgainstHash($inputs->token, $userRow["token"])) {

                    $goodUrl = processUrl($inputs->url);
                    $updateStatement = "UPDATE following SET seen=? WHERE username=? AND url=?";
                    $queryObj = $connection->prepare($updateStatement);
                    $queryObj->execute([true, $inputs->username, $goodUrl]);
                    
                }
            }
        }
    }

    if ($actionName === "createPost") {
        /*
        add this url to the urls poster is following
        set seen false for all other users who follow this url
        notify all users who follow this url
        */

        
        $goodUrl = processUrl($inputs->url);
        // 1. Update timestamp on this url
        updateUrlTimestamp($goodUrl, $connection);
        // 2. Mark url as unread for followers
        markUrlAsUnread($goodUrl, $inputs->username, $connection);
        // 3. Make sure author is following this url
        followUrl($inputs->username, $goodUrl, $connection);
        // 4. Notify other followers that this url has new post
        notifyFollowers($goodUrl, $inputs->username, $connection);
        

    }
}

?>