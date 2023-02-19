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

                    // NEW here -------------------
                    $goodUrl = processUrl($inputs->url);
                    $updateStatement = "UPDATE following SET seen=? WHERE username=? AND url=?";
                    $queryObj = $connection->prepare($updateStatement);
                    $queryObj->execute([true, $inputs->username, $goodUrl]);
                    // END new --------------------

                    // $goodUrl = processUrl($inputs->url);
                    // $following = unserialize($userRow["following"]);
                    // $idx = null;
                    // for ($i = 0; $i < count($following); $i++) {
                    //     if ($goodUrl == $following[$i][0]) {
                    //         $idx = $i;
                    //         break;
                    //     }
                    // }
                    // if ($idx != null) {
                    //     $following[$idx][1] = true;

                    //     $updateStatement = "UPDATE users SET following=? WHERE username=?";
                    //     $queryObj = $connection->prepare($updateStatement);
                    //     $queryObj->execute([serialize($following), $inputs->username]);
                    // }
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

        // new here --------------------
        $goodUrl = processUrl($inputs->url);
        // 1. Update timestamp on this url
        updateUrlTimestamp($goodUrl, $connection);
        // 2. Mark url as unread for followers
        markUrlAsUnread($goodUrl, $inputs->username, $connection);
        // 3. Make sure author is following this url
        followUrl($inputs->username, $goodUrl, $connection);
        // 4. Notify other followers that this url has new post
        notifyFollowers($goodUrl, $inputs->username, $connection);
        // end new ---------------------

        // // $goodUrl = processUrl($inputs->url);
        // $prettyUrl = makePretty($inputs->url);

        // // add url to urls this user is following in users table
        // $getStatement = "SELECT * FROM users WHERE username=?";
        // $queryObj = $connection->prepare($getStatement);
        // $queryObj->execute([$inputs->username]);
        // $users = $queryObj->fetchAll();

        // $userRow = $users[0];
        // $following = unserialize($userRow["following"]);
        // $existingIdx = 0; // only used if we're already following
        // $alreadyFollowing = false;
        // for ($i = 0; $i < count($following); $i++) {
        //     $thisUrlRow = $following[$i];
        //     if ($thisUrlRow[0] == $goodUrl) {
        //         $alreadyFollowing = true;
        //         $existingIdx = $i;
        //         break;
        //     }
        // }

        // if ($alreadyFollowing) {
        //     // puts at end and makes sure seen is set to true
        //     array_splice($following, $existingIdx, 1);
        //     array_push($following, [$goodUrl, true]);
        // } else {
        //     array_push($following, [$goodUrl, true]);
        // }

        // // TODO: decide if this is a good limit.....or redesign this whole system
        // if (count($following) > 50) {
        //     array_splice($following, 0, count($following) - 50);
        // }

        // $serializedFollowing = serialize($following);

        // $updateStatement = "UPDATE users SET following=? WHERE username=?";
        // $queryObj = $connection->prepare($updateStatement);
        // $queryObj->execute([$serializedFollowing, $inputs->username]);

        // // add as follower to this url in urls table
        // $getStatement = "SELECT * FROM urls WHERE url=?";
        // $queryObj = $connection->prepare($getStatement);
        // $queryObj->execute([$goodUrl]);
        // $existing = $queryObj->fetchAll();
        // if (count($existing) < 1) {
        //     $newArr = [];
        //     array_push($newArr, $inputs->username);
        //     $insertStatement = "INSERT INTO urls (url, pretty, followers) VALUES (?, ?, ?)";
        //     $insertObj = $connection->prepare($insertStatement);
        //     $insertObj->execute([$goodUrl, $prettyUrl, serialize($newArr)]);
        // } else {
        //     $followers = unserialize($existing[0]["followers"]);
        //     if (!in_array($inputs->username, $followers)) {
        //         array_push($followers, $inputs->username);
        //         $updateStatement = "UPDATE urls SET followers=? WHERE url=?";
        //         $updateObj = $connection->prepare($updateStatement);
        //         $updateObj->execute([serialize($followers), $goodUrl]);
        //     }

        //     // set as unread for followers and followers that the conversation has continued
        //     foreach($followers as $follower) {
        //         if ($follower != $inputs->username) {
        //             $getStatement = "SELECT * FROM users WHERE username=?";
        //             $getObj = $connection->prepare($getStatement);
        //             $getObj->execute([$follower]);
        //             $existingUsers = $getObj->fetchAll();
        //             if (count($existingUsers) == 1) {
        //                 $userRow = $existingUsers[0];

        //                 // mark as unread
        //                 $pagesUserFollows = unserialize($userRow["following"]);
        //                 $idx = null;
        //                 for ($i = 0; $i < count($pagesUserFollows); $i++) {
        //                     if ($pagesUserFollows[$i][0] == $goodUrl) {
        //                         $idx = $i;
        //                         break;
        //                     }
        //                 }
        //                 if ($idx != null) {
        //                     array_splice($pagesUserFollows, $idx, 1);
        //                     array_push($pagesUserFollows, [$goodUrl, false]);

        //                     $updateStatement = "UPDATE users SET following=? WHERE username=?";
        //                     $queryObj = $connection->prepare($updateStatement);
        //                     $queryObj->execute([serialize($pagesUserFollows), $userRow["username"]]);
        //                 }

        //                 // email notification
        //                 $email = $userRow["email"];

        //                 $to      = $email;
        //                 $subject = "GRAFFITI reply from {$inputs->username}";
        //                 $message = 
        //                 "hello {$follower}:\n\n
        //                 {$inputs->username} recently added to your GRAFFITI conversation.\n
        //                 Open GRAFFITI on the following url to see the new post:\n
        //                 {$goodUrl}\n\n
        //                 Cheers,\n
        //                 GRAFFITI team\n\n
        //                 Unfollow the conversation here: https://graffiti.red/unfollow.php?username={$follower}&url={$inputs->url}";
        //                 $headers = "From: notifications@graffiti.red" . "\r\n" .
        //                 "Reply-To: info@graffiti.red" . "\r\n" .
        //                 "X-Mailer: PHP/" . phpversion();

        //                 mail($to, $subject, $message, $headers);
        //             }
        //         }
        //     }
        // }
    }
}

?>