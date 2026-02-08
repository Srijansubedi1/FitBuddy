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

if ($res->num_rows == 0) {
    header("Location: plans.php");
    exit;
}

$row = $res->fetch_assoc();
$plan = $row['plan_name'];
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Food & Diet — FitBuddy Gym</title>
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
      <li><a href="diet.php" class="nav-link active">Food & Diet</a></li>
      <li><a href="bmi.php" class="nav-link">BMI</a></li>
      <li><a href="products.php" class="nav-link">PRODUCTS</a></li>
      <li><a href="my_orders.php" class="nav-link">MY ORDERS</a></li>
    </ul>

  <div class="auth">
    <div class="profile-menu">
      <button id="profileBtn" class="profile-btn">👤</button>

      <!-- Dropdown -->
      <div class="member-panel member-dropdown" style="display:none;">
        <p><strong>Email:</strong> <?php echo $_SESSION['email']; ?></p>
        <p><strong>Plan:</strong> <?php echo $plan; ?></p>
        <a href="logout.php">Logout</a>
      </div>
    </div>
  </div>
</nav>

<main>
  <section class="protected-section">
    <div class="section-header">
      <h2>Nutrition & Diet Plans 🥗</h2>
      <p class="section-subtitle">Personalized meal plans tailored to your goals</p>
    </div>

    <div class="diet-list">

      <div class="feature-card diet-card">
        <div class="diet-icon">🔥</div>
        <h3>Weight Loss Plan</h3>
        <p class="diet-desc">Balanced low-calorie diet with strategic meal timing</p>
        <ul class="diet-highlights">
          <li>• Calorie-controlled meals</li>
          <li>• High protein intake</li>
          <li>• Meal prep guide included</li>
        </ul>
        <div class="feature-cta">
          <a class="btn ghost" href="plans.php">View Plan</a>
        </div>
      </div>

      <div class="feature-card diet-card">
        <div class="diet-icon">💪</div>
        <h3>Muscle Gain Plan</h3>
        <p class="diet-desc">Higher protein intake with strength-focused nutrition</p>
        <ul class="diet-highlights">
          <li>• High protein meals</li>
          <li>• Caloric surplus for gains</li>
          <li>• Pre/post workout nutrition</li>
        </ul>
        <div class="feature-cta">
          <a class="btn ghost" href="plans.php">View Plan</a>
        </div>
      </div>

    </div>
  </section>
</main>

<!-- PROFILE DROPDOWN SCRIPT -->
<script>
document.addEventListener('DOMContentLoaded', function () {

  const profileBtn = document.getElementById('profileBtn');
  const profileDropdown = document.querySelector('.member-dropdown');

  if(profileBtn && profileDropdown){

    profileBtn.addEventListener('click', function(e){
      e.stopPropagation();
      profileDropdown.style.display =
        profileDropdown.style.display === 'block' ? 'none' : 'block';
    });

    document.addEventListener('click', function(e){
      if(!e.target.closest('.profile-menu')){
        profileDropdown.style.display = 'none';
      }
    });
  }

});
</script>

</body>
</html>
