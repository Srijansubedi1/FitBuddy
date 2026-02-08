<?php
session_start();
require "../config/db.php";

$class_name  = $_POST['class_name'];
$schedule    = $_POST['schedule'];
$description = $_POST['description'];
$icon        = $_POST['icon'];

$conn->query("INSERT INTO classes (class_name, schedule, description, icon, status) 
VALUES ('$class_name', '$schedule', '$description', '$icon', 'active')");

header("Location: classes.php");
