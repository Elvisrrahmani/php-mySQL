<?php
// Simple DB setup and CRUD demo for Moduli8

$dbHost = '127.0.0.1';
$dbUser = 'root';
$dbPass = '';
$dbName = 'moduli8_db';
$dbPort = 3306;

$mysqli = new mysqli($dbHost, $dbUser, $dbPass, '', $dbPort);
if ($mysqli->connect_errno) {
    die('Connect error: ' . $mysqli->connect_error);
}

// Ensure database and table exist
$createDb = "CREATE DATABASE IF NOT EXISTS `{$dbName}` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
if (!$mysqli->query($createDb)) {
    die('DB create error: ' . $mysqli->error);
}

$mysqli->select_db($dbName);

$createTable = "CREATE TABLE IF NOT EXISTS `users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

if (!$mysqli->query($createTable)) {
    die('Table create error: ' . $mysqli->error);
}

// Handle form submission (insert)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    if ($name !== '' && $email !== '') {
        $stmt = $mysqli->prepare('INSERT INTO users (name, email) VALUES (?, ?)');
        $stmt->bind_param('ss', $name, $email);
        if (!$stmt->execute()) {
            $error = $stmt->error;
        }
        $stmt->close();
    } else {
        $error = 'Name and email are required.';
    }
}

// Fetch users
$res = $mysqli->query('SELECT id, name, email, created_at FROM users ORDER BY id DESC');
$users = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Moduli8 - Users</title>
  <style>body{font-family:Arial,Helvetica,sans-serif;margin:20px}table{border-collapse:collapse;width:100%;max-width:800px}th,td{border:1px solid #ddd;padding:8px;text-align:left}th{background:#f3f3f3}</style>
</head>
<body>
  <h1>Users (Moduli8)</h1>
  <?php if (!empty($error)): ?>
    <div style="color:red;">Error: <?=htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="post" style="margin-bottom:16px;max-width:400px">
    <label>Name<br><input name="name" required></label><br>
    <label>Email<br><input name="email" type="email" required></label><br>
    <button type="submit">Add user</button>
  </form>

  <table>
    <thead>
      <tr><th>ID</th><th>Name</th><th>Email</th><th>Created</th></tr>
    </thead>
    <tbody>
      <?php foreach ($users as $u): ?>
        <tr>
          <td><?=htmlspecialchars($u['id'])?></td>
          <td><?=htmlspecialchars($u['name'])?></td>
          <td><?=htmlspecialchars($u['email'])?></td>
          <td><?=htmlspecialchars($u['created_at'])?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</body>
</html>

<?php
$mysqli->close();
?>
