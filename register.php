<?php
session_start();
include 'DBConn.php';

$name = "";
$email = "";
$username = "";
$message = "";

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if(empty($name) || empty($email) || empty($username) || empty($password)){
        $message = "All fields are required.";
    } else {
        $check = $conn->prepare("SELECT UserID FROM tblUser WHERE Email = ? OR Username = ?");
        $check->bind_param('ss', $email, $username);
        $check->execute();
        $check->store_result();

        if($check->num_rows > 0){
            $message = "Email or username is already registered.";
        } else {
            $hashPassword = md5($password);
            $insert = $conn->prepare("INSERT INTO tblUser (Name, Email, Username, Password, Role, IsVerified) VALUES (?, ?, ?, ?, 'customer', 0)");
            $insert->bind_param('ssss', $name, $email, $username, $hashPassword);

            if($insert->execute()){
                $message = "Registration successful. Your account is pending admin verification.";
                $name = $email = $username = "";
            } else {
                $message = "Error registering user: " . $conn->error;
            }
        }

        $check->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - Pastimes</title>
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
    <h2>Register</h2>

    <?php if($message != "") echo "<p class='error-message'>".htmlspecialchars($message)."</p>"; ?>

    <form method="POST">
        <label for="name">Name</label>
        <input id="name" type="text" name="name" placeholder="Name"
            value="<?php echo htmlspecialchars($name); ?>" required>

        <label for="email">Email</label>
        <input id="email" type="email" name="email" placeholder="Email"
            value="<?php echo htmlspecialchars($email); ?>" required>

        <label for="username">Username</label>
        <input id="username" type="text" name="username" placeholder="Username"
            value="<?php echo htmlspecialchars($username); ?>" required>

        <label for="password">Password</label>
        <input id="password" type="password" name="password" placeholder="Password" required>

        <button type="submit" name="submit">Register</button>
    </form>

    <p class="auth-footer">Already have an account? <a href="login.php">Login</a></p>
    <p class="auth-footer">Are you an admin? <a href="adminLogin.php">Admin Login</a></p>
</div>

</body>
</html>