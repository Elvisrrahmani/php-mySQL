<?php

$host = "localhost";
$db = "testvissi";
$user = "root";
$pass = "";

try{
$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

$sql = "ALTER TABLE users ADD COLUMN TEL INT(12) NOT NULL";

$conn->exec($sql);

echo ("COLUMN inserted successfully");

}catch(Exeption $e){

    echo ("COLUMN not inserted: ");
    
}

?>