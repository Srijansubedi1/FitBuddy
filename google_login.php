<?php
require_once 'config/google.php';

$login_url = $client->createAuthUrl();
?>

<a href="<?= $login_url ?>">
  <img src="images/google-login.png" alt="Login with Google">
</a>
