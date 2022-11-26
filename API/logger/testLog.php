<?php

$myfile = fopen("newfile.txt", "a") or die("Unable to open file!");

$txt = "Donald Duck\n";
fwrite($myfile, $txt);

fclose($myFile);

?>