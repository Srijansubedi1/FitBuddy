<?php
session_start();
require __DIR__ . "/config/db.php";

/* Auth check (optional – remove if products allowed without login) */
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}



/* Products */
$res = $conn->query("SELECT * FROM products WHERE status='active'");
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>FitBuddy | Products</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/styles.css">
</head>

<body>

<!-- NAVBAR (SAME AS INDEX.PHP) -->
<nav class="nav">
  <a href="index.php" class="logo">
    <span class="logo-icon">💪</span>
    <span class="logo-text">FitBuddy</span>
  </a>

    <ul class="nav-links">
      <li><a href="index.php" class="nav-link ">Home</a></li>
      <li><a href="trainers.php" class="nav-link">Trainers</a></li>
      <li><a href="classes.php" class="nav-link">Classes</a></li>
      <li><a href="diet.php" class="nav-link">Food & Diet</a></li>
      <li><a href="bmi.php" class="nav-link">BMI</a></li>
      <li><a href="products.php" class="nav-link active">PRODUCTS</a></li>
      <li><a href="my_orders.php" class="nav-link">MY ORDERS</a></li>
    </ul>

  <div class="auth">
    <?php if(isset($_SESSION['user_id'])): ?>

<?php
$user_id = $_SESSION['user_id'];

/* Payment */
$p = $conn->prepare("
  SELECT plan_name, status 
  FROM payments 
  WHERE user_id=? 
  ORDER BY id DESC 
  LIMIT 1
");
$p->bind_param("i", $user_id);
$p->execute();
$payment = $p->get_result()->fetch_assoc();

/* Membership */
$q = $conn->prepare("
  SELECT plan_name, status 
  FROM memberships 
  WHERE user_id=?
");
$q->bind_param("i", $user_id);
$q->execute();
$membership = $q->get_result()->fetch_assoc();

/* Trainer */
$t = $conn->prepare("
  SELECT trainer_name 
  FROM trainer_bookings 
  WHERE user_id=? AND status='booked'
  ORDER BY id DESC LIMIT 1
");
$t->bind_param("i", $user_id);
$t->execute();
$trainer = $t->get_result()->fetch_assoc();
?>

<div class="profile-menu">
  <button class="profile-btn">👤</button>

  <div class="member-panel member-dropdown">
    <p><strong>Email:</strong> <?= $_SESSION['email']; ?></p>

    <?php if ($membership && $membership['status'] === 'active'): ?>
      <p><strong>Plan:</strong> <?= htmlspecialchars($membership['plan_name']); ?></p>
      <p><strong>Status:</strong> Active ✅</p>

    <?php elseif ($payment && $payment['status'] === 'pending'): ?>
      <p><strong>Payment:</strong> Pending ⏳</p>

    <?php elseif ($payment && $payment['status'] === 'rejected'): ?>
      <p><strong>Payment:</strong> Rejected ❌</p>
      <a href="plans.php">Retry Payment</a>

    <?php else: ?>
      <p>No active membership</p>
      <a href="plans.php">Buy Membership</a>
    <?php endif; ?>

    <p><strong>Trainer:</strong> <?= $trainer['trainer_name'] ?? 'Not Assigned'; ?></p>
    <a href="logout.php">Logout</a>
  </div>
</div>

<?php else: ?>
  <a href="login.php" class="btn ghost">Login</a>
<?php endif; ?>
</div>
</nav>

<!-- PRODUCTS SECTION -->
<section class="products-section">
  <div class="container">

    <h2 class="section-title">All Gym Products</h2>

    <div class="products-grid">
      <?php while($p = $res->fetch_assoc()): ?>
        <div class="product-card">
          <img src="uploads/<?= $p['image']; ?>" alt="<?= $p['name']; ?>">

          <div class="product-body">
            <h5><?= htmlspecialchars($p['name']); ?></h5>
            <p class="price">Rs. <?= number_format($p['price'], 2); ?></p>

            <a href="add_to_cart.php?id=<?= $p['id']; ?>" class="cart-btn">
              Add to Cart
            </a>
          </div>
        </div>
      <?php endwhile; ?>
    </div>

  </div>
</section>



<script src="js/app.v2.js"></script>
<script src="js/auth.js"></script>

</body>
</html>
