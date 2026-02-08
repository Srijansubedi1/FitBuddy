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
  <title>Trainers — FitBuddy Gym</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/styles.css">

  <!-- POPUP STYLE -->
  <style>
    .popup-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0,0,0,0.6);
      display: none;
      align-items: center;
      justify-content: center;
      z-index: 9999;
    }

    .popup-box {
      background: #0f172a;
      padding: 25px 40px;
      border-radius: 12px;
      text-align: center;
      color: white;
      font-size: 18px;
      box-shadow: 0 0 30px rgba(34,197,94,0.6);
      animation: popupIn 0.3s ease;
    }

    .popup-box.error {
      box-shadow: 0 0 30px rgba(239,68,68,0.6);
    }

    @keyframes popupIn {
      from { transform: scale(0.7); opacity: 0; }
      to { transform: scale(1); opacity: 1; }
    }
  </style>
</head>

<body>

<!-- POPUP -->
<div id="popup" class="popup-overlay">
  <div id="popupBox" class="popup-box">
    <span id="popupMessage"></span>
  </div>
</div>

<!-- NAVBAR -->
<nav class="nav">
  <a href="index.php" class="logo">
    <span class="logo-icon">💪</span>
    <span class="logo-text">FitBuddy</span>
  </a>

    <ul class="nav-links">
      <li><a href="index.php" class="nav-link ">Home</a></li>
      <li><a href="trainers.php" class="nav-link active">Trainers</a></li>
      <li><a href="classes.php" class="nav-link">Classes</a></li>
      <li><a href="diet.php" class="nav-link">Food & Diet</a></li>
      <li><a href="bmi.php" class="nav-link">BMI</a></li>
      <li><a href="products.php" class="nav-link">PRODUCTS</a></li>
      <li><a href="my_orders.php" class="nav-link">MY ORDERS</a></li>
    </ul>

  <div class="auth">
    <div class="profile-menu">
      <button id="profileBtn" class="profile-btn">👤</button>
      <div class="member-panel member-dropdown" style="display:none;">
        <p><strong>Email:</strong> <?php echo $_SESSION['email']; ?></p>
        <p><strong>Plan:</strong> <?php echo $plan; ?></p>
        <a href="logout.php">Logout</a>
      </div>
    </div>
  </div>
</nav>

<main>

<!-- PAGE INTRO -->
<section class="info">
  <div class="section-header">
    <h2>Meet Our Professional Trainers 💪</h2>
    <p class="section-subtitle">
      Certified experts dedicated to your fitness transformation
    </p>
  </div>
</section>

<section class="protected-section">
  <?php
$trainers = $conn->query("SELECT * FROM trainers ORDER BY id DESC");
?>

<div class="trainer-grid">

<?php while($row = $trainers->fetch_assoc()): ?>
  <div class="feature-card trainer card-anim">

    <img src="images/<?php echo $row['image']; ?>">

    <h3><?php echo $row['name']; ?></h3>
    <p><strong><?php echo $row['specialty']; ?></strong></p>

    <p class="trainer-bio">
      <?php echo $row['bio']; ?>
    </p>

    <div class="feature-cta">
      <button class="btn btn-primary book-trainer" 
              data-trainer="<?php echo $row['name']; ?>">
        Book Session
      </button>
    </div>

  </div>
<?php endwhile; ?>

</div>

</section>


</main>

<!-- SCRIPTS -->
<script>
document.addEventListener('DOMContentLoaded', function () {

  // PROFILE DROPDOWN
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

  // BOOK TRAINER SESSION
  document.querySelectorAll('.book-trainer').forEach(btn => {
    btn.addEventListener('click', function() {
      const trainerName = this.dataset.trainer;

      fetch("book_session.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "trainer=" + encodeURIComponent(trainerName)
      })
      .then(res => res.text())
      .then(data => {

        if (data.trim() === "success") {
          showPopup("✅ Session booked successfully with " + trainerName);
        } 
        else if (data.trim() === "already_booked") {
          showPopup("⚠ You already booked a trainer session.", true);
        } 
        else {
          showPopup("❌ Booking failed.", true);
        }
      });
    });
  });

});

// POPUP FUNCTION
function showPopup(message, isError = false) {
  const popup = document.getElementById("popup");
  const popupBox = document.getElementById("popupBox");
  const popupMessage = document.getElementById("popupMessage");

  popupMessage.innerText = message;

  if (isError) {
    popupBox.classList.add("error");
  } else {
    popupBox.classList.remove("error");
  }

  popup.style.display = "flex";

  // Auto hide after 10 seconds
  setTimeout(() => {
    popup.style.display = "none";
  },1000);
}
</script>

<script>
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.card-anim').forEach((el, i) => {
    setTimeout(() => el.classList.add('in'), i * 120);
  });
});
</script>

</body>
</html>
