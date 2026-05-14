<?php
session_start();
include 'DBConn.php';

// 🚨 Only logged in users allowed
if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$message = "";

if(isset($_POST['upload'])){

    $name = trim($_POST['name']);
    $brand = trim($_POST['brand']);
    $price = trim($_POST['price']);
    $condition = trim($_POST['condition']);
    $description = trim($_POST['description']);
    $username = $_SESSION['user'];

    $imageName = basename($_FILES['image']['name']);
    $tempName = $_FILES['image']['tmp_name'];
    $folder = "images/" . $imageName;
    $imageURL = 'images/homephoto.png';

    if(!empty($imageName) && move_uploaded_file($tempName, $folder)){
        $imageURL = $folder;
    }

    $descColumn = false;
    $columnResult = $conn->query("SHOW COLUMNS FROM tblClothes LIKE 'Description'");
    if($columnResult && $columnResult->num_rows === 0){
        $conn->query("ALTER TABLE tblClothes ADD Description TEXT NULL");
    }
    $checkDesc = $conn->query("SHOW COLUMNS FROM tblClothes LIKE 'Description'");
    if($checkDesc && $checkDesc->num_rows > 0){
        $descColumn = true;
    }

    if($descColumn){
        $stmt = $conn->prepare("INSERT INTO tblClothes (Name, Brand, Price, ConditionType, Username, ImageURL, Description) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssdssss', $name, $brand, $price, $condition, $username, $imageURL, $description);
    } else {
        $stmt = $conn->prepare("INSERT INTO tblClothes (Name, Brand, Price, ConditionType, Username, ImageURL) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssdsss', $name, $brand, $price, $condition, $username, $imageURL);
    }

    if($stmt->execute()){
        $message = "Product uploaded successfully!";
    } else {
        $message = "Error: " . $conn->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload Product</title>
    <link rel="stylesheet" href="images/css/style.css">
</head>

<body class="upload-page">

<nav>
    <div class="logo">Pastimes</div>
    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="shop.php">Shop</a>
        <a class="login-btn" href="logout.php">Logout</a>
    </div>
</nav>

<div class="upload-page-content">
    <div class="upload-box">

<h2>Upload Your Clothing</h2>

<?php if(!empty($message)) echo "<p class='success-message'>" . htmlspecialchars($message) . "</p>"; ?>

<form method="POST" enctype="multipart/form-data" class="upload-form">

    <label>Product Name</label>
    <input type="text" name="name" required>

    <label>Brand</label>
    <input type="text" name="brand" required>

    <label>Price</label>
    <input type="number" name="price" required>

    <label>Condition</label>
    <input type="text" name="condition" placeholder="e.g. Good, Like New" required>

    <label>Description</label>
    <input type="text" name="description" placeholder="Add a short description" required>

    <label>Upload Image</label>
    <input type="file" name="image" required>

    <button type="submit" name="upload" class="btn btn-primary">Upload Product</button>

</form>

    </div>
</div>

</body>
</html>