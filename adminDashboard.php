<?php
session_start();
include 'DBConn.php';

if(!isset($_SESSION['admin'])){
    header("Location: adminLogin.php");
    exit();
}

if(isset($_POST['addUser'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    $sql = "INSERT INTO tblUser (Name, Email, Username, Password, IsVerified, Role)
            VALUES ('$name','$email','$username','$password',1,'user')";

    if($conn->query($sql)){
        $message = "User added successfully!";
    } else {
        $message = "Error: " . $conn->error;
    }
}
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

    </div>

</div>

</body>
</html>