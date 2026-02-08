<?php
session_start();
require __DIR__ . "/config/db.php";
// Dashboard statistics for homepage
$total_users    = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];
$total_members  = $conn->query("SELECT COUNT(*) AS total FROM memberships")->fetch_assoc()['total'];
$total_trainers = $conn->query("SELECT COUNT(*) AS total FROM trainers")->fetch_assoc()['total'];
$total_classes  = $conn->query("SELECT COUNT(*) AS total FROM classes")->fetch_assoc()['total'];


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>


<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>FitBuddy Gym | Fitness, Trainers & Nutrition</title>
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
      <li><a href="index.php" class="nav-link active">Home</a></li>
      <li><a href="trainers.php" class="nav-link">Trainers</a></li>
      <li><a href="classes.php" class="nav-link">Classes</a></li>
      <li><a href="diet.php" class="nav-link">Food & Diet</a></li>
      <li><a href="bmi.php" class="nav-link">BMI</a></li>
      <li><a href="products.php" class="nav-link">PRODUCTS</a></li>
      <li><a href="my_orders.php" class="nav-link">MY ORDERS</a></li>
    </ul>

<div class="auth">

<div class="auth">


  <?php if(isset($_SESSION['user_id'])): ?>


<?php
  $user_id = $_SESSION['user_id'];

// Check latest payment
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

