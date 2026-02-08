<?php
session_start();
require "config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

/* ---------------- UPDATE QUANTITY ---------------- */
if (isset($_GET['update']) && isset($_GET['cart_id'])) {
    $cart_id = (int)$_GET['cart_id'];
    $type = $_GET['update'];

    if ($type === "plus") {
        $conn->query("UPDATE cart SET quantity = quantity + 1 WHERE id = $cart_id AND user_id = $user_id");
    }

    if ($type === "minus") {
        $conn->query("UPDATE cart SET quantity = GREATEST(quantity - 1, 1) WHERE id = $cart_id AND user_id = $user_id");
    }

    header("Location: cart.php");
    exit;
}

/* ---------------- REMOVE ITEM ---------------- */
if (isset($_GET['remove'])) {
    $cart_id = (int)$_GET['remove'];
    $conn->query("DELETE FROM cart WHERE id = $cart_id AND user_id = $user_id");
    header("Location: cart.php");
    exit;
}

/* ---------------- FETCH CART ---------------- */
$cart = $conn->query("
    SELECT cart.id AS cart_id, cart.quantity,
           products.name, products.price, products.image
    FROM cart
    JOIN products ON cart.product_id = products.id
    WHERE cart.user_id = $user_id
");

$total = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Your Cart</title>
<link rel="stylesheet" href="css/styles.css">

<style>
/* ONLY CART-SPECIFIC — NO DESIGN CHANGE */
.cart-container { max-width: 1100px; margin: auto; }
.cart-header, .cart-row {
    display: grid;
    grid-template-columns: 100px 1fr 200px 150px 100px;
    align-items: center;
    padding: 15px;
}
.cart-header {
    background: linear-gradient(90deg,#2563eb,#1e40af);
    color: #fff;
    border-radius: 12px;
    font-weight: 600;
}
.cart-row {
    margin-top: 15px;
    background: #0b1220;
    border-radius: 15px;
}
.cart-row img {
    width: 70px;
    border-radius: 10px;
}
.qty-box {
    display: flex;
    align-items: center;
    gap: 12px;
}
.qty-box a {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #1e293b;
    color: #fff;
    display: flex;
    justify-content: center;
    align-items: center;
    text-decoration: none;
    font-weight: bold;
}
.remove {
    color: #ef4444;
    text-decoration: none;
}
.total-box {
    display: flex;
    justify-content: space-between;
    margin-top: 40px;
    align-items: center;
}
.btn {
    padding: 12px 30px;
    border-radius: 30px;
    text-decoration: none;
    font-weight: 600;
}
.btn-back { background: #ec4899; color: #fff; }
.btn-checkout { background: #2563eb; color: #fff; }
.price { color: #22c55e; font-weight: 600; }
</style>
</head>

<body>



<h2 style="text-align:center;margin:40px 0;">Your Cart</h2>

<div class="cart-container">

    <div class="cart-header">
        <div>Image</div>
        <div>Product</div>
        <div>Quantity</div>
        <div>Price</div>
        <div>Action</div>
    </div>

    <?php if ($cart->num_rows == 0): ?>
        <p style="text-align:center;margin-top:40px;">Your cart is empty.</p>
    <?php endif; ?>

    <?php while ($row = $cart->fetch_assoc()): 
        $sub = $row['price'] * $row['quantity'];
        $total += $sub;
    ?>
    <div class="cart-row">
        <div>
            <img src="uploads/<?= htmlspecialchars($row['image']) ?>">
        </div>
        <div><?= htmlspecialchars($row['name']) ?></div>
        <div class="qty-box">
            <a href="?update=minus&cart_id=<?= $row['cart_id'] ?>">−</a>
            <?= $row['quantity'] ?>
            <a href="?update=plus&cart_id=<?= $row['cart_id'] ?>">+</a>
        </div>
        <div class="price">Rs. <?= number_format($sub) ?></div>
        <div>
            <a class="remove" href="?remove=<?= $row['cart_id'] ?>">Remove</a>
        </div>
    </div>
    <?php endwhile; ?>

    <div class="total-box">
        <a href="products.php" class="btn btn-back">← Continue Shopping</a>
        <h3>Total: Rs. <?= number_format($total) ?></h3>
        <a href="checkout.php" class="btn btn-checkout">Checkout</a>
    </div>

</div>

</body>
</html>
