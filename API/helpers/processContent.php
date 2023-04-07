<?php


// TODO: one big processContant function that only iterates through it once

/*
1. deletes disallowed or fragmented html
2. puts spaces around allowed html (links & tags)
3. makes links out of things that look like links
4. makes target=false for existing links
*/

// function processContent($content) {
//     $chars = explode("", $content);

//     $currentWord = "";
//     $couldBeUrl = true;
//     $notUrl = false;
//     $inTag = false;

//     $lastChar = null;

//     for ($i = 0; $i < count($chars); $i++) {
//         $thisChar = $chars[$i];
//         if ($thisChar == " ") {

//             // do what you need with completed word
//             // Are we between tags?

//             $currentWord = "";

//         }


//         $currentWord = $currentWord.$thisChar;


        
//         // process word here
//     }
    
// }

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
    $parts = explode(".", $str);
    if (count($parts) == 2) {
        if (implode("", explode(" ", $str)) == $str) {
            if (implode("", explode("..", $str)) == $str) {
                if (!is_numeric($str)) {
                    if (substr($str, -1) != ".") {
                        return true;
                    }
                }
            }
        }
    }
    return false;
}

?>