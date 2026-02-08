<?php
session_start();
require "../config/db.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'];
$user = $conn->query("SELECT * FROM users WHERE id=$id")->fetch_assoc();

if(isset($_POST['update'])){
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];

    $conn->query("UPDATE users SET fullname='$fullname', email='$email' WHERE id=$id");
    header("Location: users.php");
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Edit User</title>
  <link rel="stylesheet" href="admin.css">
</head>
<body>

<div class="edit-container">

  <div class="edit-card">
    <h2>Edit User</h2>
    <p>Update user information</p>

    <form method="POST">
      <div class="form-group">
        <label>Full Name</label>
        <input type="text" name="fullname" value="<?= htmlspecialchars($user['fullname']) ?>" required>
      </div>

      <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
      </div>

      <div class="form-actions">
        <button type="submit" name="update" class="btn-primary">Update User</button>
        <a href="users.php" class="btn-secondary">Cancel</a>
      </div>
    </form>
  </div>

</div>

</body>
</html>
