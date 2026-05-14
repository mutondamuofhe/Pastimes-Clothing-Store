<?php
session_start();
include 'DBConn.php';

if(!isset($_SESSION['cart'])){
    $_SESSION['cart'] = [];
}

$message = "";
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_POST['update_cart']) && isset($_POST['quantities']) && is_array($_POST['quantities'])){
        foreach($_POST['quantities'] as $id => $quantity){
            $id = intval($id);
            $quantity = max(0, intval($quantity));
            if(isset($_SESSION['cart'][$id])){
                if($quantity === 0){
                    unset($_SESSION['cart'][$id]);
                } else {
                    $_SESSION['cart'][$id]['quantity'] = $quantity;
                }
            }
        }
        $message = "Your cart has been updated.";
    }

    if(isset($_POST['remove_item'])){
        $removeId = intval($_POST['remove_item']);
        if(isset($_SESSION['cart'][$removeId])){
            unset($_SESSION['cart'][$removeId]);
            $message = "Item removed from your bag.";
        }
    }
}

$cartItems = $_SESSION['cart'];
$total = 0;
$cartCount = 0;
foreach($cartItems as $item){
    $total += $item['price'] * $item['quantity'];
    $cartCount += intval($item['quantity']);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Bag - Pastimes</title>
    <link rel="stylesheet" href="images/css/style.css">
    <style>
        .cart-page { padding-top: 120px; min-height: 100vh; background: #f7f0e8; }
        .cart-container { width: 90%; max-width: 1120px; margin: 0 auto 40px; }
        .cart-card { background: #fffdfb; padding: 32px; border-radius: 24px; box-shadow: 0 18px 45px rgba(0,0,0,0.08); }
        .cart-card h2 { margin-top: 0; color: #5b4031; }
        .cart-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .cart-table th, .cart-table td { padding: 16px 14px; border-bottom: 1px solid #eee; text-align: left; }
        .cart-table th { background: #f3e3d8; color: #5b4031; }
        .cart-item-image { width: 80px; height: 80px; object-fit: cover; border-radius: 12px; }
        .cart-actions { display: flex; justify-content: space-between; flex-wrap: wrap; gap: 12px; margin-top: 24px; }
        .cart-actions .btn { min-width: 180px; }
        .cart-summary { margin-top: 24px; text-align: right; font-weight: 700; color: #5b4031; }
        .quantity-input { width: 70px; padding: 8px 10px; border: 1px solid #d7cabf; border-radius: 10px; }
        .remove-button { background: #e74c3c; color: white; border: none; padding: 10px 14px; border-radius: 10px; cursor: pointer; }
        .remove-button:hover { opacity: 0.9; }
    </style>
</head>
<body class="cart-page">

<nav>
    <div class="logo">Pastimes</div>
    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="shop.php">Shop</a>
        <a class="cart-link" href="cart.php">🛒 Bag (<?php echo $cartCount; ?>)</a>
        <a class="login-btn" href="logout.php">Logout</a>
    </div>
</nav>

<div class="cart-container">
    <div class="cart-card">
        <h2>Your Shopping Bag</h2>

        <?php if($message !== ""): ?>
            <p class="success-message"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <?php if(empty($cartItems)): ?>
            <p>Your bag is empty.</p>
            <a href="shop.php" class="btn btn-primary">Continue Shopping</a>
        <?php else: ?>

            <form method="POST">
                <table class="cart-table">
                    <tr>
                        <th>Item</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th>Action</th>
                    </tr>
                    <?php foreach($cartItems as $item): ?>
                        <tr>
                            <td>
                                <div style="display:flex; align-items:center; gap:14px;">
                                    <img class="cart-item-image" src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                                    <div>
                                        <strong><?php echo htmlspecialchars($item['name']); ?></strong><br>
                                        <span><?php echo htmlspecialchars($item['brand']); ?></span>
                                    </div>
                                </div>
                            </td>
                            <td>R <?php echo number_format($item['price'], 2); ?></td>
                            <td>
                                <input type="number" class="quantity-input" name="quantities[<?php echo intval($item['id']); ?>]" value="<?php echo intval($item['quantity']); ?>" min="0">
                            </td>
                            <td>R <?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                            <td>
                                <button type="submit" name="remove_item" value="<?php echo intval($item['id']); ?>" class="remove-button">Remove</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <div class="cart-actions">
                    <button type="submit" name="update_cart" class="btn btn-secondary">Update Cart</button>
                    <a href="shop.php" class="btn btn-secondary">Continue Shopping</a>
                    <a href="checkout.php" class="btn btn-primary">Checkout</a>
                </div>
            </form>
            <div class="cart-summary">
                Total: R <?php echo number_format($total, 2); ?>
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
