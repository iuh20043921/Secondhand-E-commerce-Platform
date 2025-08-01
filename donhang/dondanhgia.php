<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "secondhandn";

// Kết nối cơ sở dữ liệu
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Kiểm tra đăng nhập
if (!isset($_SESSION['MaNguoiDung'])) {
    header("Location: login.php");
    exit();
}

$userID = $_SESSION['MaNguoiDung'];

// Truy vấn lấy đánh giá của người dùng đăng nhập (chỉ lấy những đánh giá chưa bị xóa)
$sql = "SELECT 
    dg.*, 
    nd.TenNguoiDung as TenNguoiMua, 
    sp.TenSP, 
    nd_ban.TenNguoiDung as TenNguoiBan
FROM danhgiasanpham dg
JOIN sanpham sp ON dg.MaSP = sp.MaSP
JOIN nguoidung nd ON dg.MaNguoiDung = nd.MaNguoiDung -- Người mua
JOIN nguoidung nd_ban ON sp.MaNguoiDung = nd_ban.MaNguoiDung -- Người bán
WHERE dg.MaNguoiDung = ? AND dg.delete = 0;"; // Sử dụng Deleted = 0 để chỉ lấy các đánh giá chưa bị xóa

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Lỗi chuẩn bị truy vấn: " . $conn->error);
}

// Gán tham số và thực thi truy vấn
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();
$orders = $result->fetch_all(MYSQLI_ASSOC);

// Xử lý xóa đánh giá (ẩn đánh giá thay vì xóa)
if (isset($_GET['id'])) {
    $review_id = $_GET['id'];

    // Cập nhật cột Deleted = 1 để "ẩn" đánh giá khỏi giao diện
    $update_sql = "UPDATE danhgiasanpham SET `delete` = 1 WHERE MaDanhGia = ?";  // Dùng backticks để bao quanh "delete"
    $update_stmt = $conn->prepare($update_sql);
    if ($update_stmt) {
        $update_stmt->bind_param("i", $review_id);  // Chỉ cần tham số review_id thôi
        $update_stmt->execute();
    } else {
        die("Lỗi cập nhật đánh giá: " . $conn->error);
    }
}


// Trả về giao diện
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>C2C Marketplace - Đơn Đơn đánh giá</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="styleCSS.css?v=1">
    
</head>

<body class="d-flex flex-column min-vh-100">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="/bandocu1/index.php">NewJean</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="me-auto">

                </div>
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="/bandocu1/index.php">Trang chủ</a></li>
                    <li class="nav-item"><a class="nav-link" href="/bandocu1/view/sanpham.php">Sản phẩm</a></li>
                    <li class="nav-item"><a class="nav-link" href="/bandocu1/tinnhan/tinnhan.php">Tin nhắn</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="accountDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">Tài khoản</a>
                        <ul class="dropdown-menu" aria-labelledby="accountDropdown">
                            <?php
                            if (isset($_SESSION['TenDangNhap'])) {
                                if ($_SESSION['role'] === '2') {
                                    echo '
                            <li><a class="dropdown-item" href="/bandocu1/donhang/qldonhang.php">Admin: Quản lý Đơn hàng</a></li>
                            <li><a class="dropdown-item" href="/bandocu1/dangtin/modules/quanlytin.php">Admin: Quản lý Bài Đăng</a></li>
                            <hr/>';

                            echo ' <li><a class="dropdown-item" href="/bandocu1/qltaikhoan.php">Quản lý Tài Khoản</a></li>
                                                                <li><a class="dropdown-item" href="/bandocu1/donhang/luusp.php">Danh mục yêu thích</a></li>
                            <li><a class="dropdown-item" href="/bandocu1/donhang/donmua.php">Đơn hàng đã mua</a></li>
                             <li><a class="dropdown-item" href="/bandocu1/donhang/dondanhgia.php">Đơn hàng đã đánh giá</a></li>
                                                          <li><a class="dropdown-item" href="/bandocu1/donhang/donban.php">Đơn hàng đang bán</a></li>
                           
     
                                    <li><a class="dropdown-item" href="/bandocu1/dangxuat.php">Đăng xuất</a></li>';
                                } ?>

                                <li>
                                    <?php
                                    if ($_SESSION['role'] === '1') {
                                        echo ' <li><a class="dropdown-item" href="/bandocu1/dangtin/modules/quanlytin.php">Quản lý Bài Đăng</a></li><li><a class="dropdown-item" href="/bandocu1/qltaikhoan.php">Quản lý Tài Khoản</a></li>
                                                                <li><a class="dropdown-item" href="/bandocu1/donhang/luusp.php">Danh mục yêu thích</a></li>
                            <li><a class="dropdown-item" href="/bandocu1/donhang/donmua.php">Đơn hàng đã mua</a></li>
                             <li><a class="dropdown-item" href="/bandocu1/donhang/dondanhgia.php">Đơn hàng đã đánh giá</a></li>
                                                          <li><a class="dropdown-item" href="/bandocu1/donhang/donban.php">Đơn hàng đang bán</a></li>
                           
     
                                    <li><a class="dropdown-item" href="/bandocu1/dangxuat.php">Đăng xuất</a></li>';
                                    }
                            } else {
                                echo '<li><a class="dropdown-item" href="/bandocu1//login/login.php">Đăng nhập</a></li>';
                            }
                            ?>
                            </li>
                        </ul>
                    </li>
                    <div class="ms-2">
                        <a href="/bandocu1/view/giohang.php" class="btn btn-outline-light me-2">Giỏ hàng</a>
                        <a href="/bandocu1/dangtin/index.php" class="btn btn-warning">Đăng tin</a>
                    </div>
                </ul>
            </div>
        </div>
