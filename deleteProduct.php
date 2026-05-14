<?php
session_start();
include 'DBConn.php';

if(!isset($_SESSION['admin'])){
    header('Location: adminLogin.php');
    exit();
}

$id = intval($_GET['id']);
if($id > 0){
    $stmt = $conn->prepare("DELETE FROM tblClothes WHERE ClothesID = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
}

header('Location: adminDashboard.php');
exit();
