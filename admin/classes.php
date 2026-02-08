<?php
session_start();
require "../config/db.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$admin_id = $_SESSION['admin_id'];
$admin = $conn->query("SELECT * FROM admins WHERE id = $admin_id")->fetch_assoc();

$classes = $conn->query("SELECT * FROM classes ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Classes | FitBuddy Admin</title>
<link rel="stylesheet" href="styles.css">
<link rel="stylesheet" href="admin.css">
</head>

<body>

<!-- NAVBAR (Same as Dashboard) -->
<div class="navbar">
  <div class="nav-left">
    <div class="logo">💪 FitBuddy Admin</div>
  </div>

  <div class="nav-links">
    <a href="dashboard.php">Dashboard</a>
    <a href="users.php">Users</a>
    <a href="trainers.php">Trainers</a>
    <a href="classes.php" class="active">Classes</a>
    <a href="reviews.php">Reviews</a>
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

<!-- CLASSES PAGE -->
<div class="admin-container">

    <h2>Manage Classes</h2>

    <!-- Add Class Form -->
    <form action="add_class.php" method="POST" class="form-card">
        <input type="text" name="class_name" placeholder="Class Name" required>
        <input type="text" name="schedule" placeholder="Schedule (Mon/Wed/Fri - 6:30 AM)" required>
        <input type="text" name="icon" placeholder="Icon (🔥 💪 🧘‍♂️)" required>
        <textarea name="description" placeholder="Description" required></textarea>
        <button type="submit">Add Class</button>
    </form>

    <!-- Classes Table -->
    <table class="data-table">
        <tr>
            <th>ID</th>
            <th>Class</th>
            <th>Schedule</th>
            <th>Description</th>
            <th>Icon</th>
            <th>Status</th>
            <th>Action</th>
        </tr>

        <?php while($c = $classes->fetch_assoc()): ?>
        <tr>
            <td><?= $c['id'] ?></td>
            <td><?= htmlspecialchars($c['class_name']) ?></td>
            <td><?= htmlspecialchars($c['schedule']) ?></td>
            <td><?= htmlspecialchars($c['description']) ?></td>
            <td><?= htmlspecialchars($c['icon']) ?></td>
            <td><?= htmlspecialchars($c['status']) ?></td>
            <td>
                <a class="delete-btn" href="delete_class.php?id=<?= $c['id'] ?>">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

</div>

<!-- NAVBAR DROPDOWN JS -->
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
