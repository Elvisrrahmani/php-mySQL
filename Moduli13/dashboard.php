<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
<div class="card p-4 shadow text-center">

<h2>Welcome, <?= $_SESSION["username"]; ?> 🎉</h2>

<a href="logout.php" class="btn btn-danger mt-3">Logout</a>

</div>
</div>

</body>
</html>