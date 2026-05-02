<?php

session_start();

include_once('config.php');

$sql = "SELECT * FROM users";
$selectUser = $connection->prepare($sql);
$selectUser->execute();

$user_data = $selectUser->fetchAll();


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>table, th, td {
  border: 1px solid black;
}

td,th{
    padding: 10px;
}</style>
</head>
<body>


<table>
    <tr>
        <th>Name</th>
        <th>Surname</th>
        <th>Username</th>
        <th>Email</th>
        <th>Password</th>
        <th>Delete</th>
        <th>Update</th>
        <th>Edit</th>
        <th></th>
    </tr>


    <tr>
        <?php
            foreach($user_data as $user){
        
        ?>


        <td><?= $user['name'];?></td>
        <td><?= $user['surname'];?></td>
        <td><?= $user['username'];?></td>
        <td><?= $user['email'];?></td>
        <td><?= $user['password'];?></td>
        <td><a href="delete.php?id=<?= $user['id'];?>">Delete</a></td>
        <td><a href="update.php?id=<?= $user['id'];?>">Update</a></td>
        <td><a href="edit.php?id=<?= $user['id'];?>">Edit</a></td>

        </tr>


                <?php } ?>


</table>
</body>
</html>