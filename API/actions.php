<?php

include_once("actions/changePassword.php");
include_once("actions/checkForPosts.php");
include_once("actions/createPost.php");
include_once("actions/getPosts.php");
include_once("actions/giveFeedback.php");
include_once("actions/logIn.php");
include_once("actions/logOut.php");
include_once("actions/signUp.php");
include_once("actions/checkLogin.php");
include_once("actions/resetPassword.php");
include_once("actions/changeAvatar.php");
include_once("actions/deleteAccount.php");
include_once("actions/checkForUser.php");

include_once("helpers/connectToDatabase.php");

$availableActions = [
    "changePassword",   // done
    "checkForPosts",    // done
    "createPost",       // done
    "getPosts",         // done
    "giveFeedback",     // done
    "logIn",            // done
    "logOut",           // done
    "signUp",           // done
    "checkLogin",       // done
    "resetPassword",    // done
    "changeAvatar",     // done
    "deleteAccount",    // done
    "checkForUser"      // TODO
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
        case "checkLogin":
            return checkLogin($connection, $inputs);
        case "resetPassword":
            return resetPassword($connection, $inputs);
        case "changeAvatar":
            return changeAvatar($connection, $inputs);
        case "deleteAccount":
            return deleteAccount($connection, $inputs);
        case "checkForUser":
            return checkForUser($connection, $inputs);
    }
}

?>