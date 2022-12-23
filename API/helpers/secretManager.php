<?php

function createPasswordHash($pass) {
    return password_hash($pass, PASSWORD_BCRYPT);
}

function comparePasswordAgainstHash($pass, $hash) {
    return password_verify($pass, $hash);
}

function generateRandomToken($length = 50) {
    $validChars = ["a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m",
    "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z", "A", "B", "C",
    "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S",
    "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2", "3", "4", "5", "6", "7", "8",
    "9", "!", "@", "#", "%", "&", "(", ")"];

    $numChars = count($validChars);
    $newToken = "";
    for ($i = 0; $i < $length; $i++) {
        $newToken = $newToken.$validChars[rand(0, $numChars - 1)];
    }

    return $newToken;
}

?>