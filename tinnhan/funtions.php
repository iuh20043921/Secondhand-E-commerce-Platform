<?php
// Kiểm tra trạng thái online của người dùng
function checkUserOnlineStatus($userId, $conn) {
    $query = "SELECT LastOnline FROM nguoidung WHERE MaNguoiDung = ?";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        
        if ($user) {
            $lastOnline = strtotime($user['LastOnline']);
            $currentTime = time();
            // Xem như online nếu hoạt động trong 5 phút gần đây
            return ($currentTime - $lastOnline) < 300;
        }
    }
    return false;
}

// Cập nhật thời gian hoạt động của người dùng
function updateUserOnlineStatus($userId, $conn) {
    $query = "UPDATE nguoidung SET LastOnline = NOW() WHERE MaNguoiDung = ?";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->close();
    }
}

// Đếm số tin nhắn chưa đọc
function getUnreadMessageCount($userId, $conn) {
    $query = "SELECT COUNT(*) as count 
              FROM tinnhan 
              WHERE MaNguoiNhan = ? 
              AND DaDoc = 0";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row['count'];
    }
    return 0;
}

// Đánh dấu tin nhắn đã đọc
function markMessagesAsRead($senderId, $receiverId, $productId, $conn) {
    $query = "UPDATE tinnhan 
              SET DaDoc = 1 
              WHERE MaNguoiGui = ? 
              AND MaNguoiNhan = ? 
              AND MaSP = ? 
              AND DaDoc = 0";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("iii", $senderId, $receiverId, $productId);
        $stmt->execute();
        $stmt->close();
    }
}

// Lấy thông tin người dùng
function getUserInfo($userId, $conn) {
    $query = "SELECT TenNguoiDung, Avatar, Email, LastOnline 
              FROM nguoidung 
              WHERE MaNguoiDung = ?";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        return $user;
    }
    return null;
}

// Lấy thông tin sản phẩm
function getProductInfo($productId, $conn) {
    $query = "SELECT MaSP, TenSP, HinhAnh, Gia, TrangThai 
              FROM sanpham 
              WHERE MaSP = ?";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();
        $stmt->close();
        return $product;
    }
    return null;
}

// Định dạng thời gian thân thiện
function formatTimeAgo($timestamp) {
    $current_time = time();
    $time_difference = $current_time - strtotime($timestamp);

    if ($time_difference < 60) {
        return 'Vừa xong';
    } elseif ($time_difference < 3600) {
        $minutes = floor($time_difference / 60);
        return $minutes . ' phút trước';
    } elseif ($time_difference < 86400) {
        $hours = floor($time_difference / 3600);
        return $hours . ' giờ trước';
    } elseif ($time_difference < 604800) {
        $days = floor($time_difference / 86400);
        return $days . ' ngày trước';
    } else {
        return date('d/m/Y H:i', strtotime($timestamp));
    }
}

// Tạo tin nhắn mới
function createNewMessage($senderId, $receiverId, $productId, $message, $conn) {
    $query = "INSERT INTO tinnhan (MaNguoiGui, MaNguoiNhan, MaSP, NoiDung, NgayNhanTin, DaDoc) 
              VALUES (?, ?, ?, ?, NOW(), 0)";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("iiis", $senderId, $receiverId, $productId, $message);
        $success = $stmt->execute();
        $messageId = $stmt->insert_id;
        $stmt->close();
        return $success ? $messageId : false;
    }
    return false;
}

// Kiểm tra quyền truy cập tin nhắn
function checkMessageAccess($userId, $messageId, $conn) {
    $query = "SELECT COUNT(*) as count 
              FROM tinnhan 
              WHERE (MaNguoiGui = ? OR MaNguoiNhan = ?) 
              AND MaTinNhan = ?";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("iii", $userId, $userId, $messageId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row['count'] > 0;
    }
    return false;
}

// Xóa tin nhắn
function deleteMessage($messageId, $userId, $conn) {
    // Chỉ cho phép người gửi xóa tin nhắn
    $query = "DELETE FROM tinnhan 
              WHERE MaTinNhan = ? 
              AND MaNguoiGui = ?";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("ii", $messageId, $userId);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }
    return false;
}

