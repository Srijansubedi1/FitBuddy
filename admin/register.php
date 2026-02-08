<?php
require "../config/db.php";

if(isset($_POST['register'])){
    $name = $_POST['fullname'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $conn->query("INSERT INTO admins(fullname,email,password) VALUES('$name','$email','$password')");
    header("Location: login.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Register | FitBuddy</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body class="auth-body">

<div class="auth-container">
  <div class="auth-box">
    <h2>Admin Register</h2>

    <form method="POST">
      <input type="text" name="fullname" placeholder="Full Name" required>
      <input type="email" name="email" placeholder="Email Address" required>
      <input type="password" name="password" placeholder="Password" required>
      <button name="register">Create Account</button>
    </form>

    <p class="auth-link">
      Already have an account? <a href="login.php">Login</a>
    </p>
  </div>
</div>

</body>
</html>
