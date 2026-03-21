<?php

include_once ("config.php");

$id = $_GET['id'];

$sql = "SELECT * FROM users WHERE id = :id";

$getUsers = $conn->prepare($sql);

$getUsers->bindParam(':id', $id);

$getUsers->execute();

$data = $prep -> fetch()

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=\, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="update.php" method = "POST">

    </form>
</body>
</html>