<?php
session_start();
include "config/db.php";

if(!isset($_SESSION['user_id'])){
    echo json_encode(["status"=>"none"]);
    exit;
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT u.email, m.plan_name, m.status
        FROM users u
        LEFT JOIN memberships m ON u.id = m.user_id
        WHERE u.id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

if($result && $result['plan_name']){
    echo json_encode([
        "status" => "active",
        "email" => $result['email'],
        "plan" => $result['plan_name'],
        "membership_status" => $result['status']
    ]);
} else {
    echo json_encode(["status"=>"none"]);
}
