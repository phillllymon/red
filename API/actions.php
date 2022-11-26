<?php

require "actions/changePassword.php";
require "actions/checkForPosts.php";
require "actions/createPost.php";
require "actions/getPosts.php";
require "actions/giveFeedback.php";
require "actions/logIn.php";
require "actions/logOut.php";
require "actions/signUp.php";

require "helpers/connectToDatabase.php";

$availableActions = [
    "changePassword",
    "checkForPosts",
    "createPost",
    "getPosts",
    "giveFeedback",
    "logIn",
    "logOut",
    "signUp"
];

function executeAction($actionName) {
    try{
        $connection = connectToDatabase();
    } catch (PDOException $pe) {
        $reply = new StdClass();
        $reply->status = "fail";
        $reply->message = "databse error";
        return $reply;
    }
    switch ($actionName) {
        case "changePassword":
            return changePassword($connection);
        case "checkForPosts":
            return checkForPosts($connection);    
        case "createPost":
            return createPost($connection);
        case "getPosts":
            return getPosts($connection);
        case "giveFeedback":
            return giveFeedback($connection);
        case "logIn":
            return logIn($connection);
        case "logOut":
            return logOut($connection);
        case "signUp":
            return signUp($connection);
    }
}

?>