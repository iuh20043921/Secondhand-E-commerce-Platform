<?php
$servername = "localhost";
$username = "root"; // thay thế bằng tên đăng nhập cơ sở dữ liệu của bạn
$password = ""; // thay thế bằng mật khẩu cơ sở dữ liệu của bạn
$dbname = "secondhandn";

$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // Lấy thông tin sản phẩm để sửa
    $sql = "SELECT * FROM sanpham WHERE MaSP = ? and isDeleted = 0";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        echo "Sản phẩm không tồn tại.";
        exit();
    }
}

$error_TenSP = $error_DonGia = $error_SoLuongTon = $error_HinhSP = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy các giá trị từ form
    $TenSP = $_POST['TenSP'];
    $DonGia = $_POST['DonGia'];
    $SoLuongTon = $_POST['SoLuongTon'];
    $TinhTrang = $_POST['TinhTrang'];

    // Kiểm tra tên sản phẩm không chứa ký tự đặc biệt
    if (!preg_match("/^[\p{L}0-9\s]*$/u", $TenSP)) {
        $error_TenSP = "Tên sản phẩm không được chứa ký tự đặc biệt.";
    }

    // Kiểm tra giá phải lớn hơn 1000
    if ($DonGia <= 1000) {
        $error_DonGia = "Giá phải lớn hơn 1.000(VNĐ).";
    }

    // Kiểm tra số lượng phải lớn hơn 0
    if ($SoLuongTon <= 0) {
        $error_SoLuongTon = "Số lượng phải lớn hơn 0.";
    }

    // Nếu không có lỗi, tiếp tục xử lý tệp hình ảnh
    if (empty($error_TenSP) && empty($error_DonGia) && empty($error_SoLuongTon)) {
        // Lấy hình ảnh cũ (giả sử $product là thông tin sản phẩm hiện tại)
        $HinhSP = $product['HinhSP'];

        // Kiểm tra nếu người dùng có chọn tệp hình ảnh mới
        if ($_FILES['HinhSP']['error'] == UPLOAD_ERR_OK) {
            // Xử lý hình ảnh mới
            $target_dir = "../images/";
            $target_file = $target_dir . basename($_FILES["HinhSP"]["name"]);
            $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $allowedTypes = ['jpg', 'jpeg', 'png'];
            $maxFileSize = 2 * 1024 * 1024; // 2MB

            // Kiểm tra loại tệp và kích thước
            if (in_array($fileType, $allowedTypes) && $_FILES["HinhSP"]["size"] <= $maxFileSize) {
                // Di chuyển tệp hình ảnh lên server
                move_uploaded_file($_FILES["HinhSP"]["tmp_name"], $target_file);
                // Cập nhật tên hình ảnh mới
                $HinhSP = basename($_FILES["HinhSP"]["name"]);
            } else {
                $error_HinhSP = "Chỉ chấp nhận hình ảnh JPG, JPEG, PNG và tối đa 2MB.";
            }
        }

        // Nếu không có lỗi, tiếp tục cập nhật sản phẩm
        if (empty($error_TenSP) && empty($error_DonGia) && empty($error_SoLuongTon) && empty($error_HinhSP)) {
            // Chuẩn bị truy vấn SQL để cập nhật sản phẩm
            $sql = "UPDATE sanpham SET TenSP = ?, DonGia = ?, SoLuongTon = ?, TinhTrang = ?, HinhSP = ? WHERE MaSP = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sdisis", $TenSP, $DonGia, $SoLuongTon, $TinhTrang, $HinhSP, $id);

            // Thực thi câu lệnh SQL
            if ($stmt->execute()) {
                header("Location: quanlytin.php");
                exit();
            } else {
                echo "Lỗi khi cập nhật sản phẩm: " . $stmt->error;
            }

            // Đóng kết nối
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa sản phẩm</title>
    <style>
        .main {
        max-width: 500px; /* Giới hạn chiều rộng tối đa */
        margin: 0 auto; /* Căn giữa */
        background-color: #fff; /* Màu nền trắng */
        border-radius: 8px; /* Bo góc */
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Bóng đổ */
        padding: 20px; /* Padding cho nội dung */
        }

        h1 {
            text-align: center; /* Căn giữa tiêu đề */
            color: #333; /* Màu chữ tối */
            margin-bottom: 20px; /* Khoảng cách dưới tiêu đề */
        }

        label {
            display: block; /* Hiển thị label dưới dạng block để dễ dàng căn chỉnh */
            margin-bottom: 5px; /* Khoảng cách dưới mỗi label */
            color: #555; /* Màu chữ cho label */
        }

        input[type="text"],
        input[type="number"] {
            width: 100%; /* Chiều rộng 100% */
            padding: 10px; /* Padding cho ô input */
            margin-bottom: 15px; /* Khoảng cách dưới mỗi ô input */
            border: 1px solid #ddd; /* Đường viền xung quanh ô input */
            border-radius: 4px; /* Bo góc cho ô input */
            box-sizing: border-box; /* Bao gồm padding và border trong chiều rộng */
        }

        input[type="submit"] {
            width: 100%; /* Chiều rộng 100% */
            padding: 10px; /* Padding cho nút submit */
            margin-top: 10px;
            background-color: #007bff; /* Màu nền cho nút */
            color: white; /* Màu chữ trắng */
            border: none; /* Không có đường viền */
            border-radius: 4px; /* Bo góc cho nút */
            cursor: pointer; /* Hiệu ứng con trỏ khi di chuột */
            transition: background-color 0.3s; /* Hiệu ứng chuyển màu khi di chuột */
        }

        input[type="submit"]:hover {
            background-color: #0056b3; /* Màu nền khi di chuột qua nút */
        }
    </style>
</head>
<body>
    <div class="main">
        <h1>Sửa sản phẩm</h1>
        <form method="POST" enctype="multipart/form-data">
            <label for="TenSP">Tên sản phẩm:</label>
            <input type="text" name="TenSP" value="<?php echo isset($TenSP) ? $TenSP : $product['TenSP']; ?>" required>
            <?php if (!empty($error_TenSP)) { echo "<div style='color: red; font-size: 17px' class='error'>$error_TenSP</div>"; } ?>
            <br>

            <label for="DonGia">Giá:</label>
            <input type="number" name="DonGia" value="<?php echo isset($DonGia) ? $DonGia : $product['DonGia']; ?>" required>
            <?php if (!empty($error_DonGia)) { echo "<div style='color: red; font-size: 17px' class='error'>$error_DonGia</div>"; } ?>
            <br>

            <label for="SoLuongTon">Số lượng tồn:</label>
            <input type="number" name="SoLuongTon" value="<?php echo isset($SoLuongTon) ? $SoLuongTon : $product['SoLuongTon']; ?>" required>
            <?php if (!empty($error_SoLuongTon)) { echo "<div style='color: red; font-size: 17px' class='error'>$error_SoLuongTon</div>"; } ?>
            <br>

            <label for="TinhTrang">Tình trạng:</label>
            <input type="text" name="TinhTrang" value="<?php echo isset($TinhTrang) ? $TinhTrang : $product['TinhTrang']; ?>" required>
            <br>

            <!-- Hiển thị hình ảnh hiện tại nếu có -->
            <?php if ($product['HinhSP']): ?>
                <label for="HinhSP">Hình sản phẩm hiện tại:</label>
                <img src="../../images/<?php echo $product['HinhSP']; ?>" alt="Hình sản phẩm" width="100" height="100">
                <br>
            <?php endif; ?>
            
            <label for="HinhSP">Hình sản phẩm mới (nếu muốn):</label>
            <input type="file" name="HinhSP" accept="image/*">
            <?php if (!empty($error_HinhSP)) { echo "<div style='color: red; font-size: 17px' class='error'>$error_HinhSP</div>"; } ?>
            <br>

            <input type="submit" value="Cập nhật">
        </form>
    </div>
</body>
</html>