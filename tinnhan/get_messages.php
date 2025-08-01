<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['MaNguoiDung'])) {
    http_response_code(401);
    exit(json_encode(['error' => 'Unauthorized']));
}

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
$seller_id = isset($_GET['seller_id']) ? intval($_GET['seller_id']) : 0;
$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;
$last_id = isset($_GET['last_id']) ? intval($_GET['last_id']) : 0;

if ($seller_id === 0 || $product_id === 0) {
    http_response_code(400);
    exit(json_encode(['error' => 'Invalid parameters']));
}

// Đánh dấu tin nhắn đã đọc
$mark_read_query = "UPDATE tinnhan 
                    SET DaDoc = 1 
                    WHERE MaNguoiNhan = ? 
                    AND MaNguoiGui = ? 
                    AND MaSP = ? 
                    AND DaDoc = 0";
$stmt = $conn->prepare($mark_read_query);
$stmt->bind_param("iii", $current_user_id, $seller_id, $product_id);
$stmt->execute();
$stmt->close();

// Lấy tin nhắn mới
$messages_query = "SELECT t.*, n.TenNguoiDung 
                  FROM tinnhan t 
                  JOIN nguoidung n ON t.MaNguoiGui = n.MaNguoiDung 
                  WHERE ((t.MaNguoiGui = ? AND t.MaNguoiNhan = ?) 
                  OR (t.MaNguoiGui = ? AND t.MaNguoiNhan = ?)) 
                  AND t.MaSP = ?
                  AND t.MaTinNhan > ?
                  ORDER BY t.NgayNhanTin ASC";

$stmt = $conn->prepare($messages_query);
$stmt->bind_param("iiiiii", $current_user_id, $seller_id, $seller_id, $current_user_id, $product_id, $last_id);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = [
        'MaTinNhan' => $row['MaTinNhan'],
        'MaNguoiGui' => $row['MaNguoiGui'],
        'NoiDung' => $row['NoiDung'],
        'NgayNhanTin' => $row['NgayNhanTin'],
        'DaDoc' => $row['DaDoc'],
        'TenNguoiDung' => $row['TenNguoiDung']
    ];
}
$stmt->close();

// Kiểm tra trạng thái online của người bán
$online_query = "SELECT LastOnline FROM nguoidung WHERE MaNguoiDung = ?";
$stmt = $conn->prepare($online_query);
$stmt->bind_param("i", $seller_id);
$stmt->execute();
$online_result = $stmt->get_result();
$seller_status = $online_result->fetch_assoc();
$stmt->close();

$is_seller_online = false;
if ($seller_status) {
    $last_online = strtotime($seller_status['LastOnline']);
    $is_seller_online = (time() - $last_online) < 300; // 5 phút
}

// Trả về kết quả
echo json_encode([
    'success' => true,
    'messages' => $messages,
    'isSellerOnline' => $is_seller_online,
    'lastId' => $last_id
]);

$conn->close();
?>