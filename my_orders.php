<?php
session_start();
require "config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$orders = $conn->query("
    SELECT * 
    FROM orders 
    WHERE user_id = $user_id 
    ORDER BY created_at DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Orders | FitBuddy</title>

<!-- MAIN SITE CSS -->
<link rel="stylesheet" href="css/styles.css">

<!-- INLINE CSS FOR MY ORDERS -->
<style>
/* ===============================
   MY ORDERS PAGE
================================ */
.orders-page {
  padding: 40px 20px 80px;
  min-height: 80vh;
  background: linear-gradient(135deg, #0b1220, #020617);
}

.orders-title {
  text-align: center;
  font-size: 34px;
  margin-bottom: 40px;
  color: #fff;
  font-weight: 700;
}

.orders-wrapper {
  max-width: 1000px;
  margin: auto;
  display: grid;
  gap: 24px;
}

/* ORDER CARD */
.order-card {
  background: linear-gradient(145deg, #0f172a, #020617);
  border-radius: 18px;
  padding: 26px;
  box-shadow: 0 20px 50px rgba(0,0,0,0.5);
  transition: all 0.3s ease;
}

.order-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 30px 70px rgba(0,0,0,0.7);
}

.order-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 14px;
}

.order-id {
  font-size: 18px;
  font-weight: 600;
  color: #e5e7eb;
}

.order-date {
  font-size: 14px;
  color: #94a3b8;
}

.order-info p {
  margin: 10px 0;
  color: #cbd5f5;
  line-height: 1.6;
}

/* STATUS BADGES */
.status-badge {
  padding: 6px 14px;
  border-radius: 999px;
  font-size: 14px;
  font-weight: 600;
  display: inline-block;
}

.status-pending {
  background: rgba(234,179,8,0.15);
  color: #facc15;
}

.status-approved {
  background: rgba(34,197,94,0.15);
  color: #22c55e;
}

.status-rejected {
  background: rgba(239,68,68,0.15);
  color: #ef4444;
}

/* ADMIN MESSAGE */
.admin-message {
  margin-top: 16px;
  padding: 16px;
  background: rgba(255,255,255,0.05);
  border-left: 4px solid #38bdf8;
  border-radius: 12px;
  color: #e5e7eb;
}

/* EMPTY STATE */
.no-orders {
  text-align: center;
  color: #94a3b8;
  font-size: 18px;
  margin-top: 40px;
}
</style>
</head>

<body>

<!-- ===============================
     NAVBAR (COPIED FROM index.php)
================================ -->
<nav class="nav">
  <a href="index.php" class="logo">
    <span class="logo-icon">💪</span>
    <span class="logo-text">FitBuddy</span>
  </a>

  <ul class="nav-links">
    <li><a href="index.php" class="nav-link">Home</a></li>
    <li><a href="trainers.php" class="nav-link">Trainers</a></li>
    <li><a href="classes.php" class="nav-link">Classes</a></li>
    <li><a href="diet.php" class="nav-link">Food & Diet</a></li>
    <li><a href="bmi.php" class="nav-link">BMI</a></li>
    <li><a href="products.php" class="nav-link">PRODUCTS</a></li>
    <li><a href="my_orders.php" class="nav-link active">MY ORDERS</a></li>
  </ul>

  <div class="auth">
  <?php if(isset($_SESSION['user_id'])): ?>
    <div class="profile-menu">
      <button class="profile-btn">👤</button>
      <div class="member-panel member-dropdown">
        <p><strong>Email:</strong> <?= $_SESSION['email']; ?></p>
        <a href="logout.php">Logout</a>
      </div>
    </div>
  <?php else: ?>
    <a href="login.php" class="btn ghost">Login</a>
  <?php endif; ?>
  </div>
</nav>

<!-- ===============================
     MY ORDERS CONTENT
================================ -->
<section class="orders-page">

<h2 class="orders-title">🛒 My Orders</h2>

<div class="orders-wrapper">

<?php if ($orders->num_rows > 0): ?>
<?php while ($o = $orders->fetch_assoc()): ?>

<div class="order-card">

  <div class="order-header">
    <div class="order-id">Order #<?= $o['id'] ?></div>
    <div class="order-date">
      <?= date("d M Y, h:i A", strtotime($o['created_at'])) ?>
    </div>
  </div>

  <div class="order-info">

    <p><b>Total Amount:</b> Rs <?= number_format($o['total_amount'], 2) ?></p>

    <p><b>Delivery Address:</b><br>
      <?= nl2br(htmlspecialchars($o['address'])) ?>
    </p>

    <p><b>Status:</b>
      <?php if ($o['status'] === 'Pending'): ?>
        <span class="status-badge status-pending">Pending</span>
      <?php elseif ($o['status'] === 'Approved'): ?>
        <span class="status-badge status-approved">Approved</span>
      <?php else: ?>
        <span class="status-badge status-rejected">Rejected</span>
      <?php endif; ?>
    </p>

    <?php if (!empty($o['admin_message'])): ?>
      <div class="admin-message">
        <b>Admin Message:</b><br>
        <?= htmlspecialchars($o['admin_message']) ?>
      </div>
    <?php endif; ?>

  </div>
</div>

<?php endwhile; ?>
<?php else: ?>
  <p class="no-orders">No orders found.</p>
<?php endif; ?>

</div>
</section>

<script src="js/app.v2.js"></script>
<script src="js/auth.js"></script>

</body>
</html>
