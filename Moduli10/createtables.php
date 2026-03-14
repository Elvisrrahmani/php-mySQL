<?php

 $host = "localhost";
 $db = "testvissi1";
 $user = "root";
 $pass = "";

try{
$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

$sql =
"CREATE TABLE category(
        id INT PRIMARY KEY,
        NAME VARCHAR(255) NOT NULL
        );


CREATE TABLE products(
        id INT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        price VARCHAR(255) NOT NULL,
        category_id INT NOT NULL,
        FOREIGN KEY (category_id) REFERENCES category(id)
        );
        
INSERT INTO category(id, name) VALUES 
(1, 'Elektronike'),
(2, 'Rroba'),
(3, 'Librari');


INSERT INTO products(id, name, price, category_id) VALUES
(1, 'Laptop', '1000$', 1),
(2, 'Smartphone', '500$', 1),
(3, 'Maus', '20$', 1),
(4, 'Pantallona', '300$', 2),
(5, 'Libri ', '15$', 3),
(6, 'Revista', '5$', 3),
(7, 'Gazeta', '2$', 3),
(8, 'PC', '800$', 1),
(9, 'Tablet', '400$', 1),
(10, 'Kapele', '25$', 2),
(11, 'Shall', '30$', 2),
(12, 'Vepra', '20$', 3),
(13, 'Poezi', '10$', 3),
(14, 'Tshirt', '15$', 2),
(15, 'Monitor', '300$', 1);
";

$conn->exec($sql);


echo "table created successfully";

}catch(Exception $e){
    echo "table creation failed";
}

?>