<?php
session_start();
include "config.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email    = trim($_POST["email"]);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $password);

    if ($stmt->execute()) {
        $_SESSION["user_id"] = $stmt->insert_id;
        $_SESSION["username"] = $username;
        header("Location: dashboard.php");
        exit();
    } else {
        $message = "User already exists or error!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Sign Up</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
<div class="row justify-content-center">
<div class="col-md-4">

<div class="card p-4 shadow">
<h3 class="text-center">Sign Up</h3>

<?php if($message): ?>
<div class="alert alert-danger"><?= $message ?></div>
<?php endif; ?>

<form method="POST">
<div class="mb-3">
<input type="text" name="username" class="form-control" placeholder="Username" required>
</div>

<div class="mb-3">
<input type="email" name="email" class="form-control" placeholder="Email" required>
</div>

<div class="mb-3">
<input type="password" name="password" class="form-control" placeholder="Password" required>
</div>

<button class="btn btn-primary w-100">Sign Up</button>
</form>

<p class="mt-3 text-center">
Already have an account? <a href="login.php">Login</a>
</p>

</div>
</div>
</div>
</div>

</body>
</html>