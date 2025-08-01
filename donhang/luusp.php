<?php
session_start();
$servername = "localhost";
$username = "root"; // thay thế bằng tên đăng nhập cơ sở dữ liệu của bạn
$password = ""; // thay thế bằng mật khẩu cơ sở dữ liệu của bạn
$dbname = "secondhandn";

$mysqli = new mysqli($servername, $username, $password, $dbname);


if ($mysqli->connect_error) {
    die("Kết nối thất bại: " . $mysqli->connect_error);
}


if (!isset($_SESSION['MaNguoiDung'])) {
    header('Location: /bandocu1/login/login.php');
    exit();
}


if (isset($_POST['toggle_favorite'])) {
    $maSP = $_POST['MaSP'];
    $maNguoiDung = $_SESSION['MaNguoiDung'];
    

    $check_query = "SELECT MaLuu FROM luusp WHERE MaNguoiDung = '$maNguoiDung' AND MaSP = '$maSP'";
    $check_result = mysqli_query($mysqli, $check_query);
    
    if (mysqli_num_rows($check_result) > 0) {

        $delete_query = "DELETE FROM luusp WHERE MaNguoiDung = '$maNguoiDung' AND MaSP = '$maSP'";
        mysqli_query($mysqli, $delete_query);
    } else {

        $insert_query = "INSERT INTO luusp (MaNguoiDung, MaSP) VALUES ('$maNguoiDung', '$maSP')";
        mysqli_query($mysqli, $insert_query);
    }
    
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}


$maNguoiDung = $_SESSION['MaNguoiDung'];
$query = "SELECT sp.*, nd.TenNguoiDung as NguoiBan, ls.NgayLuu 
          FROM luusp ls
          JOIN sanpham sp ON ls.MaSP = sp.MaSP
          JOIN nguoidung nd ON sp.MaNguoiDung = nd.MaNguoiDung
          WHERE ls.MaNguoiDung = '$maNguoiDung' AND sp.isDeleted = 0 And sp.isConfirm = 1
          ORDER BY ls.NgayLuu DESC";
$result = mysqli_query($mysqli, $query);
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
    <style>

        /* Style cơ bản */
        html, body {
            height: 100%;
        }
        body {
            display: flex;
            flex-direction: column;
        }
        .content {
            flex-grow: 1;
        }
        footer {
    background-color: #f88009;
    padding: 20px 0;
    color: black;
    margin-top: 20px;
}
  
.footer-container {
    display: flex;
    justify-content: space-around;
    max-width: 1200px;
    margin: 0 auto;
}
  
.footer-section {
    flex: 1;
    padding: 0 20px;
}
  
.footer-section h4 {
    margin-bottom: 10px;
}
  
.footer-section ul {
    list-style: none;
    padding: 0;
}
  
.footer-section ul li {
    margin-bottom: 5px;
}
  
.footer-section a {
    color: white;
    text-decoration: none;
}
  
.footer-section a:hover {
    text-decoration: underline;
    color: #ddd;
}
        /* Style cho nội dung chính */
        .content {
            margin-top: 20px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Style cho bảng đơn hàng */
        .table thead th {
            background-color: #343a40;
            color: white;
        }

        .table tbody tr:nth-child(odd) {
            background-color: #f2f2f2;
        }

        .table tbody tr:hover {
            background-color: #ddd;
        }

        .table td, .table th {
            padding: 12px;
            text-align: center;
        }

        /* Style cho tiêu đề và các nút */
        .content h2 {
            color: #343a40;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        /* Style cho nút hành động */
        .action-buttons .btn {
            margin: 0 5px;
        }
    </style>
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
        <h2>Sản phẩm đã lưu</h2>
        <div class="row">
            <?php if (isset($result) && mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <div class="col-md-12 mb-4">
                        <div class="card">
                            <div class="row no-gutters">
                                <div class="col-md-4">
                                    <img src="/bandocu1/images/<?php echo htmlspecialchars($row['HinhSP']); ?>" 
                                         class="card-img" 
                                         alt="<?php echo htmlspecialchars($row['TenSP']); ?>"
                                         style="object-fit: cover; height: 200px;"
                                         loading="lazy">
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo htmlspecialchars($row['TenSP']); ?></h5>
                                        <p class="card-text">Mã sản phẩm: <?php echo htmlspecialchars($row['MaSP']); ?></p>
                                        <p class="card-text">Giá: <?php echo number_format($row['DonGia'], 0, ',', '.'); ?> VND</p>
                                        <p class="card-text">Người bán: <?php echo htmlspecialchars($row['NguoiBan']); ?></p>
                                        <p class="card-text">Tình trạng: <?php echo htmlspecialchars($row['TinhTrang']); ?></p>
                                        <div class="d-flex">
                                            <a href="/bandocu1/tinnhan/tinnhan.php?user=<?php echo urlencode($row['MaNguoiDung']); ?>" 
                                               class="btn btn-primary me-2">Chat</a>
                                            <form method="POST" style="display: inline;">
                                                <input type="hidden" name="MaSP" value="<?php echo $row['MaSP']; ?>">
                                                <button type="submit" name="toggle_favorite" class="btn btn-danger">
                                                    Bỏ lưu ❤️
                                                </button>
                                            </form>
                                            <a href="/bandocu1/view/sanpham.php?action=chitietsp&mact=<?php echo $row['MaSP']; ?>" 
                                               class="btn btn-info ms-2">Xem chi tiết</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info">
                        Bạn chưa lưu sản phẩm nào.
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

 
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