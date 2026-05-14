<?php
session_start();
include 'DBConn.php';

if(!isset($_SESSION['cart'])){
    $_SESSION['cart'] = [];
}

$message = "";
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])){
    $clothesID = intval($_POST['clothesID']);
    $quantity = max(1, intval($_POST['quantity']));

    $stmt = $conn->prepare("SELECT ClothesID, Name, Brand, Price, ImageURL FROM tblClothes WHERE ClothesID = ?");
    $stmt->bind_param('i', $clothesID);
    $stmt->execute();
    $itemResult = $stmt->get_result();

    if($itemResult && $itemResult->num_rows > 0){
        $item = $itemResult->fetch_assoc();

        if(isset($_SESSION['cart'][$clothesID])){
            $_SESSION['cart'][$clothesID]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$clothesID] = [
                'id' => $item['ClothesID'],
                'name' => $item['Name'],
                'brand' => $item['Brand'],
                'price' => floatval($item['Price']),
                'image' => $item['ImageURL'],
                'quantity' => $quantity,
            ];
        }

        $message = htmlspecialchars($item['Name']) . " has been added to your bag.";
    } else {
        $message = "Unable to add this item to the bag.";
    }

    $stmt->close();
}

$cartCount = 0;
foreach($_SESSION['cart'] as $cartItem){
    $cartCount += $cartItem['quantity'];
}

$sql = "SELECT * FROM tblClothes";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shop - Pastimes</title>
    <link rel="stylesheet" href="images/css/style.css">

    <style>
        .shop-container {
            padding: 140px 20px 40px;
            background: #ffffff;
            min-height: 100vh;
        }

        .shop-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 14px;
            max-width: 1400px;
            margin: 0 auto 24px;
        }

        .shop-header h2 {
            margin: 0;
            color: #333;
            font-size: 28px;
        }

        .cart-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 18px;
            border-radius: 999px;
            background: #dca35c;
            color: white;
            text-decoration: none;
            font-weight: 700;
            border: 2px solid transparent;
        }

        .cart-link:hover {
            background: #b7833c;
        }

        .success-message {
            max-width: 1400px;
            margin: 0 auto 20px;
            padding: 14px 18px;
            background: #e8f5e9;
            color: #25612b;
            border-radius: 15px;
            border: 1px solid #c3e6cb;
        }

        .products {
            display: flex;
            flex-wrap: wrap;
            gap: 40px;
            justify-content: center;
            max-width: 1400px;
            margin: 0 auto;
        }

        .product-card {
            background: #ffffff;
            width: 320px;
            border-radius: 8px;
            overflow: hidden;
            transition: transform 0.3s ease;
            box-shadow: 0 12px 28px rgba(0,0,0,0.08);
        }

        .product-card:hover {
            transform: translateY(-5px);
        }

        .product-image-container {
            position: relative;
            width: 100%;
            height: 320px;
            background: #f5f1ed;
            overflow: hidden;
        }

        .product-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .product-favorite {
            position: absolute;
            top: 16px;
            right: 16px;
            width: 40px;
            height: 40px;
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 20px;
        }

        .product-info {
            padding: 20px 16px;
        }

        .product-brand {
            font-size: 12px;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }

        .product-name {
            font-size: 18px;
            color: #333;
            margin-bottom: 12px;
            font-weight: 600;
        }

        .product-price {
            font-size: 16px;
            color: #cc9933;
            font-weight: 700;
            margin-bottom: 14px;
        }

        .product-btn {
            width: 100%;
            padding: 14px 16px;
            background: #f5f1ed;
            border: 2px solid #333;
            border-radius: 4px;
            color: #333;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }

        .product-btn:hover {
            background: #333;
            color: white;
        }

        .product-form {
            margin: 0;
        }
    </style>
</head>

<body>

<!-- NAVBAR -->
<nav>
    <div class="logo">Pastimes</div>

    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="shop.php" class="active">Shop</a>
        <a class="login-btn" href="logout.php">Logout</a>
        <a class="cart-link" href="cart.php">🛒 Bag (<?php echo $cartCount; ?>)</a>
    </div>
</nav>

<!-- SHOP -->
<div class="shop-container">
    <div class="shop-header">
        <h2>Available Clothes</h2>
        <a class="cart-link" href="cart.php">View Bag (<?php echo $cartCount; ?>)</a>
    </div>

    <?php if($message !== ""): ?>
        <p class="success-message"><?php echo $message; ?></p>
    <?php endif; ?>

    <div class="products">

        <?php
        if($result && $result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                $imageURL = !empty($row['ImageURL']) ? htmlspecialchars($row['ImageURL']) : 'images/homephoto.png';
                echo "
                <div class='product-card'>
                    <div class='product-image-container'>
                        <img src='" . $imageURL . "' alt='" . htmlspecialchars($row['Name']) . "'>
                        <div class='product-favorite'>♡</div>
                    </div>
                    <div class='product-info'>
                        <div class='product-brand'>" . htmlspecialchars($row['Brand']) . "</div>
                        <div class='product-name'>" . htmlspecialchars($row['Name']) . "</div>
                        <div class='product-price'>R " . htmlspecialchars(number_format($row['Price'], 2)) . "</div>
                        <form method='POST' class='product-form'>
                            <input type='hidden' name='clothesID' value='" . intval($row['ClothesID']) . "'>
                            <input type='hidden' name='quantity' value='1'>
                            <button class='product-btn' type='submit' name='add_to_cart'>Add to Bag</button>
                        </form>
                    </div>
                </div>
                ";
            }
        } else {
            echo "<p>No products available.</p>";
        }
        ?>

    </div>

</div>

</body>
</html>