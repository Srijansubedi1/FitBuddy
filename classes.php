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
  <title>Classes — FitBuddy Gym</title>
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
      <li><a href="index.php" class="nav-link a">Home</a></li>
      <li><a href="trainers.php" class="nav-link">Trainers</a></li>
      <li><a href="classes.php" class="nav-link active">Classes</a></li>
      <li><a href="diet.php" class="nav-link">Food & Diet</a></li>
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
      <h2>Fitness Classes 🏃</h2>
      <p class="section-subtitle">Join dynamic group sessions designed for all levels</p>
    </div>

    <?php
$classes = mysqli_query($conn, "SELECT * FROM classes WHERE status='active'");
?>

<ul class="classes-list">

  <?php while($row = mysqli_fetch_assoc($classes)): ?>

  <li class="feature-card class-card">
    <div class="class-icon"><?= $row['icon'] ?></div>

    <div class="class-info">
      <h4><?= $row['class_name'] ?></h4>
      <p class="class-schedule"><?= $row['schedule'] ?></p>
      <p class="class-desc"><?= $row['description'] ?></p>
    </div>

    <div class="feature-cta">
      <a class="btn ghost" href="join_class.php?id=<?= $row['id'] ?>">Join</a>
    </div>
  </li>

  <?php endwhile; ?>

</ul>

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
<?php if(isset($_GET['joined'])): ?>
<div class="popup-success">
  ✅ You have successfully joined this class!
</div>

<script>
setTimeout(() => {
  const popup = document.querySelector('.popup-success');
  if(popup) popup.remove();
},1000);
</script>
<?php endif; ?>

</body>
</html>
