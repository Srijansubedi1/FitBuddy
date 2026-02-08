<?php
session_start();
require "../config/db.php";

if(isset($_POST['add_trainer'])){

  $name = $_POST['name'];
  $email = $_POST['email'];
  $specialty = $_POST['specialty'];
  $bio = $_POST['bio'];

  $image = $_FILES['image']['name'];
  move_uploaded_file($_FILES['image']['tmp_name'], "../images/".$image);

  $stmt = $conn->prepare("INSERT INTO trainers(name,email,specialty,bio,image) VALUES(?,?,?,?,?)");
  $stmt->bind_param("sssss",$name,$email,$specialty,$bio,$image);
  $stmt->execute();

  header("Location: trainers.php?success=1");
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Add Trainer | FitBuddy Admin</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="container">

  <div class="form-card">
    <h2>Add New Trainer</h2>

    <form method="POST" enctype="multipart/form-data">

  <label>Trainer Name</label>
  <input type="text" name="name" placeholder="Enter trainer name" required>

  <label>Email</label>
  <input type="email" name="email" placeholder="Enter email address" required>

  <label>Specialty</label>
  <input type="text" name="specialty" placeholder="Trainer specialty" required>

  <label>Bio</label>
  <textarea name="bio" placeholder="Short bio about trainer"></textarea>

  <label>Profile Image</label>
  <input type="file" name="image" required>

  <div class="form-actions">
    <button type="submit" name="add_trainer">Add Trainer</button>
    <a href="trainers.php" class="cancel-btn">Cancel</a>
  </div>

</form>

  </div>

</div>

</body>
</html>
