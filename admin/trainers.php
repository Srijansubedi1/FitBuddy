<?php
session_start();
require "../config/db.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$admin_id = $_SESSION['admin_id'];
$admin = $conn->query("SELECT * FROM admins WHERE id = $admin_id")->fetch_assoc();

$trainers = $conn->query("SELECT * FROM trainers ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Admin | Trainer Management</title>
  <link rel="stylesheet" href="admin.css">
</head>

<body>

<!-- NAVBAR -->
<div class="navbar">
  <div class="logo">💪 FitBuddy Admin</div>

  <div class="nav-links">
    <a href="dashboard.php">Dashboard</a>
    <a href="users.php">Users</a>
    <a href="trainers.php" class="active">Trainers</a>
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
        <h2>Trainer Management</h2>
        <p>Manage all trainers in one place</p>
      </div>

      <div class="user-actions">
        <button class="btn-primary" onclick="openAddTrainer()">+ Add Trainer</button>
      </div>
    </div>

    <!-- TABLE -->
    <div class="table-wrapper">
      <table class="user-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Photo</th>
            <th>Name</th>
            <th>Email</th>
            <th>Specialty</th>
            <th>Action</th>
          </tr>
        </thead>

        <tbody>
        <?php while($t = $trainers->fetch_assoc()): ?>
          <tr>
            <td><?= $t['id'] ?></td>
            <td>
              <img src="../images/<?= $t['image'] ?>" width="60" style="border-radius:8px;">
            </td>
            <td><?= $t['name'] ?></td>
            <td><?= $t['email'] ?></td>
            <td><?= $t['specialty'] ?></td>
            <td>
              <a class="btn-danger" 
                 href="delete_trainer.php?id=<?= $t['id'] ?>"
                 onclick="return confirm('Delete trainer?')">
                 Delete
              </a>
            </td>
          </tr>
        <?php endwhile; ?>
        </tbody>

      </table>
    </div>

  </div>
</div>


<!-- SCRIPTS -->
<script>
document.getElementById("profileBtn").onclick = () => {
  document.getElementById("profileDropdown").classList.toggle("show");
}
</script>

<script>
function openAddTrainer() {
  window.location.href = "add_trainer.php";
}
</script>

</body>
</html>
