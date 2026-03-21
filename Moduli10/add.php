<?php

include_once ("config.php");

if (isset($_POST['submit'])){

$username = $_POST['username'];

$surname = $_POST['surname'];

$email = $_POST['email'];

$sql = "INSERT INTO users (username, surname, email) VALUES (:username, :surname, :email)";

$sqlQuery = $connect->prepare($sql);

$sqlQuery->bindParam(':username', $username);

$sqlQuery->bindParam(':surname', $surname);

$sqlQuery->bindParam(':email', $email);

$sqlQuery->execute();

echo "New Account created successfully";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
</head>
<body>
    <a href="index.php">Dashboard</a>
    <form action="add.php" method = "POST">
        <input type="text" name="username" placeholder="Username"><br>
        <input type="text" name="surname" placeholder="Surname"><br>
        <input type="email" name="email" placeholder="Email"><br>
        <button type="submit" name="submit" value="Add User">Add</button><br>
    </form>

</body>
</html>