<?php
$host = "localhost";
$db = "testvissi1";
$username = "root";
$password = "";

try {
    $connect  = new PDO("mysql:host=$host;dbname=$db", $username, $password);
    echo "Connected successfully <br>";
}
catch (Exeption $e){
    echo "Connection failed: " ;
}

?>