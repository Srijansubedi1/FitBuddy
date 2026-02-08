<?php
session_start();
require "../config/db.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$admin_id = $_SESSION['admin_id'];
$admin = $conn->query("SELECT * FROM admins WHERE id=$admin_id")->fetch_assoc();

/* ============================
   HANDLE APPROVE / REJECT
============================ */
if (isset($_POST['action'], $_POST['order_id'])) {

    $order_id = (int)$_POST['order_id'];
    $message  = trim($_POST['admin_message']);

    if ($_POST['action'] === 'approve') {
        $status = 'Approved';
        if ($message === '') {
            $message = 'Your order is approved. Delivery soon.';
        }
    }

    if ($_POST['action'] === 'reject') {
        $status = 'Rejected';
        if ($message === '') {
            $message = 'Payment verification failed. Order rejected.';
        }
    }

    $stmt = $conn->prepare("
        UPDATE orders 
        SET status = ?, admin_message = ?
        WHERE id = ?
    ");
    $stmt->bind_param("ssi", $status, $message, $order_id);
    $stmt->execute();

    header("Location: admin_orders.php");
    exit;
}

/* ============================
   FETCH ORDERS
============================ */
$orders = $conn->query("
    SELECT o.*, u.email 
    FROM orders o
    JOIN users u ON o.user_id = u.id
    ORDER BY o.id DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin | Orders</title>
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
    <a href="payments.php">Payments</a>
    <a href="admin_orders.php" class="active">Orders</a>

    <div class="profile-box">
      <div class="profile-btn" id="profileBtn">
        <?= strtoupper(substr($admin['fullname'], 0, 1)) ?>
      </div>
      <div class="profile-dropdown" id="profileDropdown">
        <p><b>Admin:</b> <?= htmlspecialchars($admin['fullname']) ?></p>
        <p><b>Email:</b> <?= htmlspecialchars($admin['email']) ?></p>
        <a href="logout.php">Logout</a>
      </div>
    </div>
  </div>
</div>

<!-- PAGE CONTENT -->
<div class="container">
<div class="user-card">

<div class="user-header">
  <div>
    <h2>Order Requests</h2>
    <p>Approve or reject product orders</p>
  </div>
</div>

<div class="table-wrapper">
<table class="user-table">
<thead>
<tr>
  <th>User Email</th>
  <th>Products</th>
  <th>Total</th>
  <th>Address</th>
  <th>Phone</th>
  <th>Payment Proof</th>
  <th>Status</th>
  <th>Action</th>
</tr>
</thead>

<tbody>
<?php if ($orders->num_rows > 0): ?>
<?php while ($o = $orders->fetch_assoc()): ?>

<tr>

<td><?= htmlspecialchars($o['email']) ?></td>



<!-- PRODUCTS -->
<td>
<?php
$items = $conn->query("
    SELECT oi.quantity, p.name, p.image
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    WHERE oi.order_id = {$o['id']}
");
?>

<?php while ($item = $items->fetch_assoc()): ?>
  <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;">
    <?php if (!empty($item['image']) && file_exists("../productimg/".$item['image'])): ?>
      <img src="../productimg/<?= htmlspecialchars($item['image']) ?>"
           width="50" height="50" style="border-radius:6px;object-fit:cover;">
    <?php else: ?>
      <span>No Image</span>
    <?php endif; ?>

    <div>
      <div><?= htmlspecialchars($item['name']) ?></div>
      <small>Qty: <?= (int)$item['quantity'] ?></small>
    </div>
  </div>
<?php endwhile; ?>
</td>

<td>Rs <?= number_format($o['total_amount'], 2) ?></td>

<td><?= nl2br(htmlspecialchars($o['address'])) ?></td>
<td><?= htmlspecialchars($o['phone']) ?></td>
<td>
<?php if ($o['payment_proof']): ?>
  <a href="../uploads/payments/<?= htmlspecialchars($o['payment_proof']) ?>" target="_blank">
    <img src="../uploads/payments/<?= htmlspecialchars($o['payment_proof']) ?>"
         width="70" style="border-radius:6px;">
  </a>
<?php else: ?>
  —
<?php endif; ?>
</td>

<td>
<span class="status <?= strtolower($o['status']) ?>">
<?= htmlspecialchars($o['status']) ?>
</span>
</td>

<td>
<?php if ($o['status'] === 'Pending'): ?>
<form method="POST" style="display:flex;flex-direction:column;gap:6px;">
  <input type="hidden" name="order_id" value="<?= $o['id'] ?>">

  <textarea name="admin_message"
            placeholder="Admin message (optional)"
            style="width:180px;"></textarea>

  <button name="action" value="approve" class="btn-success"
          onclick="return confirm('Approve this order?')">
    Approve
  </button>

  <button name="action" value="reject" class="btn-danger"
          onclick="return confirm('Reject this order?')">
    Reject
  </button>
</form>
<?php else: ?>
—
<?php endif; ?>
</td>

</tr>

<?php endwhile; ?>
<?php else: ?>
<tr>
  <td colspan="7" style="text-align:center;">No orders found</td>
</tr>
<?php endif; ?>
</tbody>

</table>
</div>
</div>
</div>

<script>
document.getElementById("profileBtn").onclick = function () {
  document.getElementById("profileDropdown").classList.toggle("show");
};
</script>

</body>
</html>
