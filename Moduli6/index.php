<?php

// $my_file = fopen("ds.txt", "w") ;

$my_file = fopen("ds.txt", "w+r") ;

// while(!feof($my_file)){
//     echo fgets($my_file)."<br>";
// }

// fclose($my_file);

$text = "This is a new line of text.";

fwrite($my_file, $text);

$my_file = fopen("ds.txt", "r") ;

while(!feof($my_file)){
    echo fgets($my_file)."<br>";
}


?>