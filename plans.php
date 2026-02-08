<?php
session_start();
require "config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$q = $conn->prepare("SELECT plan_name FROM memberships WHERE user_id=? AND status='active'");
$q->bind_param("i", $user_id);
$q->execute();
$res = $q->get_result();

$plan = "No active plan";
if ($res->num_rows > 0) {
    $row = $res->fetch_assoc();
    $plan = $row['plan_name'];
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Plans — FitBuddy Gym</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<!-- NAVBAR -->
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
      <li><a href="products.php" class="nav-link">PRODUCTS</a></li>
      <li><a href="my_orders.php" class="nav-link">MY ORDERS</a></li>
    </ul>

  <div class="auth">
    <div class="profile-menu">
      <button id="profileBtn" class="profile-btn">👤</button>
      <div id="memberHeader" class="member-panel member-dropdown" style="display:none;">
        <p><strong>Email:</strong> <?= $_SESSION['email']; ?></p>
        <p><strong>Plan:</strong> <?= $plan; ?></p>
        <a href="logout.php">Logout</a>
      </div>
    </div>
  </div>
</nav>

<!-- PLANS -->
<main>
<section class="plans info">
  <div class="section-header">
    <h2>Choose Your Plan 🎯</h2>
    <p class="section-subtitle">Start your fitness transformation today</p>
  </div>

<div class="plans-grid">

<!-- Bronze -->
<div class="plan-card" data-plan="Bronze" data-price="1500">
  <div class="plan-icon">🥉</div>
  <div class="plan-name">Bronze</div>
  <div class="plan-price">Rs 1500 / month</div>
  <div class="plan-features">Trainers, Classes</div>
  <div class="row">
    <button class="btn btn-primary confirm-plan">Confirm</button>
  </div>
</div>

<!-- Silver -->
<div class="plan-card" data-plan="Silver" data-price="2500">
  <div class="plan-icon">🌙</div>
  <div class="plan-name">Silver</div>
  <div class="plan-price">Rs 2500 / month</div>
  <div class="plan-features">Trainers, Classes</div>
  <div class="row">
    <button class="btn btn-primary confirm-plan">Confirm</button>
  </div>
</div>

<!-- Gold -->
<div class="plan-card" data-plan="Gold" data-price="3500">
  <div class="plan-icon">⭐</div>
  <div class="plan-name">Gold</div>
  <div class="plan-price">Rs 3500 / month</div>
  <div class="plan-features">Trainers, Classes, Diet</div>
  <div class="row">
    <button class="btn btn-primary confirm-plan">Confirm</button>
  </div>
</div>

<!-- Platinum -->
<div class="plan-card" data-plan="Platinum" data-price="5000">
  <div class="plan-icon">💎</div>
  <div class="plan-name">Platinum</div>
  <div class="plan-price">Rs 5000 / month</div>
  <div class="plan-features">All Access</div>
  <div class="row">
    <button class="btn btn-primary confirm-plan">Confirm</button>
  </div>
</div>

</div>
</section>
</main>

<!-- JS: Redirect to payment page -->
<script>
document.querySelectorAll(".confirm-plan").forEach(btn => {
  btn.addEventListener("click", function () {
    const card = this.closest(".plan-card");
    const plan = card.dataset.plan;
    const price = card.dataset.price;

    window.location.href =
      `payment.php?plan=${encodeURIComponent(plan)}&price=${price}`;
  });
});
</script>

<!-- Profile dropdown -->
<script>
document.getElementById("profileBtn").onclick = () => {
  const box = document.getElementById("memberHeader");
  box.style.display = box.style.display === "block" ? "none" : "block";
};
</script>

</body>
</html>
