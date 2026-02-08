<?php
session_start();
require __DIR__ . "/config/db.php";  

if (!isset($_SESSION['user_id'])) {
    echo "login_required";
    exit;
}

$user_id = $_SESSION['user_id'];
$plan = $_POST['plan'];
$price = $_POST['price'];

$check = $conn->prepare("SELECT id FROM memberships WHERE user_id=?");
$check->bind_param("i", $user_id);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo "already_member";
    exit;
}

$stmt = $conn->prepare("INSERT INTO memberships (user_id, plan_name, price, status) VALUES (?, ?, ?, 'active')");
$stmt->bind_param("isi", $user_id, $plan, $price);

if ($stmt->execute()) {
    echo "success";
} else {
    echo "error";
}
