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

$urls = [
    "https://www.google.com/",
    "https://seattle.craigslist.org/",
    "https://www.youtube.com/",
    "https://www.youtube.com/watch?v=OwG97do_Dpk",
    "https://seattle.craigslist.org/kit/boa/d/poulsbo-1927-wilmington-96-ft/7579298898.html",
    "https://seattle.craigslist.org/tac/boa/d/gig-harbor-2021-sea-ray-spx-190-ob/7582120276.html",
    "https://seattle.craigslist.org/tac/rts/d/mill-creek-hardwood-refinishing/7587905827.html",
    "https://www.facebook.com/",
    "https://www.facebook.com/heloise.bridault",
    "https://en.wikipedia.org/wiki/Main_Page",
    "https://en.wikipedia.org/wiki/Treaty_of_Paris_(1763)",
    "https://en.wikipedia.org/wiki/Moth_(dinghy)"
];

foreach ($urls as $example) {
    echo $example;
    echo "<br>";
    echo makePretty($example);
    echo "<br>";
    echo "<br>";
}

?>