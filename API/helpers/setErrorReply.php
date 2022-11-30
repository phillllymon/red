<?php

function setErrorReply($message) {
    $answer = new stdClass();
    $answer->status = "fail";
    $answer->message = "ERROR ".$message;
    return $answer;
}

?>