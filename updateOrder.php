<?php
session_start();
include 'DBConn.php';

if(!isset($_SESSION['admin'])){
    header('Location: adminLogin.php');
    exit();
}

$id = intval($_GET['id']);
$status = isset($_GET['status']) ? $_GET['status'] : '';
$allowed = ['Pending', 'Shipped', 'Delivered'];

if($id > 0 && in_array($status, $allowed, true)){
    $stmt = $conn->prepare("UPDATE tblOrder SET Status = ? WHERE OrderID = ?");
    $stmt->bind_param('si', $status, $id);
    $stmt->execute();
    $stmt->close();
}

header('Location: adminDashboard.php');
exit();
