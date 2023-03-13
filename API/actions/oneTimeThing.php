<?php

/*
Instructions:
1. Uncomment/edit the code below to your liking.
2. Upload oneTimeThing.html and oneTimeThing.js (top level).
3. Upload this file to actions folder.
4. Visit graffiti.red/oneTimeThing - look in the console to see if it worked.
5. Verify action in other ways, maybe look at the tables in admin?
6. Make adjustments, upload again, repeat until it worked. Hopefully you don't screw anything up too badly.
7. Comment out code below.
8. Delete oneTimeThing.js, oneTimeThing.html, and this file from server. (For extra security)
*/

include_once("./helpers/setErrorReply.php");

include_once("./helpers/processUrl.php");
function oneTimeThing($connection, $inputs) {

    $reply = new stdClass();

    // UNCOMMENT ALL THIS AT YOUR OWN RISK!!!!!

    // // get allPosts
    // $getStatement = "SELECT * FROM posts";
    // try {
    //     $queryObj = $connection->prepare($getStatement);
    //     $queryObj->execute([]);
    //     $allPosts = $queryObj->fetchAll();
    // } catch (PDOException $pe) {
    //     return setErrorReply("database error");
    // }
    // // end $allPosts


    $getStatement = "SELECT * FROM urls";
    try {
        $queryObj = $connection->prepare($getStatement);
        $queryObj->execute([]);
        $allUrls = $queryObj->fetchAll();
    } catch (PDOException $pe) {
        return setErrorReply("database error getting all urls");
    }

    $num = 0;
    $updated = 0;
    $statuses = [];

    foreach($allUrls as $url) {
        if (!$url["preview"]) {
            $num++;

            $target = urlencode($url["url"]);
            $key = "1f6a67dccd0d0a62be891ebe9a9618da";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://api.linkpreview.net?key={$key}&q={$target}");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $output = json_decode(curl_exec($ch));
            $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($status == 200) {
                $updateStatement = "UPDATE urls SET preview=? WHERE url=?";
                try {
                    $queryObj = $connection->prepare($updateStatement);
                    $queryObj->execute([serialize(serialize($output)), $url["url"]]);
                } catch (PDOException $pe) {
                    return setErrorReply("database error");
                }
                $updated++;
            } else {
                array_push($statuses, $status);
            }
        }

        
    }
    $reply->statuses = $statuses;
    $reply->updated = $updated;
    $reply->num = $num;

    // $reply->urls = $allUrls;



    // $usersFollowing = new stdClass();

    // foreach ($allUrls as $urlRow) {
    //     $followers = unserialize($urlRow["followers"]);

    //     foreach($followers as $username) {
    //         if (!isset($usersFollowing->$username)) {
    //             $usersFollowing->$username = [];
    //         }
    //         array_push($usersFollowing->$username, [$urlRow["url"], true]);
    //     }
    // }

    // $reply->usersFollowing = $usersFollowing;

    // $getStatement = "SELECT * FROM users";
    // try {
    //     $queryObj = $connection->prepare($getStatement);
    //     $queryObj->execute([]);
    //     $allUsers = $queryObj->fetchAll();
    // } catch (PDOException $pe) {
    //     return setErrorReply("database error getting all users");
    // }

    // foreach ($allUsers as $userRow) {
    //     $username = $userRow["username"];
    //     $newArray = [];
    //     $updateStatement = "UPDATE users SET unfollowTokens=? WHERE username=?";
    //     try {
    //         $queryObj = $connection->prepare($updateStatement);
    //         $queryObj->execute([serialize($newArray), $username]);
    //     } catch (PDOException $pe) {
    //         return setErrorReply("database error");
    //     }
    // }

    $reply->status = "success";
    $reply->message = "yay we did something!";
    return $reply;
}

?>