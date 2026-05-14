<?php
session_start();
include 'DBConn.php';

if(!isset($_SESSION['admin'])){
    header("Location: adminLogin.php");
    exit();
}

if(isset($_POST['addUser'])){
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = md5($_POST['password']);

    $stmt = $conn->prepare("INSERT INTO tblUser (Name, Email, Username, Password, IsVerified, Role) VALUES (?, ?, ?, ?, 1, 'customer')");
    $stmt->bind_param('ssss', $name, $email, $username, $password);

    if($stmt->execute()){
        $message = "User added successfully!";
    } else {
        $message = "Error adding user: " . $conn->error;
    }
    $stmt->close();
}

if(isset($_POST['addProduct'])){
    $name = trim($_POST['product_name']);
    $brand = trim($_POST['product_brand']);
    $price = trim($_POST['product_price']);
    $condition = trim($_POST['product_condition']);
    $imageURL = trim($_POST['product_image']);

    if(empty($imageURL)){
        $imageURL = 'images/homephoto.png';
    }

    $stmt = $conn->prepare("INSERT INTO tblClothes (Name, Brand, Price, ConditionType, Username, ImageURL) VALUES (?, ?, ?, ?, 'admin', ?)");
    $stmt->bind_param('ssdss', $name, $brand, $price, $condition, $imageURL);

    if($stmt->execute()){
        $message = "Product added successfully!";
    } else {
        $message = "Error adding product: " . $conn->error;
    }
    $stmt->close();
}

$userResult = $conn->query("SELECT * FROM tblUser");
$productResult = $conn->query("SELECT * FROM tblClothes ORDER BY CreatedAt DESC");
$orderResult = $conn->query("SELECT * FROM tblOrder ORDER BY OrderDate DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="images/css/style.css">
</head>

<body>

<nav>
    <div class="logo">Pastimes</div>

    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="shop.php">Shop</a>
        <a href="logout.php">Logout</a>
    </div>
</nav>

<div class="admin-container">

    <div class="admin-card">
        <h2>Admin Dashboard</h2>
        <p>Welcome Admin: <?php echo htmlspecialchars($_SESSION['admin']); ?></p>

        <?php if(isset($message)) echo "<p class='success-message'>".htmlspecialchars($message)."</p>"; ?>

        <h3>Add New User</h3>

        <form method="POST" class="form-grid">
            <input type="text" name="name" placeholder="Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>

            <button type="submit" name="addUser">Add User</button>
        </form>

        <h3>Add New Product</h3>
        <form method="POST" class="form-grid">
            <input type="text" name="product_name" placeholder="Product Name" required>
            <input type="text" name="product_brand" placeholder="Brand" required>
            <input type="number" step="0.01" name="product_price" placeholder="Price" required>
            <input type="text" name="product_condition" placeholder="Condition" required>
            <input type="text" name="product_image" placeholder="Image URL" required>
            <button type="submit" name="addProduct">Add Product</button>
        </form>

        <h3>All Users</h3>

        <table class="admin-table">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Username</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>

            <?php
            $sql = "SELECT * FROM tblUser";
            $result = $conn->query($sql);

            if($result && $result->num_rows > 0){
                while($row = $result->fetch_assoc()){

                    $statusClass = ($row['IsVerified'] == 1) ? "status-verified" : "status-not";
                    $statusText = ($row['IsVerified'] == 1) ? "Verified" : "Not Verified";

                    echo "<tr>
                        <td>{$row['UserID']}</td>
                        <td>".htmlspecialchars($row['Name'])."</td>
                        <td>".htmlspecialchars($row['Email'])."</td>
                        <td>".htmlspecialchars($row['Username'])."</td>
                        <td class='$statusClass'>$statusText</td>
                        <td>
                            <a class='action-btn verify-btn' href='verify.php?id={$row['UserID']}'>Verify</a>
                            <a class='action-btn edit-btn' href='editUser.php?id={$row['UserID']}'>Edit</a>
                            <a class='action-btn delete-btn' href='deleteUser.php?id={$row['UserID']}' onclick='return confirm(\"Delete this user?\")'>Delete</a>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No users found.</td></tr>";
            }
            ?>
        </table>

        <h3>Inventory</h3>
        <table class="admin-table">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Brand</th>
                <th>Price</th>
                <th>Condition</th>
                <th>Seller</th>
                <th>Actions</th>
            </tr>
            <?php
            if($productResult && $productResult->num_rows > 0){
                while($product = $productResult->fetch_assoc()){
                    echo "<tr>
                        <td>".intval($product['ClothesID'])."</td>
                        <td>".htmlspecialchars($product['Name'])."</td>
                        <td>".htmlspecialchars($product['Brand'])."</td>
                        <td>R " . htmlspecialchars(number_format($product['Price'], 2)) . "</td>
                        <td>".htmlspecialchars($product['ConditionType'])."</td>
                        <td>".htmlspecialchars($product['Username'])."</td>
                        <td>
                            <a class='action-btn edit-btn' href='editProduct.php?id=".intval($product['ClothesID'])."'>Edit</a>
                            <a class='action-btn delete-btn' href='deleteProduct.php?id=".intval($product['ClothesID'])."' onclick='return confirm(\"Delete this product?\")'>Delete</a>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No products found.</td></tr>";
            }
            ?>
        </table>

        <h3>Orders</h3>
        <table class="admin-table">
            <tr>
                <th>Order ID</th>
                <th>User</th>
                <th>Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            <?php
            if($orderResult && $orderResult->num_rows > 0){
                while($order = $orderResult->fetch_assoc()){
                    $statusClass = strtolower($order['Status']) === 'delivered' ? 'status-verified' : 'status-not';
                    echo "<tr>
                        <td>".intval($order['OrderID'])."</td>
                        <td>".htmlspecialchars($order['UserID'])."</td>
                        <td>".htmlspecialchars($order['OrderDate'])."</td>
                        <td class='$statusClass'>".htmlspecialchars($order['Status'])."</td>
                        <td>
                            <a class='action-btn verify-btn' href='updateOrder.php?id=".intval($order['OrderID'])."&status=Pending'>Pending</a>
                            <a class='action-btn edit-btn' href='updateOrder.php?id=".intval($order['OrderID'])."&status=Shipped'>Shipped</a>
                            <a class='action-btn delete-btn' href='updateOrder.php?id=".intval($order['OrderID'])."&status=Delivered'>Delivered</a>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No orders available.</td></tr>";
            }
            ?>
        </table>

    </div>

</div>

</body>
</html>