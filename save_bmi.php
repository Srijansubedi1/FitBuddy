<?php
session_start();
require "config/db.php";

if (!isset($_SESSION['user_id'])) {
    echo "unauthorized";
    exit;
}

$user_id = $_SESSION['user_id'];

$weight   = $_POST['weight'];
$height   = $_POST['height'];
$bmi      = $_POST['bmi'];
$category = $_POST['category'];

$stmt = $conn->prepare("INSERT INTO bmi_records (user_id, weight, height, bmi, category) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("iddss", $user_id, $weight, $height, $bmi, $category);

if ($stmt->execute()) {
    echo "success";
} else {
    echo "error";
}
?>
