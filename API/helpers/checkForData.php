<?php

function checkForData($inputs, $requiredKeys) {
    foreach($requiredKeys as $key) {
        if (!isset($inputs->{$key})) {
            return false;
        }
    }
    return true;
}

?>