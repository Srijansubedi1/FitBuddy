<?php
session_start();
require "config/db.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$id = intval($_GET['id']);

$conn->query("DELETE FROM users WHERE id = $id");
$conn->query("DELETE FROM trainer_bookings WHERE user_id = $id");
$conn->query("DELETE FROM memberships WHERE user_id = $id");

header("Location: users.php");
exit;
