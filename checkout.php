<?php
session_start();
require "config/db.php";

$user = $_SESSION['user_id'];

// Fetch cart items
$q = $conn->query("
    SELECT cart.*, products.name, products.image, products.price 
    FROM cart 
    JOIN products ON cart.product_id = products.id
    WHERE cart.user_id = $user
");

$total = 0;
$items = [];
while ($r = $q->fetch_assoc()) {
    $items[] = $r;
    $total += $r['price'] * $r['quantity'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Checkout</title>

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

body {
    min-height: 100vh;
    background: radial-gradient(circle at top, #1c2b4a, #020617);
    display: flex;
    justify-content: center;
    align-items: center;
    color: #fff;
}

.checkout-container {
    background: linear-gradient(145deg, #0b1220, #020617);
    width: 520px;
    padding: 30px;
    border-radius: 18px;
    box-shadow: 0 20px 50px rgba(0,0,0,0.6);
}

.checkout-container h2 {
    text-align: center;
    margin-bottom: 20px;
    font-size: 28px;
}

.item {
    display: flex;
    align-items: center;
    background: rgba(255,255,255,0.05);
    padding: 10px;
    border-radius: 12px;
    margin-bottom: 10px;
}

.item img {
    width: 60px;
    height: 60px;
    border-radius: 10px;
    object-fit: cover;
    margin-right: 10px;
}

.item-details {
    flex: 1;
}

.item-details p {
    font-size: 14px;
}

.total-box {
    background: rgba(255,255,255,0.08);
    padding: 15px;
    border-radius: 12px;
    font-size: 20px;
    margin: 20px 0;
    text-align: center;
}

.total-box span {
    color: #22c55e;
    font-weight: bold;
}

.qr-box {
    text-align: center;
    margin-bottom: 20px;
}

.qr-box img {
    width: 180px;
    border-radius: 12px;
    margin-top: 10px;
}

form textarea,
form input[type="text"],
form input[type="file"] {
    width: 100%;
    margin-bottom: 15px;
    padding: 10px;
    border-radius: 10px;
    border: none;
    outline: none;
}

.pay-btn {
    background: #22c55e;
    color: #fff;
    border: none;
    padding: 14px;
    font-size: 16px;
    border-radius: 50px;
    cursor: pointer;
    width: 100%;
    transition: 0.3s ease;
}

.pay-btn:hover {
    background: #16a34a;
    transform: scale(1.03);
}

.back-btn {
    display: block;
    margin-top: 15px;
    text-align: center;
    text-decoration: none;
    color: #60a5fa;
    font-size: 14px;
}

.back-btn:hover {
    text-decoration: underline;
}
</style>
</head>

<body>

<div class="checkout-container">
    <h2>Checkout</h2>

    <!-- CART ITEMS -->
    <?php foreach ($items as $item): ?>
    <div class="item">
        <img src="uploads/<?php echo $item['image']; ?>">
        <div class="item-details">
            <p><b><?php echo $item['name']; ?></b></p>
            <p>Qty: <?php echo $item['quantity']; ?></p>
            <p>Price: Rs. <?php echo $item['price']; ?></p>
        </div>
    </div>
    <?php endforeach; ?>

    <!-- TOTAL -->
    <div class="total-box">
        Total Amount: <span>Rs. <?php echo $total; ?></span>
    </div>

    <!-- QR PAYMENT -->
    <div class="qr-box">
        <p><b>Scan QR & Pay Exact Amount</b></p>
        <img src="images/qr.jpeg" alt="QR Code">
        <p style="font-size:13px;margin-top:8px;">Upload payment screenshot below</p>
    </div>

    <!-- ORDER FORM -->
    <form action="place_order.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="total" value="<?php echo $total; ?>">

        <!-- ✅ ONLY ADDED FIELD -->
        <input type="text" name="phone" placeholder="Enter phone number" required>

        <textarea name="address" placeholder="Enter delivery address..." required></textarea>

        <input type="file" name="payment_proof" accept="image/*" required>

        <button type="submit" class="pay-btn">Submit Order</button>
    </form>

    <a href="cart.php" class="back-btn">← Back to Cart</a>
</div>

</body>
</html>
