<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'DBConn.php';

// 🔥 VERY IMPORTANT: disable foreign key checks FIRST
$conn->query("SET FOREIGN_KEY_CHECKS = 0");

// Drop tables safely
$conn->query("DROP TABLE IF EXISTS tblClothes");
$conn->query("DROP TABLE IF EXISTS tblUser");
$conn->query("DROP TABLE IF EXISTS tblAdmin");

// 🔥 Turn it back on
$conn->query("SET FOREIGN_KEY_CHECKS = 1");
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
    echo "tblUser created successfully.<br>";
} else {
    echo "Error creating tblUser: " . $conn->error . "<br>";
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
    echo "tblAdmin created successfully.<br>";
} else {
    echo "Error creating tblAdmin: " . $conn->error . "<br>";
}

// Create tblClothes
$sql = "CREATE TABLE tblClothes (
    ClothesID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(100) NOT NULL,
    Brand VARCHAR(50) NOT NULL,
    Price DECIMAL(10,2) NOT NULL,
    ConditionType VARCHAR(20) NOT NULL,
    Username VARCHAR(50) NOT NULL,
    ImageURL VARCHAR(255) NOT NULL DEFAULT 'images/homephoto.png',
    CreatedAt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (Username) REFERENCES tblUser(Username)
) ENGINE=InnoDB";

if ($conn->query($sql) === TRUE) {
    echo "tblClothes created successfully.<br>";
} else {
    echo "Error creating tblClothes: " . $conn->error . "<br>";
}

// Insert default admin
$adminPassword = md5('admin123');
$conn->query("INSERT INTO tblAdmin (Name, Email, Username, Password) VALUES ('Admin User', 'admin@pastimes.local', 'admin', '$adminPassword')");

echo "Default admin user inserted (username: admin password: admin123).<br>";

// Load data from text file
$file = fopen("userData.txt", "r");
if ($file) {
    while (($line = fgetcsv($file)) !== FALSE) {
        if(count($line) < 4) {
            continue;
        }

        $name = $conn->real_escape_string($line[0]);
        $email = $conn->real_escape_string($line[1]);
        $username = $conn->real_escape_string($line[2]);
        $password = md5($line[3]);

        $conn->query("INSERT INTO tblUser (Name, Email, Username, Password, Role, IsVerified) VALUES ('$name', '$email', '$username', '$password', 'customer', 0)");
    }
    fclose($file);
    echo "Customer data loaded successfully.<br>";
} else {
    echo "Unable to open userData.txt.";
}

// Load clothes data
$file = fopen("tblClothes.txt", "r");
if ($file) {
    $count = 0;
    while (($line = fgetcsv($file)) !== FALSE) {
        if(count($line) < 5) {
            continue;
        }

        $name = $conn->real_escape_string($line[0]);
        $brand = $conn->real_escape_string($line[1]);
        $price = floatval($line[2]);
        $condition = $conn->real_escape_string($line[3]);
        $username = $conn->real_escape_string($line[4]);
        $imageURL = isset($line[5]) && trim($line[5]) !== '' ? $conn->real_escape_string($line[5]) : 'images/homephoto.png';

        $conn->query("INSERT INTO tblClothes (Name, Brand, Price, ConditionType, Username, ImageURL) VALUES ('$name', '$brand', $price, '$condition', '$username', '$imageURL')");
        $count++;
    }
    fclose($file);
    echo "$count clothes items loaded successfully.<br>";
} else {
    echo "Unable to open tblClothes.txt.";
}

$conn->close();
?>