<?php

$sports = 

["Football","Tennis","Basketball","Baseball","Hockey"];

echo $sports[3]."<br>";

echo end($sports)."<br>";

echo count($sports)."<br>";



for($count = 0; $count < 5; $count++){
    echo $sports[$count]."<br>";
}

array_push($sports,"Golf");

for($count = 0; $count < 6; $count++){
    echo $sports[$count]."<br>";
}

// array_pop($sports);

// var_dump($sports);

// array_unshift($sports,"Cricket");

// var_dump($sports);

// array_shift($sports);

// var_dump($sports);

$numbers = [1,2,3,4,5,6,7,8,9];

$mbledhja = array_sum($numbers);

echo $mbledhja."<br>";


?>