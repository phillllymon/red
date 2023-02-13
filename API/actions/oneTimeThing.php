<?php

/*
Instructions:
1. Uncomment/edit the code below to your liking.
2. Edit oneTimeThing.html (top level) to uncomment out the script.
3. Upload oneTimeThing.html, this file, and oneTimeThing.js (top level).
4. Visit graffiti.red/oneTimeThing - look in the console to see if it worked.
5. Verify action in other ways, maybe look at the tables in admin?
6. Make adjustments, upload again, repeat until it worked. Hopefully you don't screw anything up too badly.
7. Comment out code below.
8. Comment out script in oneTimeThing.html.
9. Upload oneTimeThing.html.
10. Delete oneTimeThing.js and this file from server. (For extra security)
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

    // $urls = new stdClass();

    // $getStatement = "SELECT * FROM urls";
    // try {
    //     $queryObj = $connection->prepare($getStatement);
    //     $queryObj->execute([]);
    //     $allUrls = $queryObj->fetchAll();
    // } catch (PDOException $pe) {
    //     return setErrorReply("database error getting all urls");
    // }

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
    //     if (!isset($usersFollowing->$username)) {
    //         $usersFollowing->$username = [];
    //     }
    //     $serializedFollowing = serialize($usersFollowing->$username);
    //     $updateStatement = "UPDATE users SET following=? WHERE username=?";
    //     try {
    //         $queryObj = $connection->prepare($updateStatement);
    //         $queryObj->execute([$serializedFollowing, $username]);
    //     } catch (PDOException $pe) {
    //         return setErrorReply("database error setting user following {$username}");
    //     }
    // }

    $reply->status = "success";
    $reply->message = "yay we did something!";
    return $reply;
}

?>