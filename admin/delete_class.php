<?php
session_start();
require "../config/db.php";

$id = $_GET['id'];

$conn->query("DELETE FROM classes WHERE id = $id");

header("Location: classes.php");
