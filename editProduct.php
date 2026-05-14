<?php
session_start();
include 'DBConn.php';

if(!isset($_SESSION['admin'])){
    header('Location: adminLogin.php');
    exit();
}

$id = intval($_GET['id']);
if($id <= 0){
    header('Location: adminDashboard.php');
    exit();
}

$stmt = $conn->prepare("SELECT * FROM tblClothes WHERE ClothesID = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
$stmt->close();

if(!$product){
    header('Location: adminDashboard.php');
    exit();
}

$message = "";
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $name = trim($_POST['name']);
    $brand = trim($_POST['brand']);
    $price = trim($_POST['price']);
    $condition = trim($_POST['condition']);
    $imageURL = trim($_POST['imageURL']);

    if(empty($imageURL)){
        $imageURL = 'images/homephoto.png';
    }

    $stmt = $conn->prepare("UPDATE tblClothes SET Name = ?, Brand = ?, Price = ?, ConditionType = ?, ImageURL = ? WHERE ClothesID = ?");
    $stmt->bind_param('ssdssi', $name, $brand, $price, $condition, $imageURL, $id);

    if($stmt->execute()){
        $message = 'Product updated successfully.';
        header('Location: adminDashboard.php');
        exit();
    } else {
        $message = 'Unable to update product.';
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Product - Pastimes</title>
    <link rel="stylesheet" href="images/css/style.css">
</head>
<body class="upload-page">

<nav>
    <div class="logo">Pastimes</div>
    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="adminDashboard.php">Dashboard</a>
        <a class="login-btn" href="logout.php">Logout</a>
    </div>
</nav>

<div class="upload-page-content">
    <div class="upload-box">
        <h2>Edit Product</h2>
        <?php if($message): ?>
            <p class="error-message"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <form method="POST" class="upload-form">
            <label>Product Name</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($product['Name']); ?>" required>

            <label>Brand</label>
            <input type="text" name="brand" value="<?php echo htmlspecialchars($product['Brand']); ?>" required>

            <label>Price</label>
            <input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($product['Price']); ?>" required>

            <label>Condition</label>
            <input type="text" name="condition" value="<?php echo htmlspecialchars($product['ConditionType']); ?>" required>

            <label>Image URL</label>
            <input type="text" name="imageURL" value="<?php echo htmlspecialchars($product['ImageURL']); ?>" required>

            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
    </div>
</div>

</body>
</html>
