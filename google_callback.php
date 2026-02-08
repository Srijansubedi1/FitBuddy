<?php
session_start();
require "config/db.php";
require "config/google.php";

/* 1️⃣ CHECK CODE */
if (!isset($_GET['code'])) {
    exit("Google Auth Error: No code received");
}

/* 2️⃣ FETCH TOKEN */
$token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

if (isset($token['error'])) {
    exit("Google Token Error: " . $token['error']);
}

$client->setAccessToken($token['access_token']);

/* 3️⃣ GET USER INFO */
$oauth = new Google_Service_Oauth2($client);
$user = $oauth->userinfo->get();

if (!$user->email) {
    exit("Google User Info Error");
}

$email = $user->email;
$fullname = $user->name;

/* 4️⃣ CHECK USER EXISTS */
$stmt = $conn->prepare("SELECT id FROM users WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    $password = NULL;
    $insert = $conn->prepare(
        "INSERT INTO users (fullname, email, password) VALUES (?, ?, ?)"
    );
    $insert->bind_param("sss", $fullname, $email, $password);
    $insert->execute();
    $user_id = $conn->insert_id;
} else {
    $row = $res->fetch_assoc();
    $user_id = $row['id'];
}

/* 5️⃣ LOGIN */
$_SESSION['user_id'] = $user_id;
$_SESSION['email'] = $email;

header("Location: index.php");
exit;
