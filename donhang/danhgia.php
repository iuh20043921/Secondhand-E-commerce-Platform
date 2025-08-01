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

if (!isset($_SESSION['MaNguoiDung']) ) {
    exit('Unauthorized access');
}

$userId = $_SESSION['MaNguoiDung'];


?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>C2C Marketplace - Đơn Mua</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../styles.css">
    <link rel="stylesheet" href="stylesCSS.css">
</head>
<style>
    .product-image {
    width: 150px;
    height: 150px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid #ddd;
}
    .star-rating i {
    color: #ccc; /* Màu xám cho các ngôi sao chưa được chọn */
    cursor: pointer; /* Chỉ con trỏ dạng bàn tay khi di chuột qua sao */
}

.star-rating i.active {
    color: #ffcc00; /* Màu vàng khi sao được chọn */
}
.d-flexb {
    display: flex;
    justify-content: center; /* Canh giữa ngang */
    align-items: center; /* Canh giữa dọc */
    gap: 15px; /* Khoảng cách giữa các nút */
}

button, .btn-secondary {
    flex: 1; /* Để hai nút bằng nhau nếu cần */
    text-align: center;
    padding: 10px 15px;
  
}

</style>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Đánh giá sao</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <!-- Custom CSS -->
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
    <?php
// Ensure that the madon and masp are provided in the URL
if (!isset($_GET['madon']) || !isset($_GET['masp'])) {
    exit('Invalid request');
}
$manguoidung = $_SESSION["MaNguoiDung"];
$madh = $_GET["madon"];
$masp = $_GET["masp"];
$_SESSION["madon"] = $madh;

include_once("../controller/cSanPham.php");
$p = new controlSanpham();
$tblsp = $p->getcthd($madh);

$arraytensp = [];

if ($tblsp) {
    while ($r = $tblsp->fetch_assoc()) {
        // Check if the product ID matches the masp from the URL
        if ($r["MaSP"] == $masp) {
            $arraytensp[] = [
                "MaSP" => $r["MaSP"],
                "TenSP" => $r["TenSP"],
                "HinhSP" => $r["HinhSP"]
            ];
        }
    }
}
?>
<div class="container mt-5">
    <h2 class="text-center" style="color:orange">Đánh giá sản phẩm</h2>

    <?php if (count($arraytensp) > 0): ?>
        <?php foreach ($arraytensp as $product): ?>
            <form id="rating-form-<?php echo $product['MaSP']; ?>" class="mt-4 p-4 border rounded bg-light" method="post" enctype="multipart/form-data">
            <div class="d-flex align-items-center mb-3">
                    <img src="../images/<?php echo htmlspecialchars($product['HinhSP']); ?>" alt="<?php echo htmlspecialchars($product['TenSP']); ?>" class="product-image">
                    <h5 class="ms-3" ><?php echo htmlspecialchars($product['TenSP']); ?></h5>
                </div>

                <!-- Star Rating -->
                <div class="mb-3 text-center star-rating">
                    <i class="fas fa-star" data-value="1"></i>
                    <i class="fas fa-star" data-value="2"></i>
                    <i class="fas fa-star" data-value="3"></i>
                    <i class="fas fa-star" data-value="4"></i>
                    <i class="fas fa-star" data-value="5"></i>
                </div>

                <!-- Hidden inputs -->
                <input type="hidden" id="rating-value-<?php echo $product['MaSP']; ?>" name="rating" value="0">
                <input type="hidden" name="masp" value="<?php echo $product['MaSP']; ?>">

                <!-- Feedback Input -->
                <div class="mb-3">
                    <label for="feedback-<?php echo $product['MaSP']; ?>" class="form-label">Nhận xét (tối đa 200 từ)</label>
                    <textarea class="form-control" id="feedback-<?php echo $product['MaSP']; ?>" name="feedback" rows="3" maxlength="200" placeholder="Nhập nhận xét của bạn"></textarea>
                </div>

                <!-- Image Upload -->
                <div class="mb-3">
                    <label class="form-label">Tải ảnh đánh giá (tối đa 1MB, định dạng JPG, PNG)</label>
                    <input class="form-control" type="file" name="review_image" accept="image/jpeg, image/png">
                </div>
                <div class="d-flex justify-content-center gap-3 mt-3">
                    <button type="submit" name="sub" class="btn btn-primary w-50">Gửi đánh giá</button>
                    <a href="donmua.php" class="btn btn-secondary w-50">Quay lại</a>
                </div>


            </form>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="text-center">Không tìm thấy sản phẩm này trong đơn hàng.</p>
    <?php endif; ?>
