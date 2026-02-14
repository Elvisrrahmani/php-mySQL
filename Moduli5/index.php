<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
   <table border="1">
    <tr>
        <th>Phone</th>
        <th>Company</th>
        <th>Price</th>
    </tr>
</body>
</html><?php

$dogs = [
array("Husky", "Siberian ", 15),
array("Labrador Retriever", "Usa", 10),
array("German Shepherd", "German ", 12), 
];

echo $dogs[0][0]. "Origin:". $dogs[0][1]. "Life expectancy:". $dogs[0][2].  "<br>";
echo $dogs[1][0]. "Origin:". $dogs[1][1]. "Life expectancy:". $dogs[1][2].  "<br>";
echo $dogs[2][0]. "Origin:". $dogs[2][1]. "Life expectancy:". $dogs[2][2].  "<br>";

for($x = 0; $x<3; $x++){
        echo "<ul>";
        for($y = 0; $y<3; $y++){
            echo "<li>".$dogs[$x][$y]."</li>";
        }
        echo "</ul>";
    }

$phones = [
array("Iphone 15 Pro Max", "Apple ", 1200),
array("Samsung Galaxy S23 Ultra", "Samsung ", 1000),
array("Google Pixel 7 Pro", "Google ", 900), 
];

for ($row = 0; $row < 3; $row++){
    echo "<tr>";
    for ($col = 0; $col < 3; $col++){
        echo "<td>".$phones[$row][$col]."</td>";
    }
    echo "</tr>";
}
echo "</table>";


$grades = [
"Matematik" => "3",
"Fizik" => "4",
"Kimia" => "5",
 "Biologji" => "2",
];

echo "Math grade is ".$grades["Matematik"]. "<br>";
echo "Physics grade is ".$grades["Fizik"]. "<br>";
echo "Chemistry grade is ".$grades["Kimia"]. "<br>";
echo "Biology grade is ".$grades["Biologji"]. "<br>";

?>