<?php

 $host = "localhost";
 $db = "movie";
 $user = "root";
 $pass = "";

try{
$connection = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

$sql = "INSERT INTO users(id,name,surname,username,email,password,
confirm_password) VALUES
(1,'Elvis','Rrahmani','Elvis123', 'elvis@gmail.com', 'elvis1234',
'elvis1234'),
(2,'Enes','Menxhiqi','Enes123', 'enes@gmail.com', 'enes1234',
'enes1234');

INSERT INTO movies(id,m_name,m_desc,m_category,m_year) VALUES
(1,'Spiderman', 'Lorem Ipsum','Action','2027'),
(2,'Batman','Lorem Ipsum','Action','2000');


INSERT INTO bookings(id,user_id,movie_id,nr_tickets, date, time) VALUES
(1,1,2,3,'19/04/2026', '19:00'),
(2,2,1,2,'20/04/2026', '20:00');
        ";

$connection->exec($sql);

echo "values created successfully";

}catch(Exception $e){

    echo "values creation failed";
}

?>