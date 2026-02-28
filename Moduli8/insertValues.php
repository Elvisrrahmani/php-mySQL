<?php

$host = "localhost";
$db = "testvissi";
$user = "root";
$pass = "";

try{
$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

$username = "vissi";

$password = "vissi1234";

$sql = "INSERT INTO users (username, password) VALUES ('$username', '$password')";

$conn->exec($sql);

echo ("value inserted successfully");

}catch(Exeption $e){

    echo ("value not inserted: ");
    
}

?>