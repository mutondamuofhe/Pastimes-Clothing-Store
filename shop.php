<?php
session_start();
include 'DBConn.php';

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
            padding: 100px 20px;
            background: #ffffff;
            min-height: 100vh;
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
        }

        .product-card:hover {
            transform: translateY(-5px);
        }

        .product-image-container {
            position: relative;
            width: 100%;
            height: 380px;
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
            font-size: 16px;
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

        .product-options {
            display: flex;
            gap: 8px;
            margin-bottom: 16px;
            flex-wrap: wrap;
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
    </div>
</nav>

<!-- SHOP -->
<div class="shop-container">

    <h2 style="text-align:center; color: #333; margin-bottom: 40px; font-size: 28px;">Available Clothes</h2>

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
                        <button class='product-btn'>Add to Bag</button>
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