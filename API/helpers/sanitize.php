<?php

function sanitizeAll($data) {
    $exceptions = new stdClass();
    $exceptions->start = "integer";
    $exceptions->limit = "integer";

    $answer = new stdClass();
    if (!isset($data)) {
        return $answer;
    }

    foreach($data as $key => $value) {
        if (isset($exceptions->{$key}) && gettype($value) == $exceptions->$key) {
            $answer->{$key} = $value;
        } else {
            if (gettype($key) == "string" && gettype($value) == "string") {
                $answer->{purgeBadChars($key)} = purgeBadChars($value);
            } else {
                $answer->error = "ERROR: non-string data received";
            }
        }
    }

    return $answer;
}

function purgeBadChars($str) {
    $badChars = [
        "{",
        "}",
        ";"
    ];

    $newStr = $str;

    foreach($badChars as $char) {
        $newStr = implode("", explode($char, $newStr));
    }

    return $newStr;
}

?>