// Kiểm tra xem người dùng có phải là người bán của sản phẩm không
function isProductSeller($userId, $productId, $conn) {
    $query = "SELECT COUNT(*) as count 
              FROM sanpham 
              WHERE MaSP = ? 
              AND MaNguoiDung = ?";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("ii", $productId, $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row['count'] > 0;
    }
    return false;
}

// Lấy danh sách tin nhắn của một cuộc trò chuyện
function getConversationMessages($senderId, $receiverId, $productId, $lastId, $conn) {
    $query = "SELECT t.*, n.TenNguoiDung 
              FROM tinnhan t 
              JOIN nguoidung n ON t.MaNguoiGui = n.MaNguoiDung 
              WHERE ((t.MaNguoiGui = ? AND t.MaNguoiNhan = ?) 
              OR (t.MaNguoiGui = ? AND t.MaNguoiNhan = ?)) 
              AND t.MaSP = ?
              AND t.MaTinNhan > ?
              ORDER BY t.NgayNhanTin ASC";

    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("iiiiii", $senderId, $receiverId, $receiverId, $senderId, $productId, $lastId);
        $stmt->execute();
        $result = $stmt->get_result();
        $messages = [];
        while ($row = $result->fetch_assoc()) {
            $messages[] = $row;
        }
        $stmt->close();
        return $messages;
    }
    return [];
}

// Lấy tin nhắn cuối cùng của một cuộc trò chuyện
function getLastMessage($senderId, $receiverId, $productId, $conn) {
    $query = "SELECT t.*, n.TenNguoiDung 
              FROM tinnhan t 
              JOIN nguoidung n ON t.MaNguoiGui = n.MaNguoiDung 
              WHERE ((t.MaNguoiGui = ? AND t.MaNguoiNhan = ?) 
              OR (t.MaNguoiGui = ? AND t.MaNguoiNhan = ?)) 
              AND t.MaSP = ?
              ORDER BY t.NgayNhanTin DESC 
              LIMIT 1";

    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("iiiii", $senderId, $receiverId, $receiverId, $senderId, $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        $message = $result->fetch_assoc();
        $stmt->close();
        return $message;
    }
    return null;
}

// Kiểm tra xem người dùng có đang nhập tin nhắn không
function updateTypingStatus($userId, $receiverId, $productId, $isTyping, $conn) {
    $query = "UPDATE nguoidung 
              SET IsTyping = ?, 
                  TypingWith = ?, 
                  TypingProduct = ?, 
                  LastTypingUpdate = NOW() 
              WHERE MaNguoiDung = ?";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $typingStatus = $isTyping ? 1 : 0;
        $stmt->bind_param("iiii", $typingStatus, $receiverId, $productId, $userId);
        $stmt->execute();
        $stmt->close();
    }
}

// Kiểm tra trạng thái đang nhập của người dùng khác
function checkTypingStatus($userId, $productId, $conn) {
    $query = "SELECT MaNguoiDung, LastTypingUpdate 
              FROM nguoidung 
              WHERE IsTyping = 1 
              AND TypingWith = ? 
              AND TypingProduct = ? 
              AND TIMESTAMPDIFF(SECOND, LastTypingUpdate, NOW()) < 10";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("ii", $userId, $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        $typing = $result->fetch_assoc();
        $stmt->close();
        return $typing ? true : false;
    }
    return false;
}

// Lấy thống kê tin nhắn
function getMessageStats($userId, $conn) {
    $stats = [
        'total_messages' => 0,
        'unread_messages' => 0,
        'total_conversations' => 0,
        'active_conversations' => 0 // Cuộc trò chuyện có tin nhắn trong 7 ngày qua
    ];

    // Tổng số tin nhắn
    $query = "SELECT COUNT(*) as count 
              FROM tinnhan 
              WHERE MaNguoiGui = ? OR MaNguoiNhan = ?";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("ii", $userId, $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stats['total_messages'] = $row['count'];
        $stmt->close();
    }

    // Số tin nhắn chưa đọc
    $stats['unread_messages'] = getUnreadMessageCount($userId, $conn);

    // Tổng số cuộc trò chuyện
    $query = "SELECT COUNT(DISTINCT CONCAT(MaSP, '_', 
              CASE WHEN MaNguoiGui = ? THEN MaNguoiNhan ELSE MaNguoiGui END)) as count 
              FROM tinnhan 
              WHERE MaNguoiGui = ? OR MaNguoiNhan = ?";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("iii", $userId, $userId, $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stats['total_conversations'] = $row['count'];
        $stmt->close();
    }

    // Số cuộc trò chuyện có hoạt động trong 7 ngày qua
    $query = "SELECT COUNT(DISTINCT CONCAT(MaSP, '_', 
              CASE WHEN MaNguoiGui = ? THEN MaNguoiNhan ELSE MaNguoiGui END)) as count 
              FROM tinnhan 
              WHERE (MaNguoiGui = ? OR MaNguoiNhan = ?) 
              AND NgayNhanTin >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("iii", $userId, $userId, $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stats['active_conversations'] = $row['count'];
        $stmt->close();
    }

    return $stats;
}


