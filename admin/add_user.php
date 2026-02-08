<?php
require "../config/db.php";

if(isset($_POST['add'])) {
    $name  = $_POST['name'];
    $email = $_POST['email'];
    $pass  = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $conn->query("INSERT INTO users(fullname,email,password) VALUES('$name','$email','$pass')");
    header("Location: users.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New User</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        body{
            background: linear-gradient(120deg, #020617, #020b1c);
            font-family: Arial, sans-serif;
        }

        .form-container{
            width: 420px;
            margin: 80px auto;
            padding: 30px;
            border-radius: 12px;
            background: rgba(10, 15, 35, 0.9);
            box-shadow: 0 0 20px rgba(0,0,0,0.6);
            color: white;
        }

        .form-container h2{
            text-align: center;
            color: #22c55e;
            margin-bottom: 25px;
        }

        .form-group{
            margin-bottom: 18px;
        }

        .form-group label{
            display: block;
            margin-bottom: 6px;
            color: #cbd5f5;
        }

        .form-group input{
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #1e293b;
            background: #020617;
            color: white;
            outline: none;
        }

        .form-actions{
            display: flex;
            justify-content: space-between;
            margin-top: 25px;
        }

        .btn-add{
            background: #22c55e;
            border: none;
            padding: 12px 28px;
            border-radius: 10px;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        .btn-add:hover{
            background: #16a34a;
        }

        .btn-cancel{
            background: #1f2937;
            padding: 12px 28px;
            border-radius: 10px;
            color: white;
            text-decoration: none;
            font-size: 16px;
            text-align: center;
        }

        .btn-cancel:hover{
            background: #374151;
        }
    </style>
</head>

<body>

<div class="form-container">
    <h2>Add New User</h2>

    <form method="POST">

        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="name" placeholder="Enter full name" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" placeholder="Enter email address" required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="Enter password" required>
        </div>

        <div class="form-actions">
            <button type="submit" name="add" class="btn-add">Add User</button>
            <a href="users.php" class="btn-cancel">Cancel</a>
        </div>

    </form>
</div>

</body>
</html>
