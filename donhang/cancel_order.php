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

if (!isset($_SESSION['MaNguoiDung']) || !isset($_POST['orderid'])) {
    exit('Unauthorized access');
}

$orderId = $_POST['orderid'];
$userId = $_SESSION['MaNguoiDung'];


$stmt = $conn->prepare("SELECT MaDonHang FROM donhang WHERE MaDonHang = ? AND MaNguoiMua = ? AND TrangThai = 'Chờ xác nhận'");
$stmt->bind_param("ii", $orderId, $userId);
$stmt->execute();
if ($stmt->get_result()->num_rows === 0) {
    exit('Invalid order or status');
}


$updateStmt = $conn->prepare("UPDATE donhang SET TrangThai = 'Đã Hủy đơn' WHERE MaDonHang = ?");
$updateStmt->bind_param("i", $orderId);
$updateStmt->execute();

echo 'success';
?>