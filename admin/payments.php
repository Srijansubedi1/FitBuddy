<?php
session_start();
require "../config/db.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$admin_id = $_SESSION['admin_id'];
$admin = $conn->query("SELECT * FROM admins WHERE id=$admin_id")->fetch_assoc();

$payments = $conn->query("
    SELECT p.*, u.email 
    FROM payments p
    JOIN users u ON p.user_id = u.id
    ORDER BY p.created_at DESC
");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Admin | Payment Requests</title>
  <link rel="stylesheet" href="admin.css">
</head>

<body>

<!-- NAVBAR -->
<div class="navbar">
  <div class="logo">💪 FitBuddy Admin</div>

  <div class="nav-links">
    <a href="dashboard.php">Dashboard</a>
    <a href="users.php">Users</a>
    <a href="trainers.php">Trainers</a>
    <a href="classes.php">Classes</a>
    <a href="reviews.php">Reviews</a>
    <a href="bmi.php">BMI</a>
    <a href="payments.php" class="active">Payments</a>

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

<!-- PAGE -->
<div class="container">

  <div class="user-card">

    <!-- HEADER -->
    <div class="user-header">
      <div>
        <h2>Payment Requests</h2>
        <p>Approve or reject user payments</p>
      </div>
    </div>

    <!-- TABLE -->
    <div class="table-wrapper">
      <table class="user-table">
        <thead>
          <tr>
            <th>User Email</th>
            <th>Plan</th>
            <th>Amount</th>
            <th>Screenshot</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>

        <tbody>
        <?php if($payments->num_rows > 0): ?>
          <?php while($p = $payments->fetch_assoc()): ?>
          <tr>
            <td><?= $p['email'] ?></td>
            <td><?= $p['plan_name'] ?></td>
            <td>Rs <?= $p['amount'] ?></td>
            <td>
              <a href="../uploads/payments/<?= $p['payment_screenshot'] ?>" target="_blank">
                <img src="../uploads/payments/<?= $p['payment_screenshot'] ?>" width="80" style="border-radius:8px;">
              </a>
            </td>
            <td>
              <?php if($p['status'] === 'pending'): ?>
                <span class="status pending">Pending</span>
              <?php elseif($p['status'] === 'approved'): ?>
                <span class="status approved">Approved</span>
              <?php else: ?>
                <span class="status rejected">Rejected</span>
              <?php endif; ?>
            </td>
            <td>
              <?php if($p['status'] === 'pending'): ?>
                <a class="btn-success"
                   href="approve_payment.php?id=<?= $p['id'] ?>"
                   onclick="return confirm('Approve this payment?')">
                   Approve
                </a>
                <a class="btn-danger"
                   href="reject_payment.php?id=<?= $p['id'] ?>"
                   onclick="return confirm('Reject this payment?')">
                   Reject
                </a>
              <?php else: ?>
                —
              <?php endif; ?>
            </td>
          </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="6" style="text-align:center;">No payment requests found</td>
          </tr>
        <?php endif; ?>
        </tbody>

      </table>
    </div>

  </div>

</div>

<!-- PROFILE DROPDOWN -->
<script>
document.getElementById("profileBtn").onclick = () => {
  document.getElementById("profileDropdown").classList.toggle("show");
}
</script>

</body>
</html>
