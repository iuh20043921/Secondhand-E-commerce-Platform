<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['MaNguoiDung'])) {
    http_response_code(401);
    exit(json_encode(['error' => 'Unauthorized']));
}

require_once 'functions.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "secondhandn";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    http_response_code(500);
    exit(json_encode(['error' => 'Database connection failed']));
}

$current_user_id = $_SESSION['MaNguoiDung'];

// Lấy thông tin cập nhật cho tất cả cuộc trò chuyện
$update_query = "SELECT 
    t.MaNguoiGui,
    t.MaSP,
    n.LastOnline,
    COUNT(CASE WHEN t.DaDoc = 0 THEN 1 END) as unreadCount
FROM tinnhan t
JOIN nguoidung n ON t.MaNguoiGui = n.MaNguoiDung
WHERE t.MaNguoiNhan = ?
GROUP BY t.MaNguoiGui, t.MaSP";

$stmt = $conn->prepare($update_query);
$stmt->bind_param("i", $current_user_id);
$stmt->execute();
$result = $stmt->get_result();

$conversations = [];
while ($row = $result->fetch_assoc()) {
    $conversations[] = [
        'MaNguoiGui' => $row['MaNguoiGui'],
        'MaSP' => $row['MaSP'],
        'isOnline' => checkUserOnlineStatus($row['MaNguoiGui'], $conn),
        'unreadCount' => $row['unreadCount']
    ];
}

echo json_encode([
    'success' => true,
    'conversations' => $conversations
]);

$conn->close();
?>