</nav>

<div class="container content mt-5"> 
    <h2>ĐƠN HÀNG ĐÃ ĐÁNH GIÁ</h2>
    <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <a class="nav-link active" href="#" data-status="all">Tất cả <span class="badge bg-primary" id="count-all">0</span></a>
        </li>
    </ul>

    <div id="order-table">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Tên Người Bán</th>
                    <th>Tên sản phẩm</th>
                    <th>Nội dung đánh giá</th>
                    <th>Điểm đánh giá</th>
                    <th>Ngày đánh giá</th>
                    <th>Hình đánh giá</th>
                    <th>Tùy chọn</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['TenNguoiBan']); ?></td>
                        <td><?php echo htmlspecialchars($order['TenSP']); ?></td>
                        <td><?php echo htmlspecialchars($order['NoiDungDanhGia']); ?></td>
                        <td><?php echo htmlspecialchars($order['DiemDanhGia']); ?></td>
                        <td><?php echo date('Y-m-d H:i:s', strtotime($order['NgayDanhGia'])); ?></td>
                        <td>
                            <?php if (!empty($order['HinhDG']) && file_exists($order['HinhDG'])): ?>
                                <img src="<?php echo htmlspecialchars($order['HinhDG']); ?>" alt="Hình đánh giá" style="max-width: 100px; height: auto;">
                            <?php else: ?>
                                <span>Không có hình</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="edit_review.php?id=<?php echo $order['MaDanhGia']; ?>" class="btn btn-warning btn-sm">Chỉnh sửa</a>
                            <a href="?id=<?php echo $order['MaDanhGia']; ?>" class="btn btn-danger btn-sm" 
                               onclick="return confirm('Bạn có chắc chắn muốn xóa đánh giá này?');">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>



    <div class="modal fade" id="orderDetailModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Chi tiết đơn hàng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="orderDetailContent">

                </div>
            </div>
        </div>
    </div>



    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
    $('.nav-link').on('click', function (e) {
        var status = $(this).data('status'); // Lấy dữ liệu `status` từ thuộc tính `data-status` của liên kết

        if (status) {
            e.preventDefault(); // Ngăn mặc định chuyển hướng của liên kết

            // Xử lý logic của tab, ví dụ: cập nhật giao diện hoặc lọc dữ liệu
            // Loại bỏ class 'active' khỏi tất cả các liên kết
            $('.nav-link').removeClass('active');
            // Thêm class 'active' cho liên kết hiện tại
            $(this).addClass('active');

            // Ẩn hoặc hiện các hàng trong bảng dựa trên giá trị `status`
            $('#order-table tbody tr').each(function () {
                var rowStatus = $(this).data('status'); // Lấy trạng thái của hàng từ thuộc tính `data-status`

                if (status === 'all' || rowStatus === status) {
                    $(this).show(); // Hiển thị hàng phù hợp
                } else {
                    $(this).hide(); // Ẩn hàng không phù hợp
                }
            });

            // Cập nhật bộ đếm các trạng thái, nếu cần
            updateOrderCounts();
        }
    });

    // Hàm cập nhật bộ đếm các trạng thái
    function updateOrderCounts() {
        let counts = {
            all: 0,
            pending: 0,
            waiting: 0,
            delivered: 0,
            canceled: 0
        };

        // Đếm số lượng hàng tương ứng với mỗi trạng thái
        $('#order-table tbody tr').each(function () {
            const status = $(this).data('status');
            counts.all++;

            if (counts.hasOwnProperty(status)) {
                counts[status]++;
            }
        });

        // Cập nhật giao diện số lượng theo từng trạng thái
        for (let status in counts) {
            $(`#count-${status}`).text(counts[status]);
        }
    }

    // Gọi hàm cập nhật bộ đếm khi trang được tải
    updateOrderCounts();
});
    </script>
     <footer class="bg-dark text-white text-center py-4">
    <div class="footer-container">
    <div class="footer-section">
        <h4>Thông tin liên hệ</h4>
        <p>Địa chỉ: 12 Nguyễn Văn Bảo, phường 4, Quận Gò Vấp, TP.HCM</p>
        <p>Điện thoại: 0123 456 789</p>
    </div>
    <div class="footer-section">
        <h4>Về chúng tôi</h4>
        <ul>
            <li><a href="#">Trang chủ</a></li>
            <li><a href="#">Giới thiệu</a></li>
            <li><a href="#">Liên hệ</a></li>
        </ul>
    </div>
    <div class="footer-section">
        <h4>Mạng xã hội</h4>
        <ul>
            <li><a href="#"><i class="fa-brands fa-facebook"></i>&nbsp;Facebook</a></li>
            <li><a href="#"><i class="fa-brands fa-instagram"></i>&nbsp;Instagram</a></li>
            <li><a href="#"><i class="fa-brands fa-viber"></i>&nbsp;Viper</a></li>
        </ul>
    </div>
    </div>

</footer>
    <!-- Thêm JS Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.min.js"></script>
<script src="js/index.js"></script>
</body>

</html>