<?php

$host = "localhost";
$user = "root";
$pass = "";

try{
$conn = new PDO("mysql:host=$host", $user, $pass);

$sql = "CREATE DATABASE IF NOT EXISTS testvissi1";

$conn->exec($sql);

echo "Database created successfully";
}catch(Exeption $e){
    echo "Database creation failed";
}




?>