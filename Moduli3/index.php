<?php

$var = 10;

if ($var > 5) {
    echo "$var is greater than 5.<hr>";
} 

$age = 16;

if($age>=18){
    echo "You can vote.<hr>";
}else{
   echo "you can't vote.<hr>";
}

$a = 50;
$b =10;

if($a==$b){
echo "a is equal to b <hr>";
}else if($a > $b){
echo "a is greater then b <hr>";
}else{
    echo "a is less than b <hr>";
}

switch($age){
    case ($age >=0 && $age<18):
        echo "You are a minor <hr>";
        break;
    case ($age>=18 && $age<=25):
        echo "You are a young adult <hr>";
        break;
    case ($age>25 && $age<65):
        echo "You are middle aged <hr>";  
        break;
    case ($age>65):
        echo "You are a senior <hr>"; 
        break;
    default: 
        echo "Invalid age <hr>";
        break;
}

$number = 1;

while($number<=10){
    echo "Number is: $number <hr>";
    $number++;
}

$z=1;
do{
    echo "Value of z is: $z <hr>";
    $z++;
}while($z<=5);
?>