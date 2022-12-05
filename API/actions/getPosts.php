<?php
include_once("./helpers/checkForData.php");
include_once("./helpers/setErrorReply.php");
function getPosts($connection, $inputs) {
    $reply = new stdClass();

    if (!checkForData($inputs, ["url"])) {
        return setErrorReply("url required");
    }

    $skip = isset($inputs->skip) ? $inputs->skip : 0;
    $limit = isset($inputs->limit) ? $inputs->limit : 1000;

    $getStatement = "SELECT * FROM posts WHERE url=? ORDER BY created desc LIMIT {$limit} OFFSET {$skip}";
    try {
        $queryObj = $connection->prepare($getStatement);
        $queryObj->execute([$inputs->url]);
        $reply->status = "success";
        $reply->message = "posts fetched";
        $posts = $queryObj->fetchAll();
        $reply->posts = $posts;
    } catch (PDOException $pe) {
        return setErrorReply("database error");
    }

    // get avatars for all the posts you're about to return
    $avatars = new stdClass();
    try {
        for ($i = 0; $i < count($posts); $i++) {
            $post = $posts[$i];
            if (!isset($avatars->{$post["username"]})) {
                // $avatars->{$post["username"]} = "poopyhead";

                $getAvatarStatement = "SELECT * FROM users WHERE username=?";
                $queryObj = $connection->prepare($getAvatarStatement);
                $queryObj->execute([$post["username"]]);
                $authors = $queryObj->fetchAll();

                if (count($authors) > 0) {
                    $avatars->{$post["username"]} = $authors[0]["avatar"];
                } else {
                    $avatars->{$post["username"]} = "default";
                }
            }
        }
    } catch (PDOException $pe) {
        $reply->message = "error getting post authors";
    }
    
    $reply->avatars = $avatars;
    return $reply;
}

?>