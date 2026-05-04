<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'DBConn.php';

$username = "";
$email = "";
$message = "";

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if(empty($username) || empty($email) || empty($password)){
        $message = "Please fill in all fields.";
    } else {
        $stmt = $conn->prepare("SELECT UserID, Name, Email, Username, Password, IsVerified FROM tblUser WHERE Username = ? AND Email = ?");
        $stmt->bind_param('ss', $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result && $result->num_rows > 0){
            $row = $result->fetch_assoc();

            if($row['IsVerified'] == 0){
                $message = "Account not verified by admin.";
            } elseif($row['Password'] !== md5($password)){
                $message = "Incorrect password.";
            } else {
                $_SESSION['user'] = $row['Username'];
                header("Location: dashboard.php");
                exit();
            }
        } else {
            $message = "User does not exist. Please register.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Pastimes</title>
    <link rel="stylesheet" href="images/css/style.css">
</head>

<body class="login-page">

<nav>
    <div class="logo">Pastimes</div>

    <div class="nav-links">
        <a href="index.php">Home</a>
        <a class="login-btn" href="login.php">Login</a>
    </div>
</nav>

<div class="login-box">
    <h2>Login</h2>

    <?php if($message != "") echo "<p class='error-message'>".htmlspecialchars($message)."</p>"; ?>

    <form method="POST">
        <label for="username">Username</label>
        <input id="username" type="text" name="username" placeholder="Username"
            value="<?php echo htmlspecialchars($username); ?>" required>

        <label for="email">Email</label>
        <input id="email" type="email" name="email" placeholder="Email"
            value="<?php echo htmlspecialchars($email); ?>" required>

        <label for="password">Password</label>
        <input id="password" type="password" name="password" placeholder="Password" required>

        <button type="submit">Login</button>
    </form>

    <p class="auth-footer">Don't have an account? <a href="register.php">Register</a></p>
    <p class="auth-footer">Are you an admin? <a href="adminLogin.php">Admin Login</a></p>
</div>

</body>
</html>