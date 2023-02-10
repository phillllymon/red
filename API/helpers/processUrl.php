<?php

function begins_with($str, $sub) {
    if (empty(explode($sub, $str)[0])) {
        return true;
    } else {
        return false;
    }
}

function str_has($str, $sub) {
    if (explode($sub, $str)[0] == $str) {
        return false;
    } else {
        return true;
    }
}

// Reduces some redundent urls - mostly we want to ignore params after "?" but for youtube videos the video id is passed as a url param.
// Likely this function will become more complicated as we treat more and more large websites as special cases
// !!!!!!!! If you change this function, YOU MUST APPLY IT TO ALL CURRENT SAVED URLS !!!!!!!!! Otherwise existing posts will be lost
function processUrl($url) {

    $keepParams = [
        "https://www.youtube.com"
    ];

    foreach ($keepParams as $domain) {
        if (begins_with($url, $domain)) {
            return $url;
        }
    }
    
    $pieces = explode("?", $url);
    return $pieces[0];
}

function trimBoilerPlate($url) {
    $current = $url;
    if (begins_with($current, "http://")) {
        $current = substr($current, 7);
        // $current = explode("http://", $url)[1];
        // $current = trim("http://");
    }
    if (begins_with($current, "https://")) {
        $current = substr($current, 8);
        // $current = explode("https://", $url)[1];
        // $current = trim("https://");
    }
    if (begins_with($current, "www.")) {
        $current = substr($current, 4);
        // $current = explode("www.", $url)[1];
        // $current = trim("www.");
    }
    if ($current[-1] == "/") {
        $current = substr($current, 0, -1);
    }

    return $current;
}
function makePrettySpecial($url, $case) {
    $current = trimBoilerPlate($url);
    if ($case == ".craigslist.org") {
        if ($url[-1] != "/") {
            $parts = explode("/", $current);
            $ad = $parts[count($parts) - 2];
            $current = $ad;
        }
    }
    if ($case == ".youtube.com/watch") {
        $video = explode("youtube.com/watch?v=", $url)[1];
        $current = "youtube/{$video}";
    }
    if ($case == ".facebook.com") {
        if ($url[-1] == "/") {
            $current = "facebook.com";
        } else {
            $user = explode("facebook.com/", $url)[1];
            $current = "facebook/{$user}";
        }
    }
    if ($case == ".wikipedia.org/wiki/") {
        $article = explode("/wiki/", $url)[1];
        $current = "wikipedia/{$article}";
    }
    return $current;
}
function makePretty($url) {
    $specialCases = [
        ".craigslist.org",
        ".youtube.com/watch",
        ".facebook.com",
        ".wikipedia.org/wiki/"
    ];

    foreach ($specialCases as $case) {
        if (str_has($url, $case)) {
            return makePrettySpecial($url, $case);
        }
    }

    return trimBoilerPlate($url);
}

?>