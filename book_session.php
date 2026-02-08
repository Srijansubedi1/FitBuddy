<?php
session_start();
require "config/db.php";

if (!isset($_SESSION['user_id'])) {
    echo "not_logged_in";
    exit;
}

$user_id = $_SESSION['user_id'];
$trainer = $_POST['trainer'] ?? '';

if ($trainer == '') {
    echo "invalid";
    exit;
}

/* CHECK if user already booked any trainer */
$check = $conn->prepare("SELECT id FROM trainer_bookings WHERE user_id = ?");
$check->bind_param("i", $user_id);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    echo "already_booked";
    exit;
}

/* INSERT booking */
$stmt = $conn->prepare("INSERT INTO trainer_bookings (user_id, trainer_name) VALUES (?, ?)");
$stmt->bind_param("is", $user_id, $trainer);

if ($stmt->execute()) {
    echo "success";
} else {
    echo "error";
}
