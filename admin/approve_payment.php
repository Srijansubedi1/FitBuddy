<?php
session_start();
require "../config/db.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$id = (int)$_GET['id'];

// Get payment info
$p = $conn->prepare("
    SELECT * FROM payments WHERE id=?
");
$p->bind_param("i", $id);
$p->execute();
$payment = $p->get_result()->fetch_assoc();

if (!$payment || $payment['status'] !== 'pending') {
    header("Location: payments.php");
    exit;
}

// 1️⃣ Approve payment
$u = $conn->prepare("UPDATE payments SET status='approved' WHERE id=?");
$u->bind_param("i", $id);
$u->execute();

// 2️⃣ Create membership
$m = $conn->prepare("
    INSERT INTO memberships 
    (user_id, plan_name, price, status, payment_status, payment_id) 
    VALUES (?, ?, ?, 'active', 'paid', ?)
");
$m->bind_param(
    "isdi",
    $payment['user_id'],
    $payment['plan_name'],
    $payment['amount'],
    $payment['id']
);
$m->execute();

// 3️⃣ Notify user (optional but recommended)
$n = $conn->prepare("
    INSERT INTO notifications (user_id, message) 
    VALUES (?, ?)
");
$msg = "✅ Your payment has been approved. Membership activated!";
$n->bind_param("is", $payment['user_id'], $msg);
$n->execute();

header("Location: payments.php?approved=1");
exit;
