<?php
session_start(); // Khởi tạo session
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>C2C Marketplace - Bán Đồ Cũ</title>
    <!-- Thêm CSS Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../styles.css">
    <link rel="stylesheet" href="styleCSS.css">
</head>
<body class="d-flex flex-column min-vh-100">
    <!-- Thanh điều hướng -->
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
   <!-- Content -->
   <div class="container content mt-5">
        <h2>SẢN PHẨM ĐANG BÁN</h2>
        <!-- Thanh trạng thái -->
        <ul class="nav nav-tabs mb-4">
            <li class="nav-item">
                <a class="nav-link active" href="#" data-status="all">Tất cả <span class="badge" id="count-all">0</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" data-status="pending">Hết hàng <span class="badge" id="count-pending">0</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" data-status="processing">Còn hàng <span class="badge" id="count-processing">0</span></a>
            </li>

        </ul>
        
        <div id="order-table">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Mã sản phẩm</th>
                        <th>Sản phẩm</th>
                        <th>Ngày thêm sản phẩm</th>
                        <th>Số lượng</th>
                        <th>Số tiền</th>
        
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Dữ liệu đơn hàng -->
                    <tr data-status="pending">
                        <td>DH001</td>
                        <td>Quần áo</td>
                        <td>12/10/2024</td>
                        <td>0</td>
                        <td>500,000 VND</td>
           
                        <td class="action-buttons">
                            <button class="btn btn-sm btn-primary">Sửa</button>
                            <button class="btn btn-sm btn-danger">Xóa</button>
                        </td>
                    </tr>
                    <tr data-status="processing">
                        <td>DH002</td>
                        <td>Giày</td>
                        <td>13/10/2024</td>
                        <td>12ý</td>
                        <td>800,000 VND</td>
                   
                        <td class="action-buttons">
                            <button class="btn btn-sm btn-primary">Sửa</button>
                            <button class="btn btn-sm btn-danger">Xóa</button>
                        </td>
                    </tr>
             
                </tbody>
            </table>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-4 mt-auto">
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
                    <li><a href="#"><i class="fa-brands fa-viber"></i>&nbsp;Viber</a></li>
                </ul>
            </div>
        </div>
    </footer>

    <!-- Thêm JS Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Hàm cập nhật số lượng đơn hàng theo trạng thái
            function updateOrderCounts() {
                var totalCount = $('#order-table tbody tr').length;
                var pendingCount = $('#order-table tbody tr[data-status="pending"]').length;
                var processingCount = $('#order-table tbody tr[data-status="processing"]').length;
                var shippingCount = $('#order-table tbody tr[data-status="shipping"]').length;
                var deliveredCount = $('#order-table tbody tr[data-status="delivered"]').length;
                var canceledCount = $('#order-table tbody tr[data-status="canceled"]').length;

                $('#count-all').text(totalCount);
                $('#count-pending').text(pendingCount);
                $('#count-processing').text(processingCount);
                $('#count-shipping').text(shippingCount);
                $('#count-delivered').text(deliveredCount);
                $('#count-canceled').text(canceledCount);
            }

            // Cập nhật số lượng khi tải trang
            updateOrderCounts();

            // Hàm hiển thị đơn hàng theo trạng thái
            $('.nav-link').on('click', function() {
                var status = $(this).data('status');
                $('.nav-link').removeClass('active');
                $(this).addClass('active');

                if (status === 'all') {
                    $('#order-table tbody tr').show();
                } else {
                    $('#order-table tbody tr').hide();
                    $('#order-table tbody tr[data-status="' + status + '"]').show();
                }
            });
        });
    </script>
</body>
</html>
