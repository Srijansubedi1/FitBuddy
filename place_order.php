<?php
session_start();
require "config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$total   = $_POST['total'];
$address = $conn->real_escape_string($_POST['address']);
$phone   = $conn->real_escape_string($_POST['phone']);
/* ===============================
   PAYMENT PROOF UPLOAD
================================ */
$proofName = time() . "_" . $_FILES['payment_proof']['name'];
$tmpPath   = $_FILES['payment_proof']['tmp_name'];
$uploadDir = "uploads/payments/";

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

move_uploaded_file($tmpPath, $uploadDir . $proofName);

/* ===============================
   INSERT ORDER
================================ */
$conn->query("
    INSERT INTO orders (user_id, total_amount, address, phone, payment_proof, status, created_at)
    VALUES ($user_id, $total, '$address','$phone', '$proofName', 'Pending', NOW())
");

$order_id = $conn->insert_id;

/* ===============================
   INSERT ORDER ITEMS (FROM CART TABLE)
================================ */
$cart = $conn->query("
    SELECT c.product_id, c.quantity, p.price
    FROM cart c
    JOIN products p ON c.product_id = p.id
    WHERE c.user_id = $user_id
");

while ($row = $cart->fetch_assoc()) {
    $pid   = $row['product_id'];
    $qty   = $row['quantity'];
    $price = $row['price'];

    $conn->query("
        INSERT INTO order_items (order_id, product_id, quantity, price)
        VALUES ($order_id, $pid, $qty, $price)
    ");
}

/* ===============================
   CLEAR CART
================================ */
$conn->query("DELETE FROM cart WHERE user_id = $user_id");

/* ===============================
   REDIRECT
================================ */
header("Location: my_orders.php");
exit;
