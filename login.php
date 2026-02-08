<?php
session_start();
require_once "config/db.php";
require_once 'config/google.php';
$login_url = $client->createAuthUrl();
 // Google OAuth config

$message = "";

// If already logged in, redirect
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// 🔵 Google login URL
$googleLoginURL = $client->createAuthUrl();

// 🔵 Normal login
if (isset($_POST['login'])) {

    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {

        $user = mysqli_fetch_assoc($result);

        if (!empty($user['password']) && password_verify($password, $user['password'])) {

            // Security: regenerate session
            session_regenerate_id(true);

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email']   = $user['email'];

            header("Location: index.php");
            exit;

        } else {
            $message = "Invalid password!";
        }

    } else {
        $message = "Email not registered!";
    }
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Login — FitBuddy</title>
  <link rel="stylesheet" href="css/styles.css">
</head>

<body class="auth-page">

<div class="auth-box">
  <h2>Login</h2>

  <!-- MESSAGE -->
  <?php if ($message != "") { ?>
      <p style="color:red; text-align:center;"><?php echo $message; ?></p>
  <?php } ?>

  <!-- 🔵 NORMAL LOGIN -->
  <form method="POST" action="">

    <input type="email" name="email" placeholder="Email" required>

    <input type="password" name="password" placeholder="Password" required>

    <button class="btn btn-primary" type="submit" name="login">
        Login
    </button>

  </form>

  <!-- 🔵 GOOGLE LOGIN -->
  <div style="text-align:center; margin:15px 0;">
    <a href="<?php echo $googleLoginURL; ?>">
      <img src="https://developers.google.com/identity/images/btn_google_signin_dark_normal_web.png"
           alt="Sign in with Google">
    </a>
  </div>

  <p style="text-align:center;">
    Don't have an account? <a href="register.php">Register</a>
  </p>
</div>

</body>
</html>
