<?php
session_start();
include "config/db.php";   // correct path

if (!isset($_SESSION['user_id'])) {
    echo "not_logged_in";
    exit;
}

$user_id = $_SESSION['user_id'];

$sql = "DELETE FROM memberships WHERE user_id = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo "error";
    exit;
}

$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    echo "success";
} else {
    echo "error";
}
