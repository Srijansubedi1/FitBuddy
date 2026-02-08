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
  <title>BMI Calculator — FitBuddy Gym</title>
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
    <li><a href="index.php" class="nav-link">Home</a></li>
    <li><a href="trainers.php" class="nav-link">Trainers</a></li>
    <li><a href="classes.php" class="nav-link">Classes</a></li>
    <li><a href="diet.php" class="nav-link">Food & Diet</a></li>
    <li><a href="bmi.php" class="nav-link active">BMI</a></li>
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

<section class="info">
  <div class="section-header">
    <h2>Body Mass Index (BMI) 📊</h2>
    <p class="section-subtitle">
      Understand your body composition and track your fitness progress
    </p>
  </div>
</section>

<section class="protected-section">
  <div class="plans-grid">

    <!-- BMI CALCULATOR -->
    <div class="feature-card bmi-card card-anim">
      <h3>Calculate Your BMI</h3>

      <div class="bmi-calc">

        <!-- NEW INPUTS -->
        <label>
          <span class="input-label">Age</span>
          <input type="number" id="age" min="10" placeholder="e.g. 22">
        </label>

        <label>
          <span class="input-label">Gender</span>
          <select id="gender">
            <option value="Male">Male</option>
            <option value="Female">Female</option>
          </select>
        </label>

        <label>
          <span class="input-label">Weight (kg)</span>
          <input type="number" id="weight" min="1" placeholder="e.g. 70">
        </label>

        <label>
          <span class="input-label">Height (cm)</span>
          <input type="number" id="height" min="1" placeholder="e.g. 170">
        </label>

        <label>
          <span class="input-label">Goal</span>
          <select id="goal">
            <option value="weight_loss">Weight Loss</option>
            <option value="weight_gain">Weight Gain</option>
            <option value="maintain">Maintain</option>
          </select>
        </label>

        <label>
          <span class="input-label">Diet Preference</span>
          <select id="veg">
            <option value="0">Non-Vegetarian</option>
            <option value="1">Vegetarian</option>
          </select>
        </label>

        <label>
          <span class="input-label">Disease</span>
          <select id="disease">
            <option value="none">None</option>
            <option value="diabetes">Diabetes</option>
            <option value="bp">Blood Pressure</option>
            <option value="heart_disease">Heart Disease</option>
          </select>
        </label>

        <div class="row">
          <button class="btn btn-primary" id="calcBmi">Calculate BMI</button>
        </div>

      </div>

      <div id="bmiResult" class="bmi-result" style="margin-top:14px"></div>
    </div>

  </div>
</section>

</main>

<script>
document.addEventListener('DOMContentLoaded', function () {

  const bmiResult = document.getElementById('bmiResult');

  document.getElementById('calcBmi').onclick = () => {

    const age = +document.getElementById('age').value;
    const gender = document.getElementById('gender').value;
    const weight = +document.getElementById('weight').value;
    const height = +document.getElementById('height').value;
    const goal = document.getElementById('goal').value;
    const veg = +document.getElementById('veg').value;
    const disease = document.getElementById('disease').value;

    if(!age || !weight || !height){
      bmiResult.innerHTML = "Please fill all required fields.";
      return;
    }

    const bmi = (weight / ((height/100) ** 2)).toFixed(1);

    let cat = '';
    if(bmi < 18.5) cat = '⚠Underweight';
    else if(bmi < 25) cat = '✅Normal';
    else if(bmi < 30) cat = '⚠Overweight';
    else cat = '🚨Obesity';

    bmiResult.innerHTML = `<strong>Your BMI:</strong> ${bmi} (${cat})`;

    // SAVE BMI (UNCHANGED)
    fetch("save_bmi.php", {
      method: "POST",
      headers: {"Content-Type": "application/x-www-form-urlencoded"},
      body: `weight=${weight}&height=${height}&bmi=${bmi}&category=${cat}`
    });

    // ML RECOMMENDATION
    fetch("http://127.0.0.1:5000/recommend", {
      method: "POST",
      headers: {"Content-Type": "application/json"},
      body: JSON.stringify({
        age, gender, bmi, goal, veg, disease
      })
    })
    .then(res => res.json())
    .then(data => {

  let html = `
  <div class="result-card">
    <h3>📊 Your BMI: ${data.bmi} <span>(${data.bmi_category})</span></h3>
  </div>

  <div class="result-grid">

    <div class="result-box">
      <h4>🥗 Diet Plan</h4>
  `;

  data.diet_plan.forEach(m => {
    const k = Object.keys(m)[0];
    html += `<div class="meal"><strong>${k}</strong><ul>`;
    m[k].foods.forEach(f => {
      html += `<li>${f.food_name}</li>`;
    });
    html += `</ul></div>`;
  });

  html += `
    </div>

    <div class="result-box">
      <h4>🏋️ Workout Plan</h4>
  `;

  data.workout_plan.workouts.forEach(w => {
    html += `
      <div class="workout-day">
        <span>${w.day}</span>
        <small>${w.workout}</small>
      </div>
    `;
  });

  html += `
    </div>
  </div>
  `;

  bmiResult.innerHTML += html;
});

  };
});
</script>

</body>
</html>
