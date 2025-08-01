<?php
session_start();
require_once '../tinnhan/funtions.php';

header('Content-Type: application/json');

if (!isset($_SESSION['MaNguoiDung'])) {
    echo json_encode(['success' => false, 'message' => 'Bạn chưa đăng nhập']);
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "secondhandn";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Lỗi kết nối database']);
    exit();
}

$current_user_id = $_SESSION['MaNguoiDung'];
$seller_id = isset($_POST['seller_id']) ? intval($_POST['seller_id']) : 0;
$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

if (!$seller_id || !$product_id || empty($message)) {
    echo json_encode(['success' => false, 'message' => 'Thiếu thông tin cần thiết']);
    exit();
}

$insert_query = "INSERT INTO tinnhan (MaNguoiGui, MaNguoiNhan, MaSP, NoiDung, NgayNhanTin) VALUES (?, ?, ?, ?, NOW())";
$stmt = $conn->prepare($insert_query);

if ($stmt === false) {
    echo json_encode(['success' => false, 'message' => 'Lỗi chuẩn bị câu lệnh: ' . $conn->error]);
    exit();
}

$stmt->bind_param("iiis", $current_user_id, $seller_id, $product_id, $message);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Tin nhắn đã được gửi']);
} else {
    echo json_encode(['success' => false, 'message' => 'Lỗi gửi tin nhắn: ' . $stmt->error]);
}

$stmt->close();
$conn->close();