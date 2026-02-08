<?php
session_start();
require "../config/db.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$admin_id = $_SESSION['admin_id'];
$admin = $conn->query("SELECT * FROM admins WHERE id = $admin_id")->fetch_assoc();

$products = $conn->query("SELECT * FROM products ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Admin | Product Management</title>
  <link rel="stylesheet" href="admin.css">
</head>

<body>

<!-- NAVBAR (SAME AS TRAINERS) -->
<div class="navbar">
  <div class="logo">💪 FitBuddy Admin</div>

  <div class="nav-links">
    <a href="dashboard.php">Dashboard</a>
    <a href="users.php">Users</a>
    <a href="trainers.php">Trainers</a>
    <a href="classes.php">Classes</a>
    <a href="reviews.php">Reviews</a>
    <a href="bmi.php">BMI</a>
    <a href="products.php" class="active">Products</a>

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
        <h2>Product Management</h2>
        <p>Manage all gym products in one place</p>
      </div>

      <div class="user-actions">
        <button class="btn-primary" onclick="openAddProduct()">+ Add Product</button>
      </div>
    </div>

    <!-- TABLE -->
    <div class="table-wrapper">
      <table class="user-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Name</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>

        <tbody>
        <?php while($p = $products->fetch_assoc()): ?>
          <tr>
            <td><?= $p['id'] ?></td>
            <td>
              <img src="../uploads/<?= $p['image'] ?>" width="60" style="border-radius:8px;">
            </td>
            <td><?= $p['name'] ?></td>
            <td>Rs. <?= $p['price'] ?></td>
            <td><?= $p['stock'] ?></td>
            <td><?= ucfirst($p['status']) ?></td>
            <td>
              <a class="btn-danger"
                 href="delete_product.php?id=<?= $p['id'] ?>"
                 onclick="return confirm('Delete product?')">
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

function openAddProduct() {
  window.location.href = "add_product.php";
}
</script>

</body>
</html>
