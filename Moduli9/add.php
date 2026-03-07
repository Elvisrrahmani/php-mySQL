<?php

include_once ("config.php");

if (isset($_POST['submit'])){

$username = $_POST['username'];

$password = $_POST['password'];

$email = $_POST['email'];

$sql = "INSERT INTO users (username, password, email) VALUES (:username, :password, :email)";

$sqlQuery = $connect->prepare($sql);

$sqlQuery->bindParam(':username', $username);

$sqlQuery->bindParam(':password', $password);

$sqlQuery->bindParam(':email', $email);

$sqlQuery->execute();

echo "New Account created successfully";
}

?>