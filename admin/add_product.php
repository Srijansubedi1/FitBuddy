<?php
session_start();
require "../config/db.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

if (isset($_POST['add'])) {
    $name  = $_POST['name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $desc  = $_POST['description'];

    $img = $_FILES['image']['name'];
    $tmp = $_FILES['image']['tmp_name'];
    move_uploaded_file($tmp, "../uploads/" . $img);

    $conn->query("
        INSERT INTO products (name, description, price, stock, image)
        VALUES ('$name','$desc','$price','$stock','$img')
    ");

    $msg = "✅ Product Added Successfully";
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Admin | Add Product</title>
  <link rel="stylesheet" href="admin.css">

  <!-- EXTRA CSS ONLY FOR THIS PAGE -->
  <style>
    .add-product-wrapper {
      max-width: 520px;
      margin: 60px auto;
      background: linear-gradient(145deg, #0b0f1a, #060914);
      border-radius: 16px;
      padding: 30px;
      box-shadow: 0 0 25px rgba(0,255,150,0.12);
      border: 1px solid rgba(0,255,150,0.15);
    }

    .add-product-wrapper h2 {
      color: #2dff9b;
      text-align: center;
      margin-bottom: 5px;
    }

    .add-product-wrapper p.sub {
      text-align: center;
      color: #9aa4b2;
      font-size: 14px;
      margin-bottom: 25px;
    }

    .form-group {
      margin-bottom: 16px;
    }

    .form-group label {
      display: block;
      font-size: 13px;
      color: #b8c1d1;
      margin-bottom: 6px;
    }

    .form-control {
      width: 100%;
      padding: 12px 14px;
      background: #0f1424;
      border: 1px solid #1f2a44;
      border-radius: 8px;
      color: #fff;
      font-size: 14px;
      outline: none;
      transition: 0.3s;
    }

    .form-control:focus {
      border-color: #2dff9b;
      box-shadow: 0 0 0 2px rgba(45,255,155,0.15);
    }

    textarea.form-control {
      resize: none;
      height: 90px;
    }

    .file-input {
      background: #0f1424;
      padding: 10px;
      border-radius: 8px;
      border: 1px dashed #2dff9b;
      cursor: pointer;
    }

    .btn-submit {
      width: 100%;
      padding: 12px;
      background: linear-gradient(135deg, #1fd17a, #2dff9b);
      border: none;
      border-radius: 10px;
      font-weight: bold;
      font-size: 15px;
      color: #04110b;
      cursor: pointer;
      transition: 0.3s;
      margin-top: 10px;
    }

    .btn-submit:hover {
      transform: translateY(-1px);
      box-shadow: 0 8px 20px rgba(45,255,155,0.3);
    }

    .success-msg {
      background: rgba(45,255,155,0.12);
      border: 1px solid rgba(45,255,155,0.4);
      color: #2dff9b;
      padding: 10px;
      border-radius: 8px;
      text-align: center;
      margin-bottom: 15px;
      font-size: 14px;
    }

    .back-link {
      text-align: center;
      margin-top: 15px;
    }

    .back-link a {
      color: #9aa4b2;
      font-size: 13px;
      text-decoration: none;
    }

    .back-link a:hover {
      color: #2dff9b;
    }
  </style>
</head>

<body>

<div class="add-product-wrapper">

  <h2>Add New Product</h2>
  <p class="sub">Fill product details below</p>

  <?php if(isset($msg)): ?>
    <div class="success-msg"><?= $msg ?></div>
  <?php endif; ?>

  <form method="POST" enctype="multipart/form-data">

    <div class="form-group">
      <label>Product Name</label>
      <input type="text" name="name" class="form-control" required>
    </div>

    <div class="form-group">
      <label>Description</label>
      <textarea name="description" class="form-control"></textarea>
    </div>

    <div class="form-group">
      <label>Price (Rs.)</label>
      <input type="number" name="price" class="form-control" required>
    </div>

    <div class="form-group">
      <label>Stock Quantity</label>
      <input type="number" name="stock" class="form-control" required>
    </div>

    <div class="form-group">
      <label>Product Image</label>
      <input type="file" name="image" class="form-control file-input" required>
    </div>

    <button type="submit" name="add" class="btn-submit">
      + Add Product
    </button>

  </form>

  <div class="back-link">
    <a href="products.php">← Back to Products</a>
  </div>

</div>

</body>
</html>
