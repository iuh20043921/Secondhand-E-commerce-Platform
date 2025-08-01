<?php

session_start();
// $user_id = $_SESSION['email'];



// Kết nối đến cơ sở dữ liệu
$servername = "localhost";
$username = "root"; // thay thế bằng tên đăng nhập cơ sở dữ liệu của bạn
$password = ""; // thay thế bằng mật khẩu cơ sở dữ liệu của bạn
$dbname = "secondhandn";

$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Kiểm tra xem form có được gửi hay không

// Initialize error variables
$errors = [
    'TenSP' => '',
    'MoTa' => '',
    'DonGia' => '',
    'SoLuongTon' => '',
    'HinhSP' => ''
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy giá trị từ form
    $TenSP = htmlspecialchars($_POST['TenSP']);
    $MaLoaiSP = $_POST['MaLoaiSP'];
    $DonGia = $_POST['DonGia'];
    $SoLuongTon = $_POST['SoLuongTon'];
    $TinhTrang = $_POST['TinhTrang'];
    $MoTa = htmlspecialchars($_POST['MoTa']);

    // Kiểm tra Tên sản phẩm không chứa ký tự đặc biệt
    if (!preg_match("/^[a-zA-Z0-9\s]+$/", $TenSP)) {
        $errors['TenSP'] = "Tên sản phẩm không được chứa ký tự đặc biệt!";
    }

    // Kiểm tra Mô tả không quá 200 ký tự
    if (strlen($MoTa) > 200) {
        $errors['MoTa'] = "Mô tả không được quá 200 ký tự!";
    }

    // Kiểm tra Giá phải lớn hơn 1000
    if ($DonGia <= 1000) {
        $errors['DonGia'] = "Giá sản phẩm phải lớn hơn 1000!";
    }

    // Kiểm tra Số lượng phải lớn hơn 0
    if ($SoLuongTon <= 0) {
        $errors['SoLuongTon'] = "Số lượng phải lớn hơn 0!";
    }

    // Xử lý file hình ảnh
    $target_dir = "../images/";
    $target_file = $target_dir . basename($_FILES["HinhSP"]["name"]);
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $allowedTypes = ['jpg', 'jpeg', 'png'];
    $maxFileSize = 2 * 1024 * 1024; // 2 MB

    // Kiểm tra lỗi khi tải tệp hình ảnh
    if ($_FILES["HinhSP"]["error"] != UPLOAD_ERR_OK) {
        $errors['HinhSP'] = "Lỗi khi tải lên tệp hình ảnh.";
    }

    // Kiểm tra loại tệp hình ảnh (jpg, jpeg, png)
    if (!in_array($fileType, $allowedTypes)) {
        $errors['HinhSP'] = "Chỉ chấp nhận các định dạng hình ảnh JPG, JPEG, PNG.";
    }

    // Kiểm tra kích thước tệp hình ảnh
    if ($_FILES["HinhSP"]["size"] > $maxFileSize) {
        $errors['HinhSP'] = "File hình ảnh quá lớn! Vui lòng chọn lại (tối đa 2MB).";
    }

    // Nếu không có lỗi, thực hiện lưu dữ liệu
    if (empty(array_filter($errors))) {
        if (move_uploaded_file($_FILES["HinhSP"]["tmp_name"], $target_file)) {
            // Chuẩn bị truy vấn để thêm sản phẩm vào cơ sở dữ liệu
            $sql = "INSERT INTO sanpham (TenSP, MaLoaiSP, DonGia, SoLuongTon, TinhTrang, MoTa, HinhSP, MaNguoiDung)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

            $MaNguoiDung = $_SESSION['MaNguoiDung'];

            // Thực hiện truy vấn
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("siidsssi", $TenSP, $MaLoaiSP, $DonGia, $SoLuongTon, $TinhTrang, $MoTa, $target_file, $MaNguoiDung);

            // Thực thi truy vấn
            if ($stmt->execute()) {
                echo "Đăng tin thành công!";
                header("Location: index.php");
                exit();
            } else {
                error_log("Error executing query: " . $stmt->error);
                echo "Đã có lỗi xảy ra. Vui lòng thử lại sau.";
            }

            // Đóng kết nối
            $stmt->close();
        } else {
            $errors['HinhSP'] = "Lỗi khi di chuyển tệp hình ảnh.";
        }
    }
}

$conn->close();
?>
