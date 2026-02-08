<?php
session_start();
require "config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$plan    = $_POST['plan'];
$price   = $_POST['price'];
$payment_method = "QR Payment";

/* Validate file */
if (!isset($_FILES['screenshot']) || $_FILES['screenshot']['error'] !== 0) {
    die("Payment screenshot is required");
}

/* Upload folder */
$folder = "uploads/payments/";
if (!is_dir($folder)) {
    mkdir($folder, 0777, true);
}

/* Secure filename */
$ext = pathinfo($_FILES['screenshot']['name'], PATHINFO_EXTENSION);
$filename = time() . "_" . uniqid() . "." . $ext;
$target = $folder . $filename;

move_uploaded_file($_FILES['screenshot']['tmp_name'], $target);

/* Insert payment (ONLY payment) */
$stmt = $conn->prepare("
    INSERT INTO payments 
    (user_id, plan_name, amount, payment_method, payment_screenshot, status)
    VALUES (?, ?, ?, ?, ?, 'pending')
");

$stmt->bind_param(
    "isiss",
    $user_id,
    $plan,
    $price,
    $payment_method,
    $filename
);

$stmt->execute();

/* Redirect back to dashboard */
header("Location: index.php?payment=pending");
exit;
