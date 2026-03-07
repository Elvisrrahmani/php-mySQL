<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Php form</title>
</head>
<body>
    
<form action = "add.php" method="POST">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" placeholder="Username"><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="Password"><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="Email"><br><br>

    <button type="submit" name = "submit">ADD</button>
</form>

</body>
</html>