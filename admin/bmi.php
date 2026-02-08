<?php
session_start();
require "../config/db.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$admin_id = $_SESSION['admin_id'];
$admin = $conn->query("SELECT * FROM admins WHERE id = $admin_id")->fetch_assoc();

/* Fetch BMI Records */
$sql = "
SELECT 
  bmi_records.id,
  users.fullname,
  users.email,
  bmi_records.weight,
  bmi_records.height,
  bmi_records.bmi,
  bmi_records.category,
  bmi_records.created_at
FROM bmi_records
JOIN users ON bmi_records.user_id = users.id
ORDER BY bmi_records.id DESC
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>BMI Records | FitBuddy Admin</title>
<link rel="stylesheet" href="styles.css">

<style>
  table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
  }
  th, td {
    padding: 12px;
    border: 1px solid #1f2937;
    text-align: center;
  }
  th {
    background: #020617;
    color: #22c55e;
  }
  tr:nth-child(even) {
    background: #020617;
  }
</style>
</head>

<body>

<!-- NAVBAR -->
<div class="navbar">
  <div class="nav-left">
    <div class="logo">💪 FitBuddy Admin</div>
  </div>

  <div class="nav-links">
    <a href="dashboard.php">Dashboard</a>
    <a href="users.php">Users</a>
    <a href="trainers.php">Trainers</a>
    <a href="classes.php">Classes</a>
    <a href="reviews.php">Reviews</a>
    <a href="bmi.php" class="active">BMI</a>

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

<!-- PAGE CONTENT -->
<div class="container">
  <h2>📊 User BMI Records</h2>

  <div class="panel">

    <table>
      <tr>
        <th>ID</th>
        <th>Full Name</th>
        <th>Email</th>
        <th>Weight (kg)</th>
        <th>Height (cm)</th>
        <th>BMI</th>
        <th>Category</th>
        <th>Date</th>
      </tr>

      <?php if($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['fullname']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= $row['weight'] ?></td>
            <td><?= $row['height'] ?></td>
            <td><?= $row['bmi'] ?></td>
            <td><?= $row['category'] ?></td>
            <td><?= $row['created_at'] ?></td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr>
          <td colspan="8">No BMI records found.</td>
        </tr>
      <?php endif; ?>

    </table>

  </div>
</div>

<!-- PROFILE DROPDOWN -->
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
