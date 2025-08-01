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


if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}



if (!isset($_SESSION['MaNguoiDung'])) {
    header("Location: /bandocu1/login/login.php");
    exit();
}

$user_id = $_SESSION['MaNguoiDung'];

$query = "SELECT * FROM nguoidung WHERE MaNguoiDung = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ten_nguoi_dung = $_POST['ten_nguoi_dung'];
 
    $sdt = $_POST['sdt'];
    $dia_chi = $_POST['dia_chi'];
    $ngay_sinh = $_POST['ngay_sinh'];
    
    
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    $errors = [];
    

    if (empty($ten_nguoi_dung)) {
        $errors[] = "Tên người dùng không được trống";
    }
    

    
    if (!empty($new_password)) {
        if ($new_password !== $confirm_password) {
            $errors[] = "Mật khẩu xác nhận không khớp";
        }
        
        if (strlen($new_password) < 6) {
            $errors[] = "Mật khẩu phải có ít nhất 6 ký tự";
        }
    }
    
    if (empty($errors)) {
      
        if (!empty($new_password)) {
            $query = "UPDATE nguoidung SET TenNguoiDung = ?, SDT = ?, DiaChi = ?, NgaySinh = ?, MatKhau = ? WHERE MaNguoiDung = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssss", $ten_nguoi_dung, $sdt, $dia_chi, $ngay_sinh, $new_password, $user_id);
        } else {
            $query = "UPDATE nguoidung SET TenNguoiDung = ?, SDT = ?, DiaChi = ?, NgaySinh = ? WHERE MaNguoiDung = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssssi", $ten_nguoi_dung, $sdt, $dia_chi, $ngay_sinh, $user_id);
        }
        
        
        if ($stmt->execute()) {
            $success_message = "Cập nhật thông tin thành công";
           
            $query = "SELECT * FROM nguoidung WHERE MaNguoiDung = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
        } else {
            $error_message = "Có lỗi xảy ra: " . $stmt->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Tài Khoản</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f4f4;
        }
        .card {
            border-color: rgb(33,37,41);
        }
        .btn-primary {
            background-color: rgb(33,37,41);
            border-color: rgb(33,37,41);
        }
        .btn-primary:hover {
            background-color: rgba(33,37,41,0.8);
        }
    </style>
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
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header text-center" style="background-color: rgb(33,37,41); color: white;">
                        <h3>Quản Lý Tài Khoản</h3>
                    </div>
                    <div class="card-body">
                        <?php 
                        if (isset($success_message)) {
                            echo "<div class='alert alert-success'>$success_message</div>";
                        }
                        if (!empty($errors)) {
                            foreach ($errors as $error) {
                                echo "<div class='alert alert-danger'>$error</div>";
                            }
                        }
                        ?>
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label class="form-label">Tên Người Dùng</label>
                                <input type="text" class="form-control" name="ten_nguoi_dung" 
                                       value="<?php echo htmlspecialchars($user['TenNguoiDung']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" disabled 
                                       value="<?php echo htmlspecialchars($user['Email']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Số Điện Thoại</label>
                                <input type="tel" class="form-control" name="sdt" 
                                       value="<?php echo htmlspecialchars($user['SDT']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Địa Chỉ</label>
                                <input type="text" class="form-control" name="dia_chi" 
                                       value="<?php echo htmlspecialchars($user['DiaChi']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Ngày Sinh</label>
                                <input type="date" class="form-control" name="ngay_sinh" 
                                       value="<?php echo htmlspecialchars($user['NgaySinh']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Mật Khẩu Mới (Để trống nếu không muốn thay đổi)</label>
                                <input type="password" class="form-control" name="new_password">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Xác Nhận Mật Khẩu Mới</label>
                                <input type="password" class="form-control" name="confirm_password">
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Cập Nhật Thông Tin</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>