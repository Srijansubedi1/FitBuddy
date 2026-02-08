<?php
require "config/db.php";

$msg = strtolower(trim($_POST['message']));
$found = false;

/* ---------- MEMBERSHIP PLANS ---------- */
if (preg_match('/membership|plan|pricing|price/', $msg)) {
    echo "
    <b>Bot:</b> Here are our membership plans 👇<br><br>

    🥉 <b>Bronze</b> – Rs 1500 / month<br>
    🥈 <b>Silver</b> – Rs 2500 / month<br>
    ⭐ <b>Gold</b> – Rs 3500 / month<br>
    💎 <b>Platinum</b> – Rs 5000 / month<br><br>

    <a href='plans.php' class='chat-btn'>Choose Membership</a>
    ";
    exit;
}

/* ---------- PRODUCTS ---------- */
if (preg_match('/product|products|supplement|protein/', $msg)) {
    $q = $conn->query("SELECT name, price FROM products LIMIT 5");

    echo "<b>Bot:</b> Available products 🛒<br><br>";
    while ($p = $q->fetch_assoc()) {
        echo "• {$p['name']} – Rs {$p['price']}<br>";
    }
    exit;
}

/* ---------- BMI ---------- */
if (preg_match('/bmi|calculate bmi/', $msg)) {
    echo "
    <b>Bot:</b> BMI Calculation 🧮<br><br>
    Formula: Weight (kg) ÷ (Height × Height)<br><br>
    Example:<br>
    Weight = 70 kg<br>
    Height = 1.75 m<br>
    BMI = 70 ÷ (1.75 × 1.75) = <b>22.86</b>
    ";
    exit;
}

/* ---------- TRAINERS DETAILS ---------- */
if (preg_match('/trainer|trainers|coach|personal trainer/', $msg)) {
    echo "
    <b>Bot:</b> Meet our professional trainers 💪<br><br>

    🏋️ <b>Jordan Lee</b><br>
    One of the best trainers<br><br>

    🥗 <b>Rina Patel</b><br>
    Nutrition & Wellness Coach<br>
    Specialist in personalized nutrition plans & weight management<br><br>

    💪 <b>Chris Adams</b><br>
    Strength Coach<br>
    8+ years experience in powerlifting and strength training
    ";
    exit;
}

/* ---------- FAQ MATCHING BY INTENT ---------- */
$faq = $conn->query("SELECT * FROM chatbot_faq");

while ($row = $faq->fetch_assoc()) {
    if (strpos($msg, str_replace('_',' ', $row['intent'])) !== false) {
        echo "<b>Bot:</b> {$row['answer']}";
        $found = true;
        break;
    }
}

/* ---------- FALLBACK ---------- */
if (!$found) {
    echo "
    <b>Bot:</b> I might need help from our team 🤝<br><br>

    <a href='https://wa.me/977XXXXXXXXX' target='_blank' class='wa-btn'>
        💬 WhatsApp Admin
    </a><br>

    <a href='https://www.facebook.com/profile.php?id=61586985674421' target='_blank' class='fb-btn'>
        📘 Facebook Chat
    </a>
    ";
}
?>
