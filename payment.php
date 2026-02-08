<?php
session_start();
require "config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['plan']) || !isset($_GET['price'])) {
    header("Location: plans.php");
    exit;
}

$plan  = $_GET['plan'];
$price = $_GET['price'];
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Payment — FitBuddy</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- ✅ INLINE CSS -->
  <style>
    * {
      box-sizing: border-box;
      font-family: 'Segoe UI', sans-serif;
    }

    body {
      margin: 0;
      min-height: 100vh;
      background: radial-gradient(circle at top, #111827, #020617);
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .payment-page {
      width: 100%;
      padding: 30px 15px;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .payment-card {
      background: linear-gradient(145deg, #0b1220, #020617);
      border-radius: 20px;
      padding: 32px 30px;
      width: 100%;
      max-width: 420px;
      box-shadow: 0 25px 60px rgba(0,0,0,0.65);
      border: 1px solid rgba(255,255,255,0.08);
      animation: fadeUp 0.6s ease;
    }

    .payment-card h2 {
      text-align: center;
      color: #fff;
      margin-bottom: 20px;
      font-size: 24px;
    }

    .payment-details {
      background: rgba(255,255,255,0.05);
      border-radius: 14px;
      padding: 14px 16px;
      margin-bottom: 20px;
    }

    .payment-details p {
      color: #e5e7eb;
      font-size: 15px;
      margin: 6px 0;
    }

    .payment-details strong {
      color: #f87171;
    }

    .qr-box {
      text-align: center;
      margin-bottom: 20px;
    }

    .qr-box p {
      color: #9ca3af;
      margin-bottom: 10px;
      font-size: 14px;
    }

    .qr-img {
      width: 200px;
      height: 200px;
      border-radius: 14px;
      padding: 10px;
      background: #fff;
      box-shadow: 0 10px 30px rgba(0,0,0,0.6);
    }

    label {
      display: block;
      color: #9ca3af;
      font-size: 14px;
      margin-bottom: 6px;
    }

    input[type="file"] {
      width: 100%;
      background: rgba(255,255,255,0.06);
      border: 1px dashed rgba(255,255,255,0.2);
      padding: 10px;
      border-radius: 12px;
      color: #e5e7eb;
      cursor: pointer;
    }

    input[type="file"]::file-selector-button {
      background: linear-gradient(135deg, #ef4444, #f97316);
      border: none;
      padding: 6px 14px;
      border-radius: 10px;
      color: #fff;
      cursor: pointer;
      margin-right: 10px;
    }

    button {
      width: 100%;
      margin-top: 18px;
      background: linear-gradient(135deg, #ef4444, #f97316);
      border: none;
      padding: 14px;
      border-radius: 14px;
      color: #fff;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    button:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 30px rgba(239,68,68,0.45);
    }

    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(20px); }
      to   { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>

<body>

<main class="payment-page">

  <div class="payment-card">

    <h2>Complete Your Payment 💳</h2>

    <div class="payment-details">
      <p><strong>Plan:</strong> <?= htmlspecialchars($plan) ?></p>
      <p><strong>Amount:</strong> Rs <?= htmlspecialchars($price) ?></p>
    </div>

    <div class="qr-box">
      <p>Scan & Pay</p>
      <img src="images/qr.jpeg" alt="QR Code" class="qr-img">
    </div>

    <form action="submit_payment.php" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="plan" value="<?= htmlspecialchars($plan) ?>">
      <input type="hidden" name="price" value="<?= htmlspecialchars($price) ?>">

      <label>Upload Payment Screenshot</label>
      <input type="file" name="screenshot" accept="image/*" required>

      <button type="submit">Submit Payment</button>
    </form>

  </div>

</main>

</body>
</html>
