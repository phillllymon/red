<?php

function processContent($str) {
    $words = explode(" ", $str);
    for ($i = 0; $i < count($words); $i++) {
        if (looksLikeUrl($words[$i])) {
            $words[$i] = makeLink($words[$i]);
        }
    }

    return implode(" ", $words);
}

function makeLink($str) {
    if (implode("", explode("https://", $str)) == $str) {
        return '<a href='.'https://'.$str.' target="false">'.$str.'</a>';
    } else {
        return '<a href='.$str.' target="false">'.$str.'</a>';
    }
}

function looksLikeUrl($str) {
    if (implode("", explode(".", $str)) != $str) {
        if (implode("", explode(" ", $str)) == $str) {
            if (!is_numeric($str)) {
                return true;
            }
        }
    }
    return false;
}

?>