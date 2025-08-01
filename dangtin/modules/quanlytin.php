<?php
session_start();

if (!isset($_SESSION['role']) || ($_SESSION['role'] !== '1' && $_SESSION['role'] !== '2')) {
    header("Location: /bandocu1/index.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "secondhandn";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if ($_SESSION['role'] === '2') {

    $sql = "SELECT sp.*, nd.TenNguoiDung, nd.Email 
            FROM sanpham sp 
            LEFT JOIN nguoidung nd ON sp.MaNguoiDung = nd.MaNguoiDung
            WHERE sp.isDeleted = 0";

    $result = $conn->query($sql);
} else {

    $sql = "SELECT sp.*, nd.TenNguoiDung, nd.Email 
            FROM sanpham sp 
            LEFT JOIN nguoidung nd ON sp.MaNguoiDung = nd.MaNguoiDung 
            WHERE sp.MaNguoiDung = ? AND sp.isDeleted = 0";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $_SESSION['MaNguoiDung']);
    $stmt->execute();
    $result = $stmt->get_result();
}



if ($_SESSION['role'] === '2') {
    $result = $conn->query($sql);
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Quản lý Đăng Tin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js"></script>
    <link rel="stylesheet" href="../css/quanlytin.css">
</head>

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
                                                          <li><a class="dropdown-item" href="/bandocu1/donhang/donban.php">Đơn hàng đang bán</a></li>
                           
     
                                    <li><a class="dropdown-item" href="/bandocu1/dangxuat.php">Đăng xuất</a></li>';
                                } ?>

                                <li>
                                    <?php
                                    if ($_SESSION['role'] === '1') {
                                        echo ' <li><a class="dropdown-item" href="/bandocu1/dangtin/modules/quanlytin.php">Quản lý Bài Đăng</a></li><li><a class="dropdown-item" href="/bandocu1/qltaikhoan.php">Quản lý Tài Khoản</a></li>
                                                                <li><a class="dropdown-item" href="/bandocu1/donhang/luusp.php">Danh mục yêu thích</a></li>
                            <li><a class="dropdown-item" href="/bandocu1/donhang/donmua.php">Đơn hàng đã mua</a></li>
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
    <div class="main">
        <h1>Quản lý Đăng Tin</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên sản phẩm</th>
                    <th>Giá</th>
                    <th>Số lượng</th>
                    <th>Tình trạng</th>
                    <?php if ($_SESSION['role'] === '2'): ?>
                        <th>Tên</th>
                        <th>Email</th>
                        
                    <?php endif; ?>
                    <th>Duyệt</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['MaSP']; ?></td>
                        <td><?php echo $row['TenSP']; ?></td>
                        <td><?php echo number_format($row['DonGia'], 0, ',', '.') . ' VNĐ'; ?></td>
                        <td style="text-align: center;">
    <?php 
        if ($row['SoLuongTon'] <= 0) {
            echo '<span style="color: red;">Hết hàng</span>'; 
        } else {
            echo $row['SoLuongTon']; 
        }
    ?>
</td>

                        <td><?php echo $row['TinhTrang']; ?></td>
                        <?php if ($_SESSION['role'] === '2'): ?>
                            <td><?php echo $row['TenNguoiDung']; ?></td>
                            <td><?php echo $row['Email']; ?></td>
                            

                        <?php endif; ?>
                        <td>
                                <?php
                                if ($row['isConfirm'] == 0) {
                                    echo '<span style="color: orange;">Đang chờ</span>';
                                } elseif ($row['isConfirm'] == 1) {
                                    echo '<span style="color: green;">Xong</span>'; 
                                }
                                ?>
                            </td>
                        <td>
                            <a href="sua.php?id=<?php echo $row['MaSP']; ?>">Sửa</a> |
                            <a href="xoa.php?id=<?php echo $row['MaSP']; ?>"
                                onclick="return confirm('Bạn có chắc chắn muốn xóa?');">Xóa</a>
                            <?php
                            if ($row['isConfirm'] == 0 && $_SESSION['role'] === '2' ) {
                                echo '<a href="duyet.php?id=' . $row['MaSP'] . '">Duyệt</a>';
                            }
                            ?>



                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
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

</body>

</html>