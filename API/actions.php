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

function executeAction($actionName, $inputs) {
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
            return changePassword($connection, $inputs);
        case "checkForPosts":
            return checkForPosts($connection, $inputs);    
        case "createPost":
            return createPost($connection, $inputs);
        case "getPosts":
            return getPosts($connection, $inputs);
        case "giveFeedback":
            return giveFeedback($connection, $inputs);
        case "logIn":
            return logIn($connection, $inputs);
        case "logOut":
            return logOut($connection, $inputs);
        case "signUp":
            return signUp($connection, $inputs);
    }
}

?>