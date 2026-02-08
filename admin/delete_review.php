<?php
session_start();
require "../config/db.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$id = intval($_GET['id']);

$conn->query("DELETE FROM reviews WHERE id = $id");

header("Location: reviews.php");
exit;
