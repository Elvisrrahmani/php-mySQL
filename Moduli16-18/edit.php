<?php

include_once('config.php');

    $id = $_GET['id'];
    $sql = "SELECT * FROM users WHERE id = :id";
    $prep = $connection->prepare($sql);
    $prep->bindParam(':id', $id);
    $prep->execute();

    $user_data = $prep->fetch(PDO::FETCH_ASSOC);


?>