function searchMessages($userId, $keyword, $conn) {
    $query = "SELECT t.*, n.TenNguoiDung, s.TenSP 
              FROM tinnhan t 
              JOIN nguoidung n ON (
                  CASE 
                      WHEN t.MaNguoiGui = ? THEN t.MaNguoiNhan
                      ELSE t.MaNguoiGui
                  END = n.MaNguoiDung
              )
              JOIN sanpham s ON t.MaSP = s.MaSP
              WHERE (t.MaNguoiGui = ? OR t.MaNguoiNhan = ?) 
              AND (t.NoiDung LIKE ? OR n.TenNguoiDung LIKE ? OR s.TenSP LIKE ?)
              ORDER BY t.NgayNhanTin DESC";

    $keyword = "%$keyword%";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("iiisss", $userId, $userId, $userId, $keyword, $keyword, $keyword);
        $stmt->execute();
        $result = $stmt->get_result();
        $messages = [];
        while ($row = $result->fetch_assoc()) {
            $messages[] = $row;
        }
        $stmt->close();
        return $messages;
    }
    return [];
}

// Lấy thông tin cuộc trò chuyện
function getConversationInfo($senderId, $receiverId, $productId, $conn) {
    $query = "SELECT 
                s.TenSP,
                s.Gia,
                s.HinhAnh as AnhSanPham,
                n.TenNguoiDung,
                n.Avatar,
                n.LastOnline,
                COUNT(CASE WHEN t.DaDoc = 0 AND t.MaNguoiNhan = ? THEN 1 END) as SoTinNhanChuaDoc,
                MAX(t.NgayNhanTin) as ThoiGianTinNhanCuoi
              FROM sanpham s
              JOIN nguoidung n ON n.MaNguoiDung = ?
              LEFT JOIN tinnhan t ON t.MaSP = s.MaSP 
                AND (t.MaNguoiGui IN (?, ?) AND t.MaNguoiNhan IN (?, ?))
              WHERE s.MaSP = ?
              GROUP BY s.MaSP";
              
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("iiiiiii", $senderId, $receiverId, $senderId, $receiverId, $senderId, $receiverId, $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        $info = $result->fetch_assoc();
        $stmt->close();
        return $info;
    }
    return null;
}

// Kiểm tra xem người dùng có bị chặn không
function isUserBlocked($userId, $blockedById, $conn) {
    $query = "SELECT COUNT(*) as count 
              FROM nguoidung_block 
              WHERE MaNguoiDungBiChan = ? 
              AND MaNguoiDungChan = ?";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("ii", $userId, $blockedById);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row['count'] > 0;
    }
    return false;
}

// Chặn người dùng
function blockUser($userId, $blockedUserId, $conn) {
    // Kiểm tra xem đã chặn chưa
    if (!isUserBlocked($blockedUserId, $userId, $conn)) {
        $query = "INSERT INTO nguoidung_block (MaNguoiDungChan, MaNguoiDungBiChan, NgayChan) 
                  VALUES (?, ?, NOW())";
        $stmt = $conn->prepare($query);
        if ($stmt) {
            $stmt->bind_param("ii", $userId, $blockedUserId);
            $success = $stmt->execute();
            $stmt->close();
            return $success;
        }
    }
    return false;
}

// Bỏ chặn người dùng
function unblockUser($userId, $blockedUserId, $conn) {
    $query = "DELETE FROM nguoidung_block 
              WHERE MaNguoiDungChan = ? 
              AND MaNguoiDungBiChan = ?";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("ii", $userId, $blockedUserId);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }
    return false;
}

