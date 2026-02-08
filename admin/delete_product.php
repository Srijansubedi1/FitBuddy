<?php
session_start();
require "../config/db.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

$id = $_GET['id'];
$conn->query("DELETE FROM products WHERE id=$id");

header("Location: products.php");
