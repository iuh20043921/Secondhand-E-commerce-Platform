<?php
session_start();
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
}

$servername = "localhost";
$username = "root"; // thay thế bằng tên đăng nhập cơ sở dữ liệu của bạn
$password = ""; // thay thế bằng mật khẩu cơ sở dữ liệu của bạn
$dbname = "secondhandn";

$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy ngày hiện tại
$current_date = date('Y-m-d');

// Truy vấn sản phẩm có giá rẻ nhất trong ngày
$sql = "SELECT * FROM sanpham WHERE isConfirm = 1 and isDeleted = 0 AND DATE(NgayThemSP) = '$current_date' ORDER BY DonGia ASC LIMIT 5";
$result = $conn->query($sql);

$products = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

// Truy vấn sản phẩm nổi bật (giá cao nhất)
$sql_featured = "SELECT * FROM sanpham WHERE isConfirm = 1 and isDeleted = 0 AND DonGia > 100000 ORDER BY DonGia ASC LIMIT 3";
$result_featured = $conn->query($sql_featured);

// Truy vấn sản phẩm mới đăng (theo ngày)
$sql_new = "SELECT * FROM sanpham where isConfirm = 1 and isDeleted = 0 ORDER BY NgayThemSP DESC LIMIT 4";
$result_new = $conn->query($sql_new);

// Truy vấn danh mục sản phẩm
$sql_categories = "SELECT * FROM loaisanpham";
$result_categories = $conn->query($sql_categories);
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bán Đồ Cũ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
    <link rel="stylesheet" href="style.css">
</head>
<style>
    a {
            text-decoration: none; /* Remove underline */
            color: black; /* Set text color to black */
        }

        /* Optional: Change color on hover */
        a:hover {
            color: orange; /* Optional: Change color on hover (blue) */
        }
</style>

<body>
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
    <!-- Banner -->
    <header class="banner bg-dark text-white text-center">
        <div class="container py-5">
            <h2 class="display-4">Mua bán đồ cũ dễ dàng</h2>
            <p class="lead">Nền tảng mua bán giữa người dùng với người dùng</p>
            <a href="./view/sanpham.php" class="btn btn-warning btn-lg">Khám phá ngay</a>
        </div>
    </header>
    <div class="container mt-5">
        <h2 class="text-center">Danh Mục Sản Phẩm</h2>
        <div class="row text-center mt-4">
            <div class="col-md-4">
                <div class="card">
                    <img src="images/3.jpg" class="card-img-top" alt="Quần áo">
                    <div class="card-body">
                        <h5 class="card-title"> <a href="/bandocu1/view/sanpham.php?ml=2">Quần áo </a></h5>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <img src="images/Giay.jpg" class="card-img-top" alt="Giày">
                    <div class="card-body">
                        <h5 class="card-title"> <a href="/bandocu1/view/sanpham.php?ml=1">Giày</a></h5>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <img src="images/non.jpg" class="card-img-top" alt="Nón">
                    <div class="card-body">
                        <h5 class="card-title"> <a href="/bandocu1/view/sanpham.php?ml=5">Nón </a></h5>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <img src="images/mypham.jpg" class="card-img-top" alt="Mỹ phẩm">
                    <div class="card-body">
                        <h5 class="card-title"> <a href="/bandocu1/view/sanpham.php?ml=6">Mỹ phẩm</a></h5>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <img src="images/tuisach.jpg" class="card-img-top" alt="Túi sách">
                    <div class="card-body">
                        <h5 class="card-title"> <a href="/bandocu1/view/sanpham.php?ml=4">Túi sách</a></h5>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <img src="images/sach.jpg" class="card-img-top" alt="Sách">
                    <div class="card-body">
                        <h5 class="card-title"> <a href="/bandocu1/view/sanpham.php?ml=7">Sách</a></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- <section class="slider-product-one">
            <div class="container">
                <div class="slider-produc-one-content">
                    <div class="slider-produc-one-content-title">
                        <h2>Sản Phẩm Giá Rẻ Hôm Nay</h2>
                    </div>
                    <div class="slider-produc-one-content-container">
                        <div class="slider-produc-one-content-items-total">
                            <div class="slider-produc-one-content-items">
                                <?php foreach ($products as $product): ?>
                                <div class="col-md-3 mb-4 slider-produc-one-content-item">
                                    <div class="card">
                                        <img src="<?php echo htmlspecialchars($product['HinhSP']); ?>" class="card-img-top" alt="">
                                        <div class="card-body">
                                            <h5 class="card-title"><?php echo htmlspecialchars($product['TenSP']); ?></h5>
                                            <p class="card-text">Giá: <?php echo number_format($product['DonGia']); ?> VND</p>
                                            <a href="#" class="btn btn-warning">Xem chi tiết</a>
                                        </div>
                                    </div>
                                </div>                              
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>                  
                </div>
            </div>
        </section> -->

    <!-- Danh sách sản phẩm nổi bật -->
    <section id="featured-products" class="product-list py-5">
        <div class="container">
            <h2 class="text-center mb-4">Sản phẩm nổi bật</h2>
            <div class="row">
                <?php
                if ($result_featured->num_rows > 0) {
                    while ($row = $result_featured->fetch_assoc()) {
                        echo '<div class="col-md-4 mb-4">
                            <div class="card">
                                <img src= "images/' . $row["HinhSP"] . '" class="card-img-top">
                                <div class="card-body">
                                    <h5 class="card-title">' . $row["TenSP"] . '</h5>
                                    <p class="card-text">Giá: ' . number_format($row["DonGia"], 0, ',', '.') . ' VND</p>
                                     <a href="/bandocu1/view/sanpham.php?action=chitietsp&mact=' . $row['MaSP'] . '" class="btn btn-warning">Xem chi tiết</a>
                                </div>
                            </div>
                        </div>';
                    }
                }
                ?>
            </div>
        </div>
    </section>

    <!-- Danh sách sản phẩm mới đăng -->
    <section id="new-products" class="product-list py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-4">Sản phẩm mới đăng</h2>
            <div class="row">
                <?php
                if ($result_new->num_rows > 0) {
                    while ($row = $result_new->fetch_assoc()) {
                        echo '<div class="col-md-3 mb-4">
                            <div class="card">
                                <img src="images/' . $row["HinhSP"] . '" class="card-img-top">
                                <div class="card-body">
                                    <h5 class="card-title">' . $row["TenSP"] . '</h5>
                                    <p class="card-text">Giá: ' . number_format($row["DonGia"], 0, ',', '.') . ' VND</p>
                                    <a href="/bandocu1/view/sanpham.php?action=chitietsp&mact=' . $row['MaSP'] . '" class="btn btn-warning">Xem chi tiết</a>
                                </div>
                            </div>
                        </div>';
                    }
                }
                ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
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