// Báo cáo tin nhắn
function reportMessage($messageId, $userId, $reason, $conn) {
    $query = "INSERT INTO tinnhan_baocao (MaTinNhan, MaNguoiDungBaoCao, LyDo, NgayBaoCao) 
              VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("iis", $messageId, $userId, $reason);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }
    return false;
}

// Xóa tất cả tin nhắn trong một cuộc trò chuyện
function deleteConversation($userId, $otherUserId, $productId, $conn) {
    $query = "DELETE FROM tinnhan 
              WHERE MaSP = ? 
              AND ((MaNguoiGui = ? AND MaNguoiNhan = ?) 
              OR (MaNguoiGui = ? AND MaNguoiNhan = ?))";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("iiiii", $productId, $userId, $otherUserId, $otherUserId, $userId);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }
    return false;
}

// Kiểm tra quyền truy cập cuộc trò chuyện
function checkConversationAccess($userId, $otherUserId, $productId, $conn) {
    // Kiểm tra xem người dùng có phải là người tham gia cuộc trò chuyện
    $query = "SELECT COUNT(*) as count 
              FROM tinnhan 
              WHERE MaSP = ? 
              AND ((MaNguoiGui = ? AND MaNguoiNhan = ?) 
              OR (MaNguoiGui = ? AND MaNguoiNhan = ?))";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("iiiii", $productId, $userId, $otherUserId, $otherUserId, $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        
        // Nếu có tin nhắn tồn tại
        if ($row['count'] > 0) {
            return true;
        }
        
        // Nếu chưa có tin nhắn, kiểm tra xem người dùng có phải là người bán
        return isProductSeller($otherUserId, $productId, $conn);
    }
    return false;
}

// Tạo thông báo cho tin nhắn mới
function createMessageNotification($senderId, $receiverId, $productId, $messageId, $conn) {
    $query = "INSERT INTO thongbao (MaNguoiDung, LoaiThongBao, NoiDung, TrangThai, NgayTao, MaThamChieu) 
              VALUES (?, 'message', ?, 0, NOW(), ?)";
    
    // Lấy thông tin người gửi và sản phẩm
    $sender = getUserInfo($senderId, $conn);
    $product = getProductInfo($productId, $conn);
    
    if ($sender && $product) {
        $content = $sender['TenNguoiDung'] . " đã gửi tin nhắn về sản phẩm " . $product['TenSP'];
        
        $stmt = $conn->prepare($query);
        if ($stmt) {
            $stmt->bind_param("isi", $receiverId, $content, $messageId);
            $success = $stmt->execute();
            $stmt->close();
            return $success;
        }
    }
    return false;
}

// Lấy danh sách người đã nhắn tin với user
function getMessageContacts($userId, $conn) {
    $query = "SELECT DISTINCT 
                n.MaNguoiDung,
                n.TenNguoiDung,
                n.Avatar,
                n.LastOnline,
                COUNT(DISTINCT t.MaSP) as SoSanPham,
                MAX(t.NgayNhanTin) as TinNhanCuoiCung
              FROM tinnhan t
              JOIN nguoidung n ON (
                  CASE 
                      WHEN t.MaNguoiGui = ? THEN t.MaNguoiNhan
                      ELSE t.MaNguoiGui
                  END = n.MaNguoiDung
              )
              WHERE t.MaNguoiGui = ? OR t.MaNguoiNhan = ?
              GROUP BY n.MaNguoiDung
              ORDER BY TinNhanCuoiCung DESC";
              
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("iii", $userId, $userId, $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $contacts = [];
        while ($row = $result->fetch_assoc()) {
            $contacts[] = $row;
        }
        $stmt->close();
        return $contacts;
    }
    return [];
}

// Lấy thống kê tương tác tin nhắn theo thời gian
function getMessageTimeStats($userId, $conn) {
    $query = "SELECT 
                HOUR(NgayNhanTin) as Gio,
                COUNT(*) as SoTinNhan
              FROM tinnhan
              WHERE MaNguoiGui = ? OR MaNguoiNhan = ?
              GROUP BY HOUR(NgayNhanTin)
              ORDER BY Gio";
              
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("ii", $userId, $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $stats = [];
        while ($row = $result->fetch_assoc()) {
            $stats[$row['Gio']] = $row['SoTinNhan'];
        }
        $stmt->close();
        return $stats;
    }
    return [];
}
?>