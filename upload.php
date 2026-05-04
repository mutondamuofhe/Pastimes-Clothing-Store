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

    $name = $_POST['name'];
    $brand = $_POST['brand'];
    $price = $_POST['price'];
    $condition = $_POST['condition'];
    $username = $_SESSION['user'];

    // IMAGE UPLOAD
    $imageName = $_FILES['image']['name'];
    $tempName = $_FILES['image']['tmp_name'];

    $folder = "images/" . $imageName;

    move_uploaded_file($tempName, $folder);

    // INSERT INTO DATABASE
    $sql = "INSERT INTO tblClothes (Name, Brand, Price, ConditionType, Username, ImageURL)
            VALUES ('$name','$brand','$price','$condition','$username','$folder')";

    if($conn->query($sql)){
        $message = "Product uploaded successfully!";
    } else {
        $message = "Error: " . $conn->error;
    }
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

    <label>Upload Image</label>
    <input type="file" name="image" required>

    <button type="submit" name="upload" class="btn btn-primary">Upload Product</button>

</form>

    </div>
</div>

</body>
</html>