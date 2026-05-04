<?php
session_start();
include 'DBConn.php';

$message = "";

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if(empty($username) || empty($password)){
        $message = "Please enter both username and password.";
    } else {
        $stmt = $conn->prepare("SELECT AdminID, Username, Password FROM tblAdmin WHERE Username = ?");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result && $result->num_rows > 0){
            $row = $result->fetch_assoc();

            if($row['Password'] === md5($password)){
                $_SESSION['admin'] = $row['Username'];
                header("Location: adminDashboard.php");
                exit();
            } else {
                $message = "Incorrect password.";
            }
        } else {
            $message = "Admin not found.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <link rel="stylesheet" href="images/css/style.css">
</head>
<body class="login-page">

<nav>
    <div class="logo">Pastimes</div>

    <div class="nav-links">
        <a href="index.php">Home</a>
        <a class="login-btn" href="login.php">User Login</a>
    </div>
</nav>

<div class="login-box">
    <h2>Admin Login</h2>

    <?php if($message != "") echo "<p class='error-message'>".htmlspecialchars($message)."</p>"; ?>

    <form method="POST">
        <label for="username">Username</label>
        <input id="username" type="text" name="username" placeholder="Username" required>

        <label for="password">Password</label>
        <input id="password" type="password" name="password" placeholder="Password" required>

        <button type="submit">Login</button>
    </form>

    <p class="auth-footer"><a href="login.php">User Login</a></p>
</div>

</body>
</html>