<?php
session_start();
require "../config/db.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$admin_id = $_SESSION['admin_id'];
$admin = $conn->query("SELECT * FROM admins WHERE id = $admin_id")->fetch_assoc();

$reviews = $conn->query("SELECT * FROM reviews ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Reviews</title>
<link rel="stylesheet" href="styles.css">
</head>

<body>

<!-- NAVBAR (Same as dashboard.php) -->
<div class="navbar">
  <div class="logo">💪 FitBuddy Admin</div>

  <div class="nav-links">
    <a href="dashboard.php">Dashboard</a>
    <a href="users.php">Users</a>
    <a href="trainers.php">Trainers</a>
    <a href="classes.php">Classes</a>
    <a href="reviews.php" class="active">Reviews</a>
    <a href="bmi.php">BMI</a>

    <!-- Profile -->
    <div class="profile-box">
      <div class="profile-btn" id="profileBtn">
        <?= strtoupper(substr($admin['fullname'], 0, 1)) ?>
      </div>

      <div class="profile-dropdown" id="profileDropdown">
        <p><b>Admin:</b> <span><?= $admin['fullname'] ?></span></p>
        <p><b>Email:</b> <span><?= $admin['email'] ?></span></p>
        <a href="logout.php">Logout</a>
      </div>
    </div>
  </div>
</div>

<!-- PAGE CONTAINER -->
<div class="container">

  <h2>User Reviews</h2>

  <table class="data-table">
    <thead>
      <tr>
        <th>ID</th>
        <th>User</th>
        <th>Rating</th>
        <th>Message</th>
        <th>Action</th>
      </tr>
    </thead>

    <tbody>
      <?php while($r = $reviews->fetch_assoc()): ?>
      <tr>
        <td><?= $r['id'] ?></td>
        <td><?= htmlspecialchars($r['email']) ?></td>
        <td>⭐ <?= $r['rating'] ?>/5</td>
        <td><?= htmlspecialchars($r['message']) ?></td>
        <td>
          <a class="delete-btn" href="delete_review.php?id=<?= $r['id'] ?>"
             onclick="return confirm('Delete this review?')">Delete</a>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

</div>

<!-- PROFILE DROPDOWN SCRIPT -->
<script>
document.addEventListener("DOMContentLoaded", function () {
  const profileBtn = document.getElementById("profileBtn");
  const dropdown = document.getElementById("profileDropdown");

  profileBtn.addEventListener("click", function (e) {
    e.stopPropagation();
    dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
  });

  document.addEventListener("click", function () {
    dropdown.style.display = "none";
  });
});
</script>

</body>
</html>
