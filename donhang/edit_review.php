<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "secondhandn";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Kiểm tra nếu có tham số id được truyền qua URL
if (!isset($_GET['id'])) {
    echo "Lỗi: Không tìm thấy ID đánh giá.";
    exit();
}

$reviewID = intval($_GET['id']); // Đảm bảo ID là số nguyên

// Xử lý khi người dùng gửi biểu mẫu chỉnh sửa
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $noiDung = trim($_POST['NoiDungDanhGia']);
    $diemDanhGia = intval($_POST['DiemDanhGia']);
    $hinhDanhGia = $_FILES['HinhDG']['name'];

    // Kiểm tra số từ trong nội dung đánh giá (giới hạn 200 từ)
    $wordCount = str_word_count($noiDung);
    if ($wordCount > 200) {
        echo "<script>alert('Nội dung đánh giá không được vượt quá 200 từ.');</script>";
    } elseif (empty($noiDung)) {
        echo "<script>alert('Nội dung đánh giá không được để trống.');</script>";
    } elseif ($diemDanhGia < 1 || $diemDanhGia > 5) {
        echo "<script>alert('Điểm đánh giá phải nằm trong khoảng từ 1 đến 5.');</script>";
    } else {
        // Xử lý tải file nếu có
        if (!empty($hinhDanhGia)) {
            $uploadDir = 'uploads/reviews/';
            $uploadFile = $uploadDir . basename($hinhDanhGia);

            if (move_uploaded_file($_FILES['HinhDG']['tmp_name'], $uploadFile)) {
                $hinhDanhGia = $uploadFile;
            } else {
                echo "<script>alert('Lỗi tải ảnh.');</script>";
                $hinhDanhGia = null;
            }
        } else {
            $hinhDanhGia = $_POST['ExistingHinhDG']; // Nếu không thay đổi ảnh, giữ nguyên ảnh cũ
        }

        // Cập nhật đánh giá
        $sql = "UPDATE danhgiasanpham 
                SET NoiDungDanhGia = ?, DiemDanhGia = ?, HinhDG = ?
                WHERE MaDanhGia = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sisi", $noiDung, $diemDanhGia, $hinhDanhGia, $reviewID);

        if ($stmt->execute()) {
            header("Location: dondanhgia.php");
            exit();
        } else {
            echo "<script>alert('Lỗi: Không thể cập nhật đánh giá. " . $stmt->error . "');</script>";
        }
    }
}

// Lấy dữ liệu hiện tại của đánh giá
$sql = "SELECT * FROM danhgiasanpham WHERE MaDanhGia = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $reviewID);
$stmt->execute();
$result = $stmt->get_result();
$review = $result->fetch_assoc();

if (!$review) {
    echo "Lỗi: Không tìm thấy đánh giá.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>C2C Marketplace - Chỉnh sửa đánh giá</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../styles.css">
    <link rel="stylesheet" href="stylesCSS.css">
</head>
<style>
    /* General Styles */
body {
    font-family: 'Arial', sans-serif;
    background-color: #f4f4f9;
    margin: 0;
    padding: 0;
}

.container {
    width: 50%;
    max-width: 800px;
    margin: 50px auto;
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

h2 {
    text-align: center;
    color: #333;
    margin-bottom: 20px;
}

/* Form Styling */
form {
    display: flex;
    flex-direction: column;
}

label {
    font-weight: bold;
    color: #333;
    margin-bottom: 8px;
}

textarea,
input[type="text"],
input[type="number"],
input[type="file"] {
    padding: 12px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 14px;
    width: 100%;
    box-sizing: border-box;
}

textarea {
    resize: vertical;
}

input[type="file"] {
    padding: 0;
}

/* Button Styles */
button {
    padding: 12px 20px;
    font-size: 16px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

button:hover {
    background-color: #45a049;
}

a {
    display: inline-block;
    text-align: center;
    padding: 12px;
    margin-top: 10px;
    text-decoration: none;
    color: #007BFF;
    font-size: 16px;
    border-radius: 5px;
    transition: color 0.3s;
}

a:hover {
    color: #0056b3;
}

/* Image Preview Styling */
img {
    max-width: 100px;
    margin-bottom: 10px;
}

/* Responsive Design */
@media screen and (max-width: 768px) {
    .container {
        width: 90%;
    }
}

</style>

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
    <body>
    <div class="container">
        <h2>Chỉnh sửa đánh giá</h2>
        <form method="post" enctype="multipart/form-data">
            <label for="NoiDungDanhGia">Nội dung đánh giá:</label>
            <textarea id="NoiDungDanhGia" name="NoiDungDanhGia" rows="5" required><?php echo htmlspecialchars($review['NoiDungDanhGia']); ?></textarea>

            <label for="DiemDanhGia">Điểm đánh giá:</label>
            <input type="number" id="DiemDanhGia" name="DiemDanhGia" min="1" max="5" value="<?php echo htmlspecialchars($review['DiemDanhGia']); ?>" required>

            <label for="HinhDG">Hình đánh giá:</label>
            <?php if (!empty($review['HinhDG'])): ?>
                <img src="<?php echo htmlspecialchars($review['HinhDG']); ?>" alt="Hình hiện tại">
                <input type="hidden" name="ExistingHinhDG" value="<?php echo htmlspecialchars($review['HinhDG']); ?>">
            <?php endif; ?>
            <input type="file" id="HinhDG" name="HinhDG">

            <button type="submit">Cập nhật</button>
            <a href="dondanhgia.php">Quay lại</a>
        </form>
    </div>
</body>
</html>
