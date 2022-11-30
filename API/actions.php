<?php

// require "actions/changePassword.php";
// require "actions/checkForPosts.php";
// require "actions/createPost.php";
// require "actions/getPosts.php";
// require "actions/giveFeedback.php";
// require "actions/logIn.php";
require "actions/logOut.php";
require "actions/signUp.php";

require "helpers/connectToDatabase.php";

$availableActions = [
    "changePassword",   // need to test 7
    "checkForPosts",    // need to test 6
    "createPost",       // need to test 4
    "getPosts",         // need to test 5
    "giveFeedback",     // done
    "logIn",            // need to test 3
    "logOut",           // need to test 2
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
            // return changePassword($connection, $inputs);
        case "checkForPosts":
            // return checkForPosts($connection, $inputs);    
        case "createPost":
            // return createPost($connection, $inputs);
        case "getPosts":
            // return getPosts($connection, $inputs);
        case "giveFeedback":
            // return giveFeedback($connection, $inputs);
        case "logIn":
            // return logIn($connection, $inputs);
        // case "logOut":
        //     return logOut($connection, $inputs);
        case "signUp":
            return signUp($connection, $inputs);
    }
}

?>