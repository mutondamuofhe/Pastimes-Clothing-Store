<?php
include 'DBConn.php';

$message = "";
$setupComplete = false;

// Check if tables exist
$result = $conn->query("SHOW TABLES LIKE 'tblUser'");
$tablesExist = $result && $result->num_rows > 0;

if(isset($_POST['setup'])){
    // Drop existing tables
    $conn->query("DROP TABLE IF EXISTS tblUser");
    $conn->query("DROP TABLE IF EXISTS tblAdmin");

    // Create tblUser
    $sql = "CREATE TABLE tblUser (
        UserID INT AUTO_INCREMENT PRIMARY KEY,
        Name VARCHAR(100) NOT NULL,
        Email VARCHAR(100) NOT NULL UNIQUE,
        Username VARCHAR(50) NOT NULL UNIQUE,
        Password VARCHAR(255) NOT NULL,
        Role ENUM('customer','admin') NOT NULL DEFAULT 'customer',
        IsVerified TINYINT(1) NOT NULL DEFAULT 0,
        CreatedAt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB";

    if ($conn->query($sql) === TRUE) {
        $message .= "✓ tblUser created successfully.<br>";
    } else {
        $message .= "✗ Error creating tblUser: " . $conn->error . "<br>";
    }

    // Create tblAdmin
    $sql = "CREATE TABLE tblAdmin (
        AdminID INT AUTO_INCREMENT PRIMARY KEY,
        Name VARCHAR(100) NOT NULL,
        Email VARCHAR(100) NOT NULL UNIQUE,
        Username VARCHAR(50) NOT NULL UNIQUE,
        Password VARCHAR(255) NOT NULL,
        CreatedAt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB";

    if ($conn->query($sql) === TRUE) {
        $message .= "✓ tblAdmin created successfully.<br>";
    } else {
        $message .= "✗ Error creating tblAdmin: " . $conn->error . "<br>";
    }

    // Create tblClothes
    $sql = "CREATE TABLE tblClothes (
        ClothesID INT AUTO_INCREMENT PRIMARY KEY,
        Name VARCHAR(100) NOT NULL,
        Brand VARCHAR(50) NOT NULL,
        Price DECIMAL(10,2) NOT NULL,
        ConditionType VARCHAR(50) NOT NULL,
        Username VARCHAR(50) NOT NULL,
        ImageURL VARCHAR(255) NOT NULL DEFAULT 'images/homephoto.png',
        Description TEXT NULL,
        CreatedAt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB";

    if ($conn->query($sql) === TRUE) {
        $message .= "✓ tblClothes created successfully.<br>";
    } else {
        $message .= "✗ Error creating tblClothes: " . $conn->error . "<br>";
    }

    // Create tblOrder
    $sql = "CREATE TABLE tblOrder (
        OrderID INT AUTO_INCREMENT PRIMARY KEY,
        UserID VARCHAR(100) NOT NULL,
        OrderDate DATE NOT NULL,
        Status VARCHAR(50) NOT NULL
    ) ENGINE=InnoDB";

    if ($conn->query($sql) === TRUE) {
        $message .= "✓ tblOrder created successfully.<br>";
    } else {
        $message .= "✗ Error creating tblOrder: " . $conn->error . "<br>";
    }

    // Insert default admin
    $adminPassword = md5('admin123');
    if($conn->query("INSERT INTO tblAdmin (Name, Email, Username, Password) VALUES ('Admin User', 'admin@pastimes.local', 'admin', '$adminPassword')")){
        $message .= "✓ Default admin user created (Username: <strong>admin</strong> | Password: <strong>admin123</strong>).<br>";
    } else {
        $message .= "✗ Error creating admin user: " . $conn->error . "<br>";
    }

    // Load data from text file
    $file = fopen("userData.txt", "r");
    if ($file) {
        $count = 0;
        while (($line = fgetcsv($file)) !== FALSE) {
            if(count($line) < 4) {
                continue;
            }

            $name = $conn->real_escape_string($line[0]);
            $email = $conn->real_escape_string($line[1]);
            $username = $conn->real_escape_string($line[2]);
            $password = md5($line[3]);

            if($conn->query("INSERT INTO tblUser (Name, Email, Username, Password, Role, IsVerified) VALUES ('$name', '$email', '$username', '$password', 'customer', 0)")){
                $count++;
            }
        }
        fclose($file);
        $message .= "✓ Loaded $count customer records from userData.txt.<br>";
    } else {
        $message .= "⚠ Unable to open userData.txt, skipping customer data.<br>";
    }

    $setupComplete = true;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pastime - Setup</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .container { max-width: 600px; margin: 0 auto; }
        .alert { padding: 15px; margin: 20px 0; border-radius: 5px; }
        .success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .info { background-color: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }
        .warning { background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba; }
        button { padding: 10px 20px; font-size: 16px; cursor: pointer; background-color: #007bff; color: white; border: none; border-radius: 5px; }
        button:hover { background-color: #0056b3; }
        a { color: #007bff; }
    </style>
</head>
<body>

<div class="container">
    <h1>🎭 Pastime - Database Setup</h1>

    <?php if($setupComplete): ?>
        <div class="alert success">
            <h3>✓ Setup Complete!</h3>
            <p><?php echo $message; ?></p>
            <p>You can now:</p>
            <ul>
                <li><a href="adminLogin.php">Login as Admin</a> (admin / admin123)</li>
                <li><a href="register.php">Register a new user</a></li>
                <li><a href="login.php">Login as customer</a></li>
            </ul>
        </div>
    <?php elseif($tablesExist): ?>
        <div class="alert info">
            <h3>ℹ Database already initialized</h3>
            <p>Tables already exist in the database. You can proceed to:</p>
            <ul>
                <li><a href="adminLogin.php">Admin Login</a></li>
                <li><a href="register.php">Register</a></li>
                <li><a href="login.php">User Login</a></li>
            </ul>
            <p style="margin-top: 20px; border-top: 1px solid #0c5460; padding-top: 10px;">
                Or <form method="POST" style="display:inline;">
                    <button type="submit" name="setup">Reset Database</button>
                </form> to start fresh.
            </p>
        </div>
    <?php else: ?>
        <div class="alert warning">
            <h3>⚠ Database Not Initialized</h3>
            <p>The database tables have not been created yet. Click the button below to initialize:</p>
            <form method="POST">
                <button type="submit" name="setup">Initialize Database</button>
            </form>
        </div>
    <?php endif; ?>

    <hr>
    <p style="font-size: 12px; color: #666;">Database: <?php echo isset($dbname) ? htmlspecialchars($dbname) : 'Unknown'; ?></p>
</div>

</body>
</html>