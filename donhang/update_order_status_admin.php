<?php

session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "secondhandn";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['MaNguoiDung'])) {
    echo "unauthorized";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderId = $_POST['orderid'];
    $newStatus = $_POST['status'];

 
    $checkSql = "SELECT MaNguoiBan FROM donhang WHERE MaDonHang = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("i", $orderId);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows === 1) {
      
        $updateSql = "UPDATE donhang SET TrangThai = ? WHERE MaDonHang = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("si", $newStatus, $orderId);
        
        if ($updateStmt->execute()) {
            echo "success";
        } else {
            echo "error";
        }
    } else {
        echo "unauthorized";
    }
    
    $conn->close();
}
?>