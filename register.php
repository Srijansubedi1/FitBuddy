<?php
require_once "config/db.php";

$message = "";

if (isset($_POST['register'])) {

    $fullname = trim($_POST['fullname']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    // Empty field check
    if ($fullname == "" || $email == "" || $password == "") {
        $message = "All fields are required!";
    }

    // Email format check
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Please enter a valid email address!";
    }

    // Block fake gmail domains like gmail.co, gmail.cm, etc
    elseif (!preg_match("/@(gmail\.com|outlook\.com|hotmail\.com)$/", $email)) {
        $message = "Please use a valid email provider like gmail.com,  outlook.com";
    }

    else {

        $fullname = mysqli_real_escape_string($conn, $fullname);
        $email    = mysqli_real_escape_string($conn, $email);

        // Encrypt password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Check if email already exists
        $check = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");

        if (mysqli_num_rows($check) > 0) {
            $message = "Email already registered!";
        } else {

            $sql = "INSERT INTO users (fullname, email, password)
                    VALUES ('$fullname', '$email', '$hashedPassword')";

            if (mysqli_query($conn, $sql)) {
                $message = "Registration successful! You can login now.";
            } else {
                $message = "Something went wrong. Please try again.";
            }
        }
    }
}
?>

<!doctype html>
<html>
<head>
  <title>Register — FitBuddy</title>
  <link rel="stylesheet" href="css/styles.css">
</head>
<body class="auth-page">

<div class="auth-box">
  <h2>Register</h2>

  <!-- MESSAGE -->
  <?php if ($message != "") { ?>
      <p style="color:red; text-align:center;"><?php echo $message; ?></p>
  <?php } ?>

  <form method="POST" action="">

    <input type="text" name="fullname" placeholder="Full Name" required>

    <input type="email" name="email" placeholder="Email Address" required>

    <input type="password" name="password" placeholder="Password" required>

    <button class="btn btn-primary" type="submit" name="register">
        Create Account
    </button>

  </form>

  <p style="text-align:center;">Already have an account? <a href="login.php">Login</a></p>
</div>

</body>
</html>
