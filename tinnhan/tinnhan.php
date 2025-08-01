<?php
session_start();
require_once '../tinnhan/funtions.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['MaNguoiDung'])) {
    header('Location: ../login/login.php');
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "secondhandn";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$current_user_id = $_SESSION['MaNguoiDung'];

// Lấy danh sách cuộc trò chuyện
$conversations_query = "SELECT DISTINCT 
    t.MaSP,
    t.MaNguoiGui,
    t.MaNguoiNhan,
    t.NgayNhanTin as NgayNhanTinMoiNhat,
    s.TenSP,
    s.HinhSP as AnhSanPham,
    s.DonGia,
    n.TenNguoiDung
FROM tinnhan t
JOIN sanpham s ON t.MaSP = s.MaSP
JOIN nguoidung n ON (
    CASE 
        WHEN t.MaNguoiGui = ? THEN t.MaNguoiNhan
        ELSE t.MaNguoiGui
    END = n.MaNguoiDung
)
WHERE t.MaNguoiGui = ? OR t.MaNguoiNhan = ?
GROUP BY t.MaSP, t.MaNguoiGui, t.MaNguoiNhan
ORDER BY t.NgayNhanTin DESC";

// Và sửa lại số lượng tham số bind
$stmt = $conn->prepare($conversations_query);
if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("iii", 
    $current_user_id, 
    $current_user_id, 
    $current_user_id
);
$stmt->execute();
$conversations_result = $stmt->get_result();

// Lấy thông tin cuộc trò chuyện hiện tại nếu có
$seller_id = isset($_GET['seller_id']) ? intval($_GET['seller_id']) : 0;
$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;

