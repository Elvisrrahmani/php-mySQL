<?php

$host = "localhost";
$db = "testvissi";
$user = "root";
$pass = "";

try{
$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

$sql = " Drop TABLE products";

$conn->exec($sql);

echo ("value inserted successfully");

}catch(Exeption $e){

    echo ("value not inserted: ");
    
}

?>