</div>

<?php
if (isset($_POST["sub"])) {
    $manguoidung = $_SESSION["MaNguoiDung"];
    $masp = $_POST["masp"];
    $feedback = trim($_POST["feedback"]);
    $rating = intval($_POST["rating"]);
    $imagePath = '';

    // Kiểm tra rating hợp lệ
    if ($rating <= 0 || $rating > 5) {
        echo "<script>alert('Số sao không hợp lệ.');</script>";
        exit;
    }

    // Kiểm tra nhận xét
    if (empty($feedback)) {
        echo "<script>alert('Vui lòng nhập nhận xét của bạn.');</script>";
        exit;
    }

    // Kiểm tra độ dài nhận xét
    if (strlen($feedback) > 200) {
        echo "<script>alert('Nhận xét không được vượt quá 200 ký tự.');</script>";
        exit;
    }

    // Kiểm tra đã đánh giá sản phẩm chưa
    include_once("../controller/cSanPham.php");
    $p = new controlSanpham();
    $existingReview = $p->checkUserReview($manguoidung, $masp);

    if ($existingReview) {
        echo "<script>alert('Bạn đã đánh giá sản phẩm này rồi!');</script>";
    } else {
        // Xử lý upload ảnh
        if (isset($_FILES['review_image']) && $_FILES['review_image']['error'] === UPLOAD_ERR_OK) {
            $allowedTypes = ['image/jpeg', 'image/png'];
            $fileInfo = new finfo(FILEINFO_MIME_TYPE);
            $fileType = $fileInfo->file($_FILES['review_image']['tmp_name']);
            
            if (in_array($fileType, $allowedTypes) && $_FILES['review_image']['size'] <= 1048576) {
                $uploadDir = "../uploads/reviews/";
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $imagePath = $uploadDir . uniqid() . "_" . basename($_FILES['review_image']['name']);
                move_uploaded_file($_FILES['review_image']['tmp_name'], $imagePath);
            } else {
                echo "<script>alert('Ảnh không hợp lệ. Chỉ chấp nhận JPG/PNG và dưới 1MB.');</script>";
            }
        }

        // Gửi đánh giá vào cơ sở dữ liệu
        $tblinsert = $p->getinsertdanhgia($manguoidung, $masp, $feedback, $rating, $imagePath);
        if ($tblinsert) {
            echo "<script>alert('Đánh giá đã được gửi thành công!');</script>";
        } else {
            echo "<script>alert('Có lỗi xảy ra khi gửi đánh giá.');</script>";
        }
    }
}
?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
   $(document).ready(function () {
    
    // Gắn sự kiện click cho các ngôi sao
    $(".star-rating i").on("click", function () {
        const rating = $(this).data("value"); // Lấy giá trị sao được nhấn
        const parent = $(this).closest(".star-rating"); // Lấy phần tử cha .star-rating

        // Xóa lớp "active" trên tất cả các sao
        parent.find("i").removeClass("active");

        // Thêm lớp "active" cho các sao có giá trị <= sao được chọn
        parent.find("i").each(function () {
            if ($(this).data("value") <= rating) {
                $(this).addClass("active");
            }
        });

        // Cập nhật giá trị vào input ẩn (nằm ngay sau .star-rating)
        parent.next('input[name="rating"]').val(rating);
    });

    // Hiển thị lại rating khi trang tải lại hoặc khi đánh giá đã gửi
    $(".star-rating i").each(function () {
        const rating = $(this).closest('form').find('input[name="rating"]').val();
        if ($(this).data("value") <= rating) {
            $(this).addClass("active");
        }
    });

    // Kiểm tra dữ liệu trước khi gửi form
    $("form").on("submit", function (e) {
        const formId = $(this).attr("id");
        const rating = $(`#${formId} input[name="rating"]`).val();
        const feedback = $(`#${formId} textarea[name="feedback"]`).val();

        // Kiểm tra rating
        if (rating == 0 || isNaN(rating)) {
            alert("Vui lòng chọn số sao trước khi gửi đánh giá.");
            e.preventDefault();
        }

        // Kiểm tra nhận xét
        if (feedback.trim() === "") {
            alert("Vui lòng nhập nhận xét của bạn.");
            e.preventDefault();
        }

        // Kiểm tra độ dài nhận xét
        if (feedback.length > 200) {
            alert("Nhận xét không được vượt quá 200 ký tự.");
            e.preventDefault();
        }
    });
});

</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
