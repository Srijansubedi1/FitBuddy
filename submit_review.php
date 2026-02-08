<?php
session_start();
require "config/db.php";

if(!isset($_SESSION['user_id'])){
  header("Location: login.php");
  exit;
}

$user_id = $_SESSION['user_id'];
$email   = $_SESSION['email'];
$rating  = $_POST['rating'];
$message = $_POST['message'];

$stmt = $conn->prepare("INSERT INTO reviews(user_id, email, rating, message) VALUES (?,?,?,?)");
$stmt->bind_param("isis", $user_id, $email, $rating, $message);
$stmt->execute();

header("Location: index.php?review=success");
exit;
