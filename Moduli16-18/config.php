<?php
$host = "localhost";
$db = "movie";
$username = "root";
$password = "";

try {
    $connection  = new PDO("mysql:host=$host;dbname=$db", $username, $password);
    echo "Connected successfully <br>";
}
catch (Exeption $e){
    echo "Connection failed: " ;
}

?>