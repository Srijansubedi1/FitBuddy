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

  <!-- LOGO -->
  <a href="index.php" class="logo">
    <span class="logo-icon">💪</span>
    <span class="logo-text">FitBuddy</span>
  </a>

  <!-- NAV LINKS -->
  <ul class="nav-links">
    <li><a href="index.php" class="nav-link active">Home</a></li>
    <li><a href="trainers.php" class="nav-link">Trainers</a></li>
    <li><a href="classes.php" class="nav-link">Classes</a></li>
    <li><a href="diet.php" class="nav-link">Food & Diet</a></li>
    <li><a href="bmi.php" class="nav-link">BMI</a></li>
  </ul>

  <!-- AUTH SECTION -->
  <div class="auth">

    <!-- PROFILE (Only after login) -->
    <div id="profileMenu" class="profile-menu" style="display:none;">
      <button id="profileBtn" class="profile-btn">👤</button>
      <div id="memberHeader" class="member-panel member-dropdown" style="display:none;"></div>
    </div>

    <!-- LOGIN BUTTON (Only before login) -->
    <button class="btn ghost" id="authBtn">Login</button>

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
          <img src="images/hero-gym.jpg" alt="Gym workout" loading="lazy">
          <div class="image-glow"></div>
        </div>
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
          <div class="stat"><div class="stat-number">1452</div><div class="stat-label">Members</div></div>
          <div class="stat"><div class="stat-number">28</div><div class="stat-label">Trainers</div></div>
          <div class="stat"><div class="stat-number">120</div><div class="stat-label">Weekly Classes</div></div>
          <div class="stat"><div class="stat-number">10</div><div class="stat-label">Years</div></div>
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
      <p>+977 98XXXXXXXX</p>
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
        <a href="#">f</a>
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

  <!-- SCRIPTS -->

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


</body>
</html>
