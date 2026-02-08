<?php
session_start();
require "config/db.php";

$id = (int)$_GET['id'];
$type = $_GET['type'];

if ($type === 'plus') {
    $conn->query("UPDATE cart SET quantity = quantity + 1 WHERE id = $id");
}

if ($type === 'minus') {
    $conn->query("UPDATE cart SET quantity = GREATEST(1, quantity - 1) WHERE id = $id");
}

header("Location: cart.php");
