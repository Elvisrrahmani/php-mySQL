<?php

include_once('config.php');

    $id = $_GET['id'];
    $sql = "DELETE FROM users WHERE id = :id";
    $prep = $connection->prepare($sql);
    $prep->bindParam(':id', $id);
    $prep->execute();


?>