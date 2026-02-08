<?php
require "../config/db.php";
$id = $_GET['id'];
$conn->query("UPDATE orders SET status='Rejected' WHERE id=$id");
header("Location: admin_orders.php");
