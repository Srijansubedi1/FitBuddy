<?php
session_start();
require "../config/db.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$admin_id = $_SESSION['admin_id'];
$admin = $conn->query("SELECT * FROM admins WHERE id = $admin_id")->fetch_assoc();

/* Dashboard counts */
$users    = $conn->query("SELECT COUNT(*) as total FROM users")->fetch_assoc()['total'];
$members  = $conn->query("SELECT COUNT(*) as total FROM memberships")->fetch_assoc()['total'];
$trainer_count = $conn->query("SELECT COUNT(*) AS total FROM trainers")->fetch_assoc()['total'];
$classes  = $conn->query("SELECT COUNT(*) as total FROM classes")->fetch_assoc()['total'];
$reviews  = $conn->query("SELECT COUNT(*) as total FROM reviews")->fetch_assoc()['total'];
$bmi      = $conn->query("SELECT COUNT(*) as total FROM bmi_records")->fetch_assoc()['total'];

/* ✅ Pending payments count */
$pending_payments = $conn
  ->query("SELECT COUNT(*) AS total FROM payments WHERE status='pending'")
  ->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>FitBuddy Admin Dashboard</title>
<link rel="stylesheet" href="styles.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>

<!-- NAVBAR -->
<div class="navbar">
  <div class="nav-left">
    <div class="logo">💪 FitBuddy Admin</div>
  </div>

  <div class="nav-links">
    <a href="dashboard.php" class="active">Dashboard</a>
    <a href="users.php">Users</a>
    <a href="trainers.php">Trainers</a>
    <a href="classes.php">Classes</a>
    <a href="reviews.php">Reviews</a>
    <a href="bmi.php">BMI</a>
    <a href="products.php">PRODUCTS</a>
    <a href="admin_orders.php">ORDERS</a>

    <!-- ✅ PAYMENTS -->
    <a href="payments.php">
      PAYMENTS
      <?php if ($pending_payments > 0): ?>
        <span class="badge"><?= $pending_payments ?></span>
      <?php endif; ?>
    </a>

    <!-- Profile -->
    <div class="profile-box">
      <div class="profile-btn" id="profileBtn">
        <?= strtoupper(substr($admin['fullname'], 0, 1)) ?>
      </div>

      <div class="profile-dropdown" id="profileDropdown">
        <p><b>Admin:</b> <?= $admin['fullname'] ?></p>
        <p><b>Email:</b> <?= $admin['email'] ?></p>
        <a href="logout.php">Logout</a>
      </div>
    </div>
  </div>
</div>

<!-- DASHBOARD -->
<div class="container">
  <h2>Admin Dashboard</h2>

  <!-- STATS -->
  <div class="grid">
    <div class="card"><h3>Total Users</h3><p><?= $users ?></p></div>
    <div class="card"><h3>Total Members</h3><p><?= $members ?></p></div>
    <div class="card"><h3>Total Trainers</h3><p><?= $trainer_count ?></p></div>
    <div class="card"><h3>Total Classes</h3><p><?= $classes ?></p></div>
    <div class="card"><h3>Total Reviews</h3><p><?= $reviews ?></p></div>
    <div class="card"><h3>BMI Records</h3><p><?= $bmi ?></p></div>
  </div>

  <!-- DASHBOARD ROW -->
  <div class="dashboard-row">

    <!-- QUICK ACTIONS -->
    <div class="panel">
      <h3>Quick Actions</h3>
      <div class="quick-actions">
        <a href="add_user.php">+ Add User</a>
        <a href="add_trainer.php">+ Add Trainer</a>
        <a href="add_class.php">+ Add Class</a>
        <a href="reviews.php">View Reviews</a>
      </div>
    </div>

    <!-- SYSTEM OVERVIEW -->
    <div class="panel">
      <h3>System Overview</h3>
      <ul class="overview-list">
        <li>👤 Total Users: <span><?= $users ?></span></li>
        <li>🏋️ Trainers Available: <span><?= $trainer_count ?></span></li>
        <li>📚 Classes Running: <span><?= $classes ?></span></li>
        <li>⭐ Reviews Received: <span><?= $reviews ?></span></li>
        <li>📊 BMI Records: <span><?= $bmi ?></span></li>
        <li>💳 Pending Payments: <span><?= $pending_payments ?></span></li>
      </ul>
    </div>

  </div>

  <!-- CHART SECTION -->
  <div class="chart-row">
    <div class="chart-card">
      <h3>User Growth</h3>
      <canvas id="userChart"></canvas>
    </div>

    <div class="chart-card">
      <h3>Members vs Trainers</h3>
      <canvas id="trainerChart"></canvas>
    </div>
  </div>

</div>

<!-- JS -->
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

<script>
document.addEventListener("DOMContentLoaded", function () {

  const userChartCanvas = document.getElementById("userChart");
  const trainerChartCanvas = document.getElementById("trainerChart");

  if(userChartCanvas && trainerChartCanvas){

    new Chart(userChartCanvas, {
      type: "line",
      data: {
        labels: ["Jan","Feb","Mar","Apr","May","Jun"],
        datasets: [{
          label: "Users",
          data: [1,2,3,4,5,<?= $users ?>],
          borderColor: "#22c55e",
          fill: false,
          tension: 0.4
        }]
      }
    });

    new Chart(trainerChartCanvas, {
      type: "bar",
      data: {
        labels: ["Members","Trainers"],
        datasets: [{
          label: "Count",
          data: [<?= $members ?>, <?= $trainer_count ?>],
          backgroundColor: ["#f97316","#0ea5e9"]
        }]
      }
    });

  }
});
</script>

</body>
</html>
