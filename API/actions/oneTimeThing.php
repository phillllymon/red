<?php

include_once("./helpers/setErrorReply.php");
function oneTimeThing($connection, $inputs) {

    $reply = new stdClass();

    // UNCOMMENT ALL THIS AT YOUR OWN RISK!!!!!

    // $getStatement = "SELECT * FROM posts";
    // try {
    //     $queryObj = $connection->prepare($getStatement);
    //     $queryObj->execute([$inputs->url]);
    //     $allPosts = $queryObj->fetchAll();
    // } catch (PDOException $pe) {
    //     return setErrorReply("database error");
    // }

    // $urls = new stdClass();

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
    $reply->status = "success";
    $reply->message = "yay we did something!";
    return $reply;
}

?>