if ($seller_id && $product_id) {
    // Lấy thông tin người bán
    $seller_query = "SELECT TenNguoiDung, LastOnline FROM nguoidung WHERE MaNguoiDung = ?";
    $stmt = $conn->prepare($seller_query);
    $stmt->bind_param("i", $seller_id);
    $stmt->execute();
    $seller_result = $stmt->get_result();
    $seller = $seller_result->fetch_assoc();

    // Lấy thông tin sản phẩm
    $product_query = "SELECT TenSP FROM sanpham WHERE MaSP = ?";
    $stmt = $conn->prepare($product_query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $product_result = $stmt->get_result();
    $product = $product_result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tin nhắn</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .chat-container {
            height: calc(100vh - 2rem);
            margin: 1rem 0;
        }
        .conversation-list {
            height: 100%;
            overflow-y: auto;
            border-right: 1px solid #dee2e6;
        }
        .chat-area {
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .conversation-item {
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }
        .conversation-item:hover,
        .conversation-item.active {
            background-color: #f8f9fa;
            border-left-color: #0d6efd;
        }
        .unread {
            background-color: #e7f3ff;
        }
        .product-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 8px;
        }
        .message-container {
            flex-grow: 1;
            overflow-y: auto;
            padding: 1rem;
            background-color: #f8f9fa;
        }
        .message {
            margin-bottom: 1rem;
            max-width: 70%;
        }
        .message-content {
            padding: 0.75rem;
            border-radius: 1rem;
            position: relative;
        }
        .sent {
            margin-left: auto;
        }
        .sent .message-content {
            background-color: #0d6efd;
            color: white;
        }
        .received .message-content {
            background-color: #e9ecef;
        }
        .typing-indicator {
            padding: 1rem;
            color: #6c757d;
            font-style: italic;
            display: none;
        }
        .chat-input {
            padding: 1rem;
            background-color: #fff;
            border-top: 1px solid #dee2e6;
        }
        .online-indicator {
            width: 8px;
            height: 8px;
            background-color: #28a745;
            border-radius: 50%;
            display: inline-block;
            margin-right: 5px;
        }
        .offline-indicator {
            width: 8px;
            height: 8px;
            background-color: #6c757d;
            border-radius: 50%;
            display: inline-block;
            margin-right: 5px;
        }
        .time-ago {
            font-size: 0.75rem;
            color: #6c757d;
        }
        @media (max-width: 768px) {
            .conversation-list {
                display: none;
            }
            .conversation-list.show {
                display: block;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: 1000;
                background: white;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row chat-container">
            <!-- Danh sách cuộc trò chuyện -->
            <div class="col-md-4 col-lg-3 conversation-list" id="conversationList">
                <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                    <h5 class="mb-0">Tin nhắn</h5>
                    <a href="../index.php" class="btn btn-outline-primary btn-sm">
                        Trang chủ
                    </a>
                </div>
                
                <?php while ($conv = $conversations_result->fetch_assoc()): 
                    $isOnline = checkUserOnlineStatus($conv['MaNguoiGui'], $conn);
                    $timeAgo = formatTimeAgo($conv['NgayNhanTinMoiNhat']);
                    $otherUserId = ($conv['MaNguoiGui'] == $current_user_id) 
                        ? $conv['MaNguoiNhan'] 
                        : $conv['MaNguoiGui'];
                    $isActive = ($otherUserId == $seller_id && $conv['MaSP'] == $product_id);
                ?>
                    <a href="?seller_id=<?php echo $otherUserId; ?>&product_id=<?php echo $conv['MaSP']; ?>" 
                       class="conversation-item d-block p-3 text-decoration-none text-dark border-bottom <?php 
                            echo $isActive ? ' active' : '';
                       ?>">
                        <div class="d-flex align-items-center gap-2">
                           
     
                            <div class="flex-grow-1 min-width-0">
                                <div class="d-flex align-items-center mb-1">
                                    <span class="<?php echo $isOnline ? 'online-indicator' : 'offline-indicator'; ?>"></span>
                                    <h6 class="mb-0 text-truncate"><?php echo htmlspecialchars($conv['TenNguoiDung']); ?></h6>
                                </div>
                                <div class="text-muted small text-truncate">
                                    <?php echo htmlspecialchars($conv['TenSP']); ?>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-success small">
                                        <?php echo number_format($conv['DonGia'], 0, ',', '.'); ?>đ
                                    </span>
                                    
                                </div>
                            </div>
                        </div>
                    </a>
                <?php endwhile; ?>
            </div>

            <!-- Khu vực chat -->
            <div class="col-md-8 col-lg-9 chat-area">
                <?php if ($seller_id && $product_id): ?>
                    <div class="chat-header p-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-0">
                                    <?php echo htmlspecialchars($seller['TenNguoiDung']); ?>
                                    <span id="online-status" class="<?php 
                                        echo (strtotime('now') - strtotime($seller['LastOnline']) < 300) 
                                            ? 'online-indicator' 
                                            : 'offline-indicator'; 
                                    ?>"></span>
                                </h5>
                                <small class="text-muted">
                                    <?php echo htmlspecialchars($product['TenSP']); ?>
                                </small>
                            </div>
                            <button class="btn btn-outline-primary btn-sm d-md-none" onclick="toggleConversationList()">
                                Danh sách
                            </button>
                        </div>
                    </div>

                    <div id="message-container" class="message-container">
                        <!-- Tin nhắn sẽ được load động ở đây -->
                    </div>

                    <div id="typing-indicator" class="typing-indicator">
                        Đang nhập tin nhắn...
                    </div>

                    <div class="chat-input">
                        <form id="message-form" class="d-flex gap-2">
                            <textarea 
                                id="message-input"
                                class="form-control" 
                                rows="1" 
                                placeholder="Nhập tin nhắn..."
                                required
                            ></textarea>
                            <button type="submit" class="btn btn-primary">Gửi</button>
                        </form>
                    </div>
                <?php else: ?>
                    <div class="d-flex align-items-center justify-content-center h-100">
                        <div class="text-center text-muted">
                            <h4>Chọn một cuộc trò chuyện để bắt đầu</h4>
                            <p>Hoặc tìm kiếm sản phẩm để nhắn tin với người bán</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php if ($seller_id && $product_id): ?>
    <script>
        const currentUserId = <?php echo $current_user_id; ?>;
        const sellerId = <?php echo $seller_id; ?>;
        const productId = <?php echo $product_id; ?>;
        let lastMessageId = 0;
        let typingTimeout;

        // Hàm load tin nhắn
        function loadMessages() {
            fetch(`get_messages.php?seller_id=${sellerId}&product_id=${productId}&last_id=${lastMessageId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.messages && data.messages.length > 0) {
                        const container = document.getElementById('message-container');
                        data.messages.forEach(message => {
                            if (message.MaTinNhan > lastMessageId) {
                                const messageDiv = createMessageElement(message);
                                container.appendChild(messageDiv);
                                lastMessageId = message.MaTinNhan;
                            }
                        });
                        if (data.messages[data.messages.length - 1].MaNguoiGui !== currentUserId) {
                            scrollToBottom();
                        }
                    }
                    updateOnlineStatus(data.isSellerOnline);
                });
        }

        // Hàm tạo phần tử tin nhắn
        function createMessageElement(message) {
            const div = document.createElement('div');
            div.className = `message ${message.MaNguoiGui === currentUserId ? 'sent' : 'received'}`;
            
            const content = document.createElement('div');
            content.className = 'message-content';
            content.textContent = message.NoiDung;
            
            const time = document.createElement('small');
            time.className = 'time-ago';
            time.textContent = formatTimeAgo(new Date(message.NgayNhanTin));
            content.appendChild(time);
            
            div.appendChild(content);
            return div;
        }

        // Hàm cuộn xuống cuối cùng
        function scrollToBottom() {
            const container = document.getElementById('message-container');
            container.scrollTop = container.scrollHeight;
        }

        // Xử lý gửi tin nhắn
        document.getElementById('message-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const input = document.getElementById('message-input');
            const message = input.value.trim();
            
            if (message) {
                const formData = new FormData();
                formData.append('seller_id', sellerId);
                formData.append('product_id', productId);
                formData.append('message', message);

                fetch('send_message.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        input.value = '';
                        loadMessages();
                        scrollToBottom();
                    } else {
                        alert('Lỗi khi gửi tin nhắn: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra khi gửi tin nhắn');
                });
            }
        });

        // Hàm cập nhật trạng thái online
        function updateOnlineStatus(isOnline) {
            const indicator = document.getElementById('online-status');
            indicator.className = isOnline ? 'online-indicator' : 'offline-indicator';
        }

        // Xử lý đang nhập tin nhắn
        const messageInput = document.getElementById('message-input');
        messageInput.addEventListener('input', function() {
            clearTimeout(typingTimeout);
            
            const formData = new FormData();
            formData.append('seller_id', sellerId);
            formData.append('product_id', productId);
            formData.append('is_typing', '1');
            
            fetch('update_typing.php', {
                method: 'POST',
                body: formData
            });
            
            typingTimeout = setTimeout(() => {
                formData.set('is_typing', '0');
                fetch('update_typing.php', {
                    method: 'POST',
                    body: formData
                });
            }, 2000);
        });

        // Hàm format thời gian
        function formatTimeAgo(date) {
            const seconds = Math.floor((new Date() - date) / 1000);
            
            let interval = Math.floor(seconds / 31536000);
            if (interval > 1) return interval + ' năm trước';
            
            interval = Math.floor(seconds / 2592000);
            if (interval > 1) return interval + ' tháng trước';
            
            interval = Math.floor(seconds / 86400);
            if (interval > 1) return interval + ' ngày trước';
            
            interval = Math.floor(seconds / 3600);
            if (interval > 1) return interval + ' giờ trước';
            
            interval = Math.floor(seconds / 60);
            if (interval > 1) return interval + ' phút trước';
            
            return 'Vừa xong';
        }

        // Hàm toggle danh sách cuộc trò chuyện trên mobile
        function toggleConversationList() {
            const list = document.getElementById('conversationList');
            list.classList.toggle('show');
        }

        // Load tin nhắn ban đầu và thiết lập interval để cập nhật
        loadMessages();
        scrollToBottom();
        setInterval(loadMessages, 3000);

        // Đóng danh sách khi click ra ngoài trên mobile
        document.addEventListener('click', function(e) {
            const list = document.getElementById('conversationList');
            const target = e.target;
            
            if (list.classList.contains('show') && 
                !list.contains(target) && 
                !target.closest('button')) {
                list.classList.remove('show');
            }
        });
    </script>
    <?php endif; ?>
</body>
</html>