<?php
session_start();
require "../config/db.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$admin_id = $_SESSION['admin_id'];
$admin = $conn->query("SELECT * FROM admins WHERE id = $admin_id")->fetch_assoc();

$sql = "
SELECT 
    u.id,
    u.fullname,
    u.email,
    m.plan_name,
    m.price,
    tb.trainer_name,
    tb.booking_date,
    tb.status
FROM users u
LEFT JOIN memberships m ON u.id = m.user_id
LEFT JOIN trainer_bookings tb ON u.id = tb.user_id
ORDER BY u.id DESC
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Admin | User Management</title>
 <link rel="stylesheet" href="admin.css">
</head>

<body>

<!-- NAVBAR -->
<div class="navbar">
  <div class="logo">💪 FitBuddy Admin</div>

  <div class="nav-links">
    <a href="dashboard.php">Dashboard</a>
    <a href="users.php" class="active">Users</a>
    <a href="trainers.php">Trainers</a>
    <a href="classes.php">Classes</a>
    <a href="reviews.php">Reviews</a>
    <a href="bmi.php">BMI</a>

    <div class="profile-box">
      <div class="profile-btn" id="profileBtn">
        <?= strtoupper(substr($admin['fullname'],0,1)) ?>
      </div>

      <div class="profile-dropdown" id="profileDropdown">
        <p><b>Admin:</b> <span><?= $admin['fullname'] ?></span></p>
        <p><b>Email:</b> <span><?= $admin['email'] ?></span></p>
        <a href="logout.php">Logout</a>
      </div>
    </div>
  </div>
</div>


<!-- PAGE -->
<div class="container">

  <div class="user-card">

    <!-- HEADER -->
    <div class="user-header">
      <div>
        <h2>User Management</h2>
        <p>Manage all users in one place</p>
      </div>

      <div class="user-actions">
     
        <button class="btn-primary" onclick="openAddUser()">+ Add User</button>

      </div>
    </div>

    <!-- FILTERS -->
<div class="filters">
  <input type="text" id="searchInput" placeholder="Search users...">

  <select id="statusFilter">
    <option value="">All Status</option>
    <option value="booked">Booked</option>
    <option value="pending">Pending</option>
  </select>

  <select id="dateFilter">
    <option value="">All Dates</option>
    <option value="today">Today</option>
    <option value="week">This Week</option>
    <option value="month">This Month</option>
  </select>
</div>


    <!-- TABLE -->
    <div class="table-wrapper">
      <table class="user-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Membership</th>
            <th>Trainer</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>

<tbody id="userTable">
  <!-- Users will load here using AJAX -->
</tbody>

      </table>
    </div>

  </div>
</div>

<script>
document.getElementById("profileBtn").onclick = () => {
  document.getElementById("profileDropdown").classList.toggle("show");
}
</script>
<script>
function loadUsers() {
  let search = document.getElementById("searchInput").value;
  let status = document.getElementById("statusFilter").value;
  let date   = document.getElementById("dateFilter").value;

  fetch(`fetch_users.php?search=${search}&status=${status}&date=${date}`)
    .then(res => res.text())
    .then(data => {
      document.getElementById("userTable").innerHTML = data;
    });
}

document.getElementById("searchInput").addEventListener("keyup", loadUsers);
document.getElementById("statusFilter").addEventListener("change", loadUsers);
document.getElementById("dateFilter").addEventListener("change", loadUsers);

// Auto load on page open
loadUsers();
</script>
<script>
function openAddUser() {
  window.location.href = "add_user.php";
}
</script>

</body>
</html>
