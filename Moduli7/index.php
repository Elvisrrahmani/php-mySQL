<?php

$host = "localhost";
$user = "root";
$pass = "";

try{
$conn = new PDO("mysql:host=$host", $user, $pass);

echo "Connected successfully";
}catch(Exeption $e){
    echo "Connection failed";
}




?>