// Check membership
$q = $conn->prepare("
    SELECT plan_name, status 
    FROM memberships 
    WHERE user_id=?
");
$q->bind_param("i", $user_id);
$q->execute();
$membership = $q->get_result()->fetch_assoc();

// Fetch booked trainer
$t = $conn->prepare("
    SELECT trainer_name 
    FROM trainer_bookings 
    WHERE user_id = ? 
    AND status = 'booked'
    ORDER BY id DESC 
    LIMIT 1
");
$t->bind_param("i", $user_id);
$t->execute();
$trainer = $t->get_result()->fetch_assoc();
?>


<div class="profile-menu">
  <button class="profile-btn">👤</button>

  <div class="member-panel member-dropdown">
    <p><strong>Email:</strong> <?php echo $_SESSION['email']; ?></p>

    <?php if ($membership && $membership['status'] === 'active'): ?>
  <p><strong>Plan:</strong> <?= htmlspecialchars($membership['plan_name']); ?></p>
  <p><strong>Status:</strong> Active ✅</p>

<?php elseif ($payment && $payment['status'] === 'pending'): ?>
  <p><strong>Payment Status:</strong> Pending Approval ⏳</p>

<?php elseif ($payment && $payment['status'] === 'rejected'): ?>
  <p><strong>Payment Status:</strong> Rejected ❌</p>
  <a href="plans.php">Retry Payment</a>

<?php else: ?>
  <p>No active membership</p>
  <a href="plans.php">Buy Membership</a>
<?php endif; ?>

<p><strong>Trainer:</strong> 
  <?php echo $trainer['trainer_name'] ?? 'Not Assigned'; ?>
</p>

    <a href="logout.php">Logout</a>
  </div>
</div>

<?php else: ?>
  <a href="login.php" class="btn ghost">Login</a>
<?php endif; ?>

</div>

</nav>

  <main>

    <!-- HERO SECTION -->
    <section id="home" class="hero">
      <div class="hero-overlay"></div>

      <div class="hero-content">
        <div class="hero-badge">🏋️ Transform Your Life</div>
        <h1>Your Fitness Journey <span class="gradient-text">Starts Here</span></h1>
        <p class="hero-description">
          Join our community of 1000+ members and unlock exclusive coaching,
          personalized nutrition guidance, and group classes designed for your goals.
        </p>

        <div class="cta">
          <button class="btn btn-primary" id="plansBtn">
            <span>View Plans</span>
            <span class="btn-arrow">→</span>
          </button>
          <a href="#location" class="btn btn-outline">Find Us</a>
        </div>
      </div>

      <div class="hero-image">
        <div class="image-frame">
         <img src="images/7.jpg" alt="Gym Workout">
          <div class="image-glow"></div>
        </div>
      </div>
      <?php
$p = $conn->query("SELECT * FROM products WHERE status='active' LIMIT 6");
?>
 </section>
 
<section class="products-section">
  <div class="container">

    <h3 class="section-title">Gym Products</h3>

<div class="products-grid">
<?php while($row = $p->fetch_assoc()) { ?>
  
  <div class="product-card">
    <img src="uploads/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">

    <div class="product-body">
      <h5><?php echo $row['name']; ?></h5>
      <p class="price">Rs. <?php echo number_format($row['price'], 2); ?></p>

      <a href="add_to_cart.php?id=<?php echo $row['id']; ?>" class="cart-btn">
        Add to Cart
      </a>
    </div>
  </div>

<?php } ?>
</div>

<a href="products.php" class="view-all">View All Products</a>


  </div>
</section>


    <!-- ABOUT SECTION -->
    <section id="about" class="info about-section">
      <div class="section-header">
        <h2>About FitBuddy</h2>
        <p class="section-subtitle">More than a gym, it's a lifestyle</p>
      </div>

      <div class="about-grid">
        <div class="about-hero">
          <p class="lead">
            FitBuddy empowers every member to train smarter and live healthier.
            We combine certified coaching, nutrition guidance, and modern programs.
          </p>

          <p>
            Our coaches design workouts for all levels, including personal training,
            group classes, and wellness workshops to ensure long-term fitness success.
          </p>

          <div class="mission-vision">
            <div class="card feature-card">
              <div class="card-icon">🎯</div>
              <h4>Mission</h4>
              <p>Make fitness easy, enjoyable, and effective for everyone.</p>
            </div>

            <div class="card feature-card">
              <div class="card-icon">🌟</div>
              <h4>Vision</h4>
              <p>Build a strong and supportive fitness community.</p>
            </div>

            <div class="card feature-card">
              <div class="card-icon">💎</div>
              <h4>Values</h4>
              <p>Integrity, inclusivity, consistency, and results.</p>
            </div>
          </div>

          <div class="cta" style="margin-top:14px">
            <a class="btn" href="plans.php">Join Now</a>
            <a class="btn ghost" href="trainers.php">Meet Trainers</a>
          </div>
        </div>

<aside class="about-stats">
  <div class="stat">
    <div class="stat-number"><?= $total_members ?></div>
    <div class="stat-label">Members</div>
  </div>

  <div class="stat">
    <div class="stat-number"><?= $total_trainers ?></div>
    <div class="stat-label">Trainers</div>
  </div>

  <div class="stat">
    <div class="stat-number"><?= $total_classes ?></div>
    <div class="stat-label">Weekly Classes</div>
  </div>

  <div class="stat">
    <div class="stat-number"><?= date("Y") - 2015 ?></div>
    <div class="stat-label">Years</div>
  </div>
</aside>

      </div>
    </section>

    <!-- TEAM SECTION -->
    <section class="info">
      <div class="section-header">
        <h2>Meet Our Expert Team</h2>
        <p class="section-subtitle">Certified and experienced trainers</p>
      </div>

      <div class="team-grid">
        <div class="team-card feature-card">
          <img src="images/trainer1.jpg" alt="Alex Reed" loading="lazy">
          <h4>Alex Reed</h4>
          <p>Strength & Conditioning</p>
        </div>

        <div class="team-card feature-card">
          <img src="images/trainer2.jpg" alt="Sara Kim" loading="lazy">
          <h4>Sara Kim</h4>
          <p>HIIT & Cardio Coach</p>
        </div>

        <div class="team-card feature-card">
          <img src="images/trainer3.jpg" alt="Jordan Lee" loading="lazy">
          <h4>Jordan Lee</h4>
          <p>Nutrition Specialist</p>
        </div>

        <div class="team-card feature-card">
          <img src="images/trainer4.jpg" alt="Rina Patel" loading="lazy">
          <h4>Rina Patel</h4>
          <p>Group Fitness Trainer</p>
        </div>
      </div>
    </section>

    <!-- MAP -->
    <section id="location" class="map-section">
      <div class="section-header">
        <h2>Find Us</h2>
        <p class="section-subtitle">Our gym location</p>
      </div>

      <iframe
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3532.8429653355247!2d84.44410359999999!3d27.691248!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3994e4c950356f59%3A0x9f16980dc6bd57b4!2sUnited%20Technical%20College!5e0!3m2!1sen!2snp!4v1653817507420!5m2!1sen!2snp"
        width="100%" height="300" style="border:0;" loading="lazy"></iframe>
    </section>

    <!-- FEATURES -->
    <section id="features" class="info features-section">
      <div class="section-header">
        <h2>Explore Features</h2>
        <p class="section-subtitle">
          Membership-based access —
          <a href="plans.php" class="link-accent">View Plans</a>
        </p>
      </div>

      <div class="plans-grid features-grid">
        <a class="feature-card" href="trainers.php">
          <div class="feature-icon">👨‍🏫</div>
          <h3>Trainers</h3>
          <p>Expert coaching</p>
        </a>

        <a class="feature-card" href="classes.php">
          <div class="feature-icon">🏃</div>
          <h3>Classes</h3>
          <p>Group workouts</p>
        </a>

        <a class="feature-card" href="diet.php">
          <div class="feature-icon">🥗</div>
          <h3>Food & Diet</h3>
          <p>Nutrition planning</p>
        </a>

        <a class="feature-card" href="bmi.php">
          <div class="feature-icon">⚖️</div>
          <h3>BMI Calculator</h3>
          <p>Health tracking</p>
        </a>
      </div>
    </section>

  </main>
  <!-- REVIEWS SECTION -->
<section class="info reviews-section">
  <div class="section-header">
    <h2>Member Reviews ⭐</h2>
    <p class="section-subtitle">What our members say about FitBuddy</p>
  </div>

  <div class="reviews-grid">

    <?php
  $reviews = $conn->query("SELECT id, user_id, email, rating, message FROM reviews ORDER BY id DESC LIMIT 6");

    while($row = $reviews->fetch_assoc()):
    ?>
<div class="review-card">
  <div class="review-header">
    <div class="review-user">
      <div class="avatar">👤</div>
      <div>
        <h4><?php echo htmlspecialchars($row['email']); ?></h4>
        <span class="review-date">Verified Member</span>
      </div>
    </div>
  </div>

  <div class="stars"><?php echo str_repeat("★", $row['rating']); ?></div>

  <p class="review-text"><?php echo htmlspecialchars($row['message']); ?></p>
</div>


    <?php endwhile; ?>

  </div>

  <?php if(isset($_SESSION['user_id'])): ?>
  <div class="review-form">
    <h3>Leave a Review</h3>
    <form action="submit_review.php" method="POST">
     <div class="star-rating">
  <input type="radio" name="rating" value="5" id="star5" required>
  <label for="star5">★</label>

  <input type="radio" name="rating" value="4" id="star4">
  <label for="star4">★</label>

  <input type="radio" name="rating" value="3" id="star3">
  <label for="star3">★</label>

  <input type="radio" name="rating" value="2" id="star2">
  <label for="star2">★</label>

  <input type="radio" name="rating" value="1" id="star1">
  <label for="star1">★</label>
</div>


      <textarea name="message" placeholder="Write your review..." required></textarea>

      <button class="btn btn-primary">Submit Review</button>
    </form>
  </div>
  <?php else: ?>
    <p style="text-align:center;margin-top:20px;">
      <a href="login.php" class="btn ghost">Login to leave a review</a>
    </p>
  <?php endif; ?>
</section>


<!-- FOOTER -->
<footer class="site-footer">

  <div class="footer-container">

    <!-- Logo -->
    <div class="footer-col">
      <h2 class="footer-logo">💪 FitBuddy</h2>
      <p>Your fitness partner for a healthier lifestyle.</p>
    </div>

    <!-- Sitemap -->
    <div class="footer-col">
      <h4>SITE MAP</h4>
      <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="trainers.php">Trainers</a></li>
        <li><a href="classes.php">Classes</a></li>
        <li><a href="diet.php">Food & Diet</a></li>
        <li><a href="plans.php">Membership</a></li>
      </ul>
    </div>

    <!-- Contact -->
    <div class="footer-col">
      <h4>CONTACT INFORMATION</h4>
      <p>Bharatpur, Nepal</p>
      <p>+977 9860000000</p>
      <p>fitbuddy@gmail.com</p>
      <a href="#" class="footer-link">Privacy Policy</a>
    </div>

    <!-- Subscribe -->
    <div class="footer-col">
      <h4>SUBSCRIBE</h4>
      <div class="footer-form">
        <input type="email" placeholder="Email address">
        <button>SUBSCRIBE</button>
      </div>

      <div class="footer-social">
        <a href="https://www.facebook.com/profile.php?id=61586985674421">f</a>
        <a href="#">t</a>
        <a href="#">✉</a>
        <a href="#">▶</a>
      </div>
    </div>

  </div>

  <div class="footer-bottom">
    <p>Copyright © <?php echo date("Y"); ?> FitBuddy Gym</p>
  </div>

</footer>
<!--chatbot---->
<link rel="stylesheet" href="chatbot.css">

<!-- Floating Chat Icon -->
<div id="chat-toggle">💬</div>

<!-- Chat Box -->
<div id="chatbox">
  <div class="chat-header">
    Chat with FitBuddy 🤖
    <span onclick="toggleChat()">✕</span>
  </div>

  <div id="chat-body"></div>

  <div class="chat-input">
    <input type="text" id="msg" placeholder="Ask about gym, BMI, plans...">
    <button onclick="sendMsg()">➤</button>
    <button id="voice-btn" title="Voice Input">🎤</button>
  </div>
</div>


  <!-- SCRIPTS -->
  <!--<script src="js/membership.v2.js"></script>-->
  <script src="js/app.v2.js"></script>

  <script>
    document.addEventListener('DOMContentLoaded', () => {

    

      document.getElementById('plansBtn')?.addEventListener('click', () => {
        window.location.href = 'plans.php';
      });

      document.getElementById('plansBtnTop')?.addEventListener('click', () => {
        window.location.href = 'plans.php';
      });

    });
  </script>
 
<script src="js/auth.js"></script>
<?php if(isset($_GET['review'])): ?>
<div class="popup-success">
  ✅ Thank you for your review!
</div>

<script>
setTimeout(() => {
  document.querySelector('.popup-success')?.remove();
}, 1000);
</script>
<?php endif; ?>
<script>
let botStarted = false;

/* Toggle chatbot */
function toggleChat(){
  const chatbox = document.getElementById("chatbox");
  const chatBody = document.getElementById("chat-body");

  chatbox.classList.toggle("open");

  if(chatbox.classList.contains("open") && !botStarted){
    chatBody.innerHTML += `
      <div class="bot">
        👋 <b>Hello! Welcome to FitBuddy</b> 💪<br><br>
            How can I help you today?

      </div>
    `;
    chatBody.scrollTop = chatBody.scrollHeight;
    botStarted = true;
  }
}

/* Send message */
function sendMsg(){
  const input = document.getElementById("msg");
  const msg = input.value.trim();
  if(msg === "") return;

  const chatBody = document.getElementById("chat-body");

  chatBody.innerHTML += `<div class="user">You: ${msg}</div>`;
  chatBody.scrollTop = chatBody.scrollHeight;
  input.value = "";

  fetch("chatbot.php",{
    method: "POST",
    headers: {"Content-Type":"application/x-www-form-urlencoded"},
    body: "message=" + encodeURIComponent(msg)
  })
  .then(res => res.text())
  .then(data => {
    chatBody.innerHTML += `<div class="bot">${data}</div>`;
    chatBody.scrollTop = chatBody.scrollHeight;
  });
}

/* Enter key send */
document.getElementById("msg").addEventListener("keydown", function(e){
  if(e.key === "Enter"){
    e.preventDefault();
    sendMsg();
  }
});

/* Floating icon */
document.getElementById("chat-toggle").addEventListener("click", toggleChat);

/* 🎤 VOICE INPUT */
const voiceBtn = document.getElementById("voice-btn");
const inputField = document.getElementById("msg");

if ("webkitSpeechRecognition" in window) {
  const recognition = new webkitSpeechRecognition();
  recognition.lang = "en-US";
  recognition.continuous = false;
  recognition.interimResults = false;

  voiceBtn.addEventListener("click", () => {
    voiceBtn.innerText = "🎙️";
    recognition.start();
  });

  recognition.onresult = function(event){
    const speechText = event.results[0][0].transcript;
    inputField.value = speechText;
    sendMsg(); // auto send
  };

  recognition.onend = function(){
    voiceBtn.innerText = "🎤";
  };

} else {
  voiceBtn.addEventListener("click", () => {
    alert("Voice input not supported in this browser");
  });
}
</script>



</body>
</html>
