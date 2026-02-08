<?php
session_start();
require "../config/db.php";

if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $admin = $conn->query("SELECT * FROM admins WHERE email='$email'")->fetch_assoc();

    if($admin && password_verify($password, $admin['password'])){
        $_SESSION['admin_id'] = $admin['id'];
        header("Location: dashboard.php");
    } else {
        $error = "Invalid login credentials!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Login | FitBuddy</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body class="auth-body">

<div class="auth-container">
  <div class="auth-box">
    <h2>Admin Login</h2>

    <?php if(isset($error)): ?>
      <p class="error"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST">
      <input type="email" name="email" placeholder="Email Address" required>
      <input type="password" name="password" placeholder="Password" required>
      <button name="login">Login</button>
    </form>

    <p class="auth-link">
      Don't have an account? <a href="register.php">Register</a>
    </p>
  </div>
</div>

</body>
</html>
