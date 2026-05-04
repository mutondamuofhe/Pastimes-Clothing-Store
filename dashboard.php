<?php
session_start();

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Pastimes</title>
    <link rel="stylesheet" href="images/css/style.css">
</head>

<body>

<!-- NAVBAR -->
<nav>
    <div class="logo">Pastimes</div>

    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="shop.php">Shop</a>
        <a class="login-btn" href="logout.php">Logout</a>
    </div>
</nav>

<!-- CONTENT -->
<div class="hero">
    <div class="hero-content">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['user']); ?> 👋</h1>
        <p>Start exploring or sell your clothes.</p>

        <a href="shop.php" class="btn btn-primary">Browse Clothes</a>
        <a href="upload.php" class="btn btn-secondary">Sell Items</a>
    </div>
</div>

</body>
</html>