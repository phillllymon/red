<?php

include_once("actions/changePassword.php");
include_once("actions/checkForPosts.php");
include_once("actions/createPost.php");
include_once("actions/getPosts.php");
include_once("actions/giveFeedback.php");
include_once("actions/logIn.php");
include_once("actions/logOut.php");
include_once("actions/signUp.php");

include_once("helpers/connectToDatabase.php");

$availableActions = [
    "changePassword",   // need to test 7
    "checkForPosts",    // need to test 6
    "createPost",       // need to test 4
    "getPosts",         // need to test 5
    "giveFeedback",     // done
    "logIn",            // need to test 3
    "logOut",           // done
    "signUp"            // done
];

function executeAction($actionName, $inputs) {
    try{
        $connection = connectToDatabase();
    } catch (PDOException $pe) {
        $reply = new StdClass();
        $reply->status = "fail";
        $reply->message = "cannot connect to database";
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