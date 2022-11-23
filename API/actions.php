<?php

require "actions/createUser.php";
require "actions/createPost.php";

$availableActions = [
    "createUser",
    "createPost"
];

function executeAction($actionName) {
    switch ($actionName) {
        case "createUser":
            return createUser();
        case "createPost":
            return createPost();
    }
}

?>