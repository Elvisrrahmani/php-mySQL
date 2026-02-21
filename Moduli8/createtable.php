<?php

$host = "localhost";
$db = "testvissi";
$user = "root";
$pass = "";

try{
$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

$sql = "CREATE TABLE users(
id INT(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
username VARCHAR(255) NOT NULL,
password VARCHAR(255) NOT NULL
);

CREATE TABLE category(
        id INT PRIMARY KEY,
        NAME VARCHAR(255) NOT NULL
        );


CREATE TABLE products(
        id INT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        category_id INT NOT NULL,
        FOREIGN KEY (category_id) REFERENCES category(id)
        );
        
INSERT INTO category(id, name) VALUES 
(1, 'Elektronike'),
(2, 'Veshje'),
(3, 'Librari');


INSERT INTO products(id, name, category_id) VALUES
(1, 'Laptop', 1),
(2, 'Smartphone', 1),
(3, 'Maica', 2),
(4, 'Pantallona', 2),
(5, 'Libri ', 3);
        ";

$conn->exec($sql);

echo "table created successfully";

}catch(Exeption $e){

    echo "table creation failed";
}

?>