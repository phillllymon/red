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
9. Upload oneTimeThing.html and this file.
10. Delete oneTimeThing.js from server. (For extra security)
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
    //     $queryObj->execute([$inputs->url]);
    //     $allPosts = $queryObj->fetchAll();
    // } catch (PDOException $pe) {
    //     return setErrorReply("database error");
    // }
    // // end $allPosts

    // $urls = new stdClass();

    // $getStatement = "SELECT * FROM urls";
    // try {
    //     $queryObj = $connection->prepare($getStatement);
    //     $queryObj->execute([$inputs->url]);
    //     $allUrls = $queryObj->fetchAll();
    // } catch (PDOException $pe) {
    //     return setErrorReply("database error");
    // }

    // foreach ($allUrls as $urlRow) {
    //     $url = $urlRow["url"];
    //     $pretty = makePretty($url);

    //     $updateStatement = "UPDATE urls SET pretty=? WHERE url=?";
    //     try {
    //         $queryObj = $connection->prepare($updateStatement);
    //         $queryObj->execute([$pretty, $url]);
    //     } catch (PDOException $pe) {
    //         return setErrorReply("database error");
    //     }

    // }

    // foreach($allPosts as $post) {
    //     $url = $post["url"];
    //     $author = $post["username"];
    //     if (!isset($urls->$url)) {
    //         $urls->$url = [];
    //     }
    //     if (!in_array($author, $urls->$url)) {
    //         array_push($urls->$url, $author);
    //     }
        
    // }

    // foreach($urls as $page => $users) {


    //     $insertStatement = "INSERT INTO urls (url, pretty, followers) VALUES (?, ?, ?)";
    //     try {
    //         $queryObj = $connection->prepare($insertStatement);
    //         $queryObj->execute([$page, $page, serialize($users)]);
    //     } catch (PDOException $pe) {
    //         return setErrorReply("database error");
    //     }
    // }

    // $reply->data = json_encode($urls);
    // $reply->status = "success";
    // $reply->message = "yay we did something!";
    return $reply;
}

?>