<?php

include_once('config.php');

if(isset($_POST['submit'])){
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password= $_POST['confirm_password'];

 if(empty($name) || empty($surname) || empty($username) ||empty($email) ||  empty($password) || empty($confirm_password)){
    echo " You are required to fill all of the fields above!";
 }
 else{
    $sql = "INSERT INTO users(name, surname, username, email,password, confirm_password) VALUES (:name,:surname,:username,:email, :password,:confirm_password)" ;

    $insertsql = $connect->prepare($sql);


    $insertsql->bindParam(':name', $name);
    $insertsql->bindParam(':surname', $surname);
    $insertsql->bindParam(':username', $username);
    $insertsql->bindParam(':email', $email);
    $insertsql->bindParam(':password', $password);
    $insertsql->bindParam(':confirm_password', $confirm_password);

    $insertsql->execute();

    header("Location: login.php");
}
 
}

?>