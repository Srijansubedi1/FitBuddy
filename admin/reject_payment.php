<?php
session_start();
require "../config/db.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$payment_id = intval($_GET['id']);

$conn->query("
    UPDATE payments 
    SET status = 'rejected' 
    WHERE id = $payment_id
");

header("Location: payments.php");
exit;
