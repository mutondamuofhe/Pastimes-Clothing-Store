<?php
session_start();
include 'DBConn.php';

if(!isset($_SESSION['admin'])){
    header("Location: adminLogin.php");
    exit();
}

$id = intval($_GET['id']);

$sql = "DELETE FROM tblUser WHERE UserID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$stmt->close();

header("Location: adminDashboard.php");
exit();
?>