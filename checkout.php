<?php
session_start();
include 'DBConn.php';

if(!isset($_SESSION['cart']) || empty($_SESSION['cart'])){
    header('Location: shop.php');
    exit();
}

if(!isset($_SESSION['user'])){
    $_SESSION['redirect_after_login'] = 'checkout.php';
    header('Location: login.php');
    exit();
}

$message = "";
$success = "";
$orderId = null;

$cartItems = $_SESSION['cart'];
$total = 0;
$cartCount = 0;
foreach($cartItems as $item){
    $total += $item['price'] * $item['quantity'];
    $cartCount += intval($item['quantity']);
}

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_order'])){
    $username = $_SESSION['user'];
    $orderDate = date('Y-m-d');
    $status = 'Pending';

    $stmt = $conn->prepare("INSERT INTO tblOrder (UserID, OrderDate, Status) VALUES (?, ?, ?)");
    $stmt->bind_param('sss', $username, $orderDate, $status);
    if($stmt->execute()){
        $orderId = $stmt->insert_id;
        $_SESSION['order_confirmation'] = [
            'id' => intval($orderId),
            'total' => number_format($total, 2),
            'date' => $orderDate,
        ];
        unset($_SESSION['cart']);
        $stmt->close();
        header('Location: orderConfirmation.php');
        exit();
    } else {
        $message = "Unable to place order: " . $conn->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout - Pastimes</title>
    <link rel="stylesheet" href="images/css/style.css">
    <style>
        .checkout-page { padding-top: 120px; min-height: 100vh; background: #f7f0e8; }
        .checkout-container { width: 90%; max-width: 900px; margin: 0 auto 40px; }
        .checkout-card { background: #fffdfb; padding: 36px; border-radius: 24px; box-shadow: 0 18px 45px rgba(0,0,0,0.08); }
        .checkout-card h2 { margin-top: 0; color: #5b4031; }
        .checkout-summary { margin-top: 24px; }
        .checkout-summary p { margin: 10px 0; }
        .checkout-summary strong { color: #5b4031; }
        .checkout-actions { margin-top: 28px; display: flex; gap: 14px; flex-wrap: wrap; }
    </style>
</head>
<body class="checkout-page">

<nav>
    <div class="logo">Pastimes</div>
    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="shop.php">Shop</a>
        <a class="cart-link" href="cart.php">🛒 Bag (<?php echo $cartCount; ?>)</a>
        <a class="login-btn" href="logout.php">Logout</a>
    </div>
</nav>

<div class="checkout-container">
    <div class="checkout-card">
        <h2>Checkout</h2>

        <?php if($message !== ""): ?>
            <p class="error-message"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <?php if($success !== ""): ?>
            <p class="success-message"><?php echo htmlspecialchars($success); ?></p>
            <a href="shop.php" class="btn btn-primary">Continue Shopping</a>
        <?php else: ?>
            <div class="checkout-summary">
                <p><strong>Buyer:</strong> <?php echo htmlspecialchars($_SESSION['user']); ?></p>
                <p><strong>Items:</strong> <?php echo count($cartItems); ?></p>
                <p><strong>Total:</strong> R <?php echo number_format($total, 2); ?></p>
                <p><strong>Order Date:</strong> <?php echo date('Y-m-d'); ?></p>
            </div>

            <form method="POST">
                <div class="checkout-actions">
                    <button type="submit" name="confirm_order" class="btn btn-primary">Confirm Order</button>
                    <a href="cart.php" class="btn btn-secondary">Back to Bag</a>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
