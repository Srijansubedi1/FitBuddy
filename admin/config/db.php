<?php
$conn = new mysqli("localhost", "root", "", "gym_website");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
session_start();
?>
