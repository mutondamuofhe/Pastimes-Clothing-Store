<?php
session_start();
include 'DBConn.php';

if(!isset($_SESSION['order_confirmation'])){
    header('Location: shop.php');
    exit();
}

$order = $_SESSION['order_confirmation'];
unset($_SESSION['order_confirmation']);
$cartCount = isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'quantity')) : 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Confirmation - Pastimes</title>
    <link rel="stylesheet" href="images/css/style.css">
    <style>
        .confirmation-page { padding-top: 120px; min-height: 100vh; background: #f7f0e8; }
        .confirmation-card { width: 90%; max-width: 700px; margin: 0 auto 40px; background: #fffdfb; padding: 34px; border-radius: 24px; box-shadow: 0 18px 45px rgba(0,0,0,0.08); }
        .confirmation-card h2 { margin-top: 0; color: #5b4031; }
        .confirmation-card p { line-height: 1.7; color: #5b4031; }
        .confirmation-actions { margin-top: 28px; display: flex; gap: 14px; flex-wrap: wrap; }
    </style>
</head>
<body class="confirmation-page">

<nav>
    <div class="logo">Pastimes</div>
    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="shop.php">Shop</a>
        <a class="cart-link" href="cart.php">🛒 Bag (<?php echo intval($cartCount); ?>)</a>
        <a class="login-btn" href="logout.php">Logout</a>
    </div>
</nav>

<div class="confirmation-card">
    <h2>Thank you for your order!</h2>
    <p>Your order <strong>#<?php echo intval($order['id']); ?></strong> is confirmed.</p>
    <p>Total amount: <strong>R <?php echo htmlspecialchars($order['total']); ?></strong></p>
    <p>Order date: <strong><?php echo htmlspecialchars($order['date']); ?></strong></p>
    <p>We will notify you once the order status is updated. For now, you can continue shopping or view your bag.</p>

    <div class="confirmation-actions">
        <a class="btn btn-primary" href="shop.php">Continue Shopping</a>
        <a class="btn btn-secondary" href="shop.php">View Shop</a>
    </div>
</div>

</body>
</html>
