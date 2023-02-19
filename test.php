<?php


    $success = mail("itsabitrunnysir@gmail.com", "test_subject", "email body");

    if ($success) {
        echo "yes";
    } else {
        echo "no";
    }

?>