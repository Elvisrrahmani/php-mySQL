<?php

include_once ("config.php");

if (isset($_POST['submit']))
    {

$id = $_POST['id'];
$username = $_POST['username'];
$surname = $_POST['surname'];
$email = $_POST['email'];


$sql = "UPDATE users SET username = :username, surname = :surname, email = :email WHERE id = :id";
    }