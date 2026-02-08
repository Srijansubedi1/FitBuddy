<?php
session_start();
require "config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$email   = $_SESSION['email'];   // get email from session
$class_id = $_GET['id'] ?? null;

if (!$class_id) {
    header("Location: classes.php");
    exit;
}

// Prevent duplicate join
$check = $conn->prepare("SELECT id FROM class_bookings WHERE user_id=? AND class_id=?");
$check->bind_param("ii", $user_id, $class_id);
$check->execute();
$result = $check->get_result();

if ($result->num_rows == 0) {
    $insert = $conn->prepare("INSERT INTO class_bookings (user_id, email, class_id) VALUES (?, ?, ?)");
    $insert->bind_param("isi", $user_id, $email, $class_id);
    $insert->execute();
}

header("Location: classes.php?joined=success");
exit;
