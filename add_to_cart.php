<?php
session_start();
require "config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user_id'];
$pid = $_GET['id'];

$check = $conn->query("SELECT * FROM cart WHERE user_id=$user AND product_id=$pid");

if ($check->num_rows > 0) {
    $conn->query("UPDATE cart SET quantity=quantity+1 WHERE user_id=$user AND product_id=$pid");
} else {
    $conn->query("INSERT INTO cart (user_id, product_id) VALUES ($user,$pid)");
}

header("Location: cart.php");
