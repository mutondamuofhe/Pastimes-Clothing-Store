<?php
include 'DBConn.php';

$id = $_GET['id'];

$result = $conn->query("SELECT * FROM tblUser WHERE UserID=$id");
$row = $result->fetch_assoc();

if(isset($_POST['update'])){
    $name = $_POST['name'];
    $email = $_POST['email'];

    $sql = "UPDATE tblUser 
            SET Name='$name', Email='$email'
            WHERE UserID=$id";

    if($conn->query($sql)){
        header("Location: adminDashboard.php");
    }
}
?>

<form method="POST">
Name: <input type="text" name="name" value="<?php echo $row['Name']; ?>"><br>
Email: <input type="email" name="email" value="<?php echo $row['Email']; ?>"><br>
<input type="submit" name="update" value="Update">
</form>