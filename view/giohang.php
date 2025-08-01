<?php
session_start();
if (!isset($_SESSION['MaNguoiDung'])) {
    header('location:../login/login.php');
}
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "secondhandn";
$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function processCheckout($conn, $username)
{
    try {
        $conn->begin_transaction();

        // Get cart items
        $cart_query = "SELECT gh.MaGioHang, sp.MaSP, sp.MaNguoiDung as MaNguoiBan, 
                              sp.DonGia, ctgh.SoLuongSP
                       FROM chitietgiohang ctgh
                       JOIN giohang gh ON ctgh.MaGioHang = gh.MaGioHang
                       JOIN sanpham sp ON ctgh.MaSP = sp.MaSP
                       WHERE gh.MaNguoiDung = ?
                       AND sp.isConfirm = 1 AND sp.isDeleted = 0";

        $stmt = $conn->prepare($cart_query);
        $stmt->bind_param("i", $username);
        $stmt->execute();
        $cart_items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        if (empty($cart_items)) {
            throw new Exception("Giỏ hàng trống!");
        }

   
        $address_query = "SELECT DiaChi FROM nguoidung WHERE MaNguoiDung = ?";
        $stmt = $conn->prepare($address_query);
        $stmt->bind_param("i", $username);
        $stmt->execute();
        $user_address = $stmt->get_result()->fetch_assoc()['DiaChi'];

       
        $order_name = "Đơn hàng " . date('YmdHis') . rand(100, 999);
        
     
        $insert_order = "INSERT INTO donhang (MaNguoiBan, MaNguoiMua, TenDonHang, 
                                            NgayTaoDon, DiaChi, TrangThai) 
                        VALUES (?, ?, ?, CURDATE(), ?, 'Chờ xác nhận')";

        $stmt = $conn->prepare($insert_order);
        $stmt->bind_param("iiss", $cart_items[0]['MaNguoiBan'], $username, $order_name, $user_address);
        $stmt->execute();
        $order_id = $conn->insert_id;

       
        foreach ($cart_items as $item) {
            $total = $item['DonGia'] * $item['SoLuongSP'];
            
        
            $insert_detail = "INSERT INTO chitietdonhang (MaDonHang, MaSP, SoLuongSP, 
                                                        DonGiaSP, TongTien) 
                             VALUES (?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($insert_detail);
            $stmt->bind_param(
                "iiidd",
                $order_id,
                $item['MaSP'],
                $item['SoLuongSP'],
                $item['DonGia'],
                $total
            );
            $stmt->execute();

            
            $update_quantity = "UPDATE sanpham 
                              SET SoLuongTon = SoLuongTon - ? 
                              WHERE MaSP = ?
                              AND isConfirm = 1
                              AND isDeleted = 0";
            $stmt = $conn->prepare($update_quantity);
            $stmt->bind_param("ii", $item['SoLuongSP'], $item['MaSP']);
            $stmt->execute();
        }

      
        $cart_id = $cart_items[0]['MaGioHang'];
        $clear_cart = "DELETE FROM chitietgiohang WHERE MaGioHang = ?";
        $stmt = $conn->prepare($clear_cart);
        $stmt->bind_param("i", $cart_id);
        $stmt->execute();

        $conn->commit();
        return ["success" => true, "message" => "Đặt hàng thành công!"];
    } catch (Exception $e) {
        $conn->rollback();
        return ["success" => false, "message" => "Lỗi: " . $e->getMessage()];
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout'])) {
    $result = processCheckout($conn, $_SESSION['MaNguoiDung']);
    $alert_type = $result['success'] ? 'success' : 'danger';
    $alert_message = $result['message'];
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_item'])) {
    $maSP = $_POST['MaSP'];
    $username = $_SESSION['MaNguoiDung'];


    $user_query = "SELECT MaGioHang FROM giohang 
                   JOIN nguoidung ON giohang.MaNguoiDung = nguoidung.MaNguoiDung 
                   WHERE nguoidung.MaNguoiDung = ?";
    $stmt = $conn->prepare($user_query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $cart_result = $stmt->get_result();
    $cart = $cart_result->fetch_assoc();


    $remove_query = "DELETE FROM chitietgiohang 
                     WHERE MaGioHang = ? AND MaSP = ?";
    $stmt = $conn->prepare($remove_query);
    $stmt->bind_param("ii", $cart['MaGioHang'], $maSP);
    $stmt->execute();
}


$username = $_SESSION['MaNguoiDung'];
$cart_query = "
    SELECT sp.MaSP, sp.TenSP, sp.DonGia,sp.TinhTrang, ctgh.SoLuongSP 
    FROM chitietgiohang ctgh
    JOIN giohang gh ON ctgh.MaGioHang = gh.MaGioHang
    JOIN nguoidung nd ON gh.MaNguoiDung = nd.MaNguoiDung
    JOIN sanpham sp ON ctgh.MaSP = sp.MaSP
    WHERE nd.MaNguoiDung = ?
    and sp.isConfirm = 1 
    and sp.isDeleted = 0
";



$stmt = $conn->prepare($cart_query);
$stmt->bind_param("s", $username);
$stmt->execute();
$cart_result = $stmt->get_result();


$total_price = 0;
$cart_items = $cart_result->fetch_all(MYSQLI_ASSOC);

$address_query = "SELECT DiaChi FROM nguoidung WHERE MaNguoiDung = ?";
$stmt = $conn->prepare($address_query);
$stmt->bind_param("s", $username);
$stmt->execute();
$address_result = $stmt->get_result();
$user_address = $address_result->fetch_assoc()['DiaChi'];
?>


<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Giỏ Hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .bg-signature {
            background-color: rgb(33, 37, 41) !important;
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
        <h1 class="mb-4 text-center">Giỏ Hàng</h1>

        <div class="row">
            <?php if (empty($cart_items)): ?>
                <div class="col-12 text-center">
                    <div class="alert alert-info">
                        <h3>Giỏ hàng của bạn đang trống</h3>
                        <p>Có vẻ như bạn chưa thêm sản phẩm nào vào giỏ hàng</p>
                        <a href="/bandocu1/view/sanpham.php" class="btn btn-primary mt-3">
                            Khám Phá Sản Phẩm
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <div class="col-md-8">
                    <div class="card mb-3">
                        <div class="card-header bg-signature text-white">
                            Sản Phẩm Trong Giỏ Hàng
                        </div>
                        <div class="card-body">
                            <?php if (isset($alert_message)): ?>
                                <div class="alert alert-<?php echo $alert_type; ?> alert-dismissible fade show" role="alert">
                                    <?php echo $alert_message; ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>
                            <?php foreach ($cart_items as $product):
                                $item_total = $product['DonGia'] * $product['SoLuongSP'];
                                $total_price += $item_total;
                                ?>
                                <div class="row align-items-center mb-3 border-bottom pb-3">
                                    <div class="col-md-6">
                                        <h5 class="card-title"><?php echo htmlspecialchars($product['TenSP']); ?></h5>
                                        Tình trạng: <small><?php echo htmlspecialchars($product['TinhTrang']); ?></small>
                                    </div>
                                    <div class="col-md-2">
                                        <p class="mb-0">Giá: <?php echo number_format($product['DonGia'], 0, ',', '.'); ?>₫</p>
                                    </div>
                                    <div class="col-md-2">
                                        <p class="mb-0">Số Lượng: <?php echo $product['SoLuongSP']; ?></p>
                                    </div>
                                    <div class="col-md-2">
                                        <form method="POST" action="">
                                            <input type="hidden" name="MaSP" value="<?php echo $product['MaSP']; ?>">
                                            <button type="submit" name="remove_item" class="btn btn-danger btn-sm">
                                                Xóa
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

           
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-signature text-white">
                            Tóm Tắt Đơn Hàng
                        </div>
                        <div class="card-body">
                         
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tổng Cộng:</span>
                                <span><?php echo number_format($total_price, 0, ',', '.'); ?>₫</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Thuế:</span>
                                <span class="text-success">Miễn Phí</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span>Phí Vận Chuyển:</span>
                                <span class="text-success">Miễn Phí</span>
                            </div>
                            <div class="mb-3 border-bottom pb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>Địa Chỉ Giao Hàng:</strong>
                                        <p class="mb-0"><?php echo htmlspecialchars($user_address); ?></p>
                                    </div>
                                    <a href="/bandocu1/qltaikhoan.php" class="btn btn-outline-secondary btn-sm">
                                        Chỉnh Sửa
                                    </a>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="paymentMethod" class="form-label">Phương Thức Thanh Toán</label>
                                <select class="form-select" id="paymentMethod">
                                    <option value="cod">Thanh Toán Khi Nhận Hàng (COD)</option>
                                    <option value="credit">Thẻ Tín Dụng</option>
                                </select>
                            </div>

                            <div class="d-flex justify-content-between mb-3 fw-bold">
                                <span>Tổng Thanh Toán:</span>
                                <span><?php echo number_format($total_price, 0, ',', '.'); ?>₫</span>
                            </div>

                            <form method="POST" action="">
                                <button type="submit" name="checkout" class="btn btn-signature w-100"
                                    style="background-color: rgb(33,37,41); color: white;">
                                    Tiến Hành Thanh Toán
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        <?php if (isset($result) && $result['success']): ?>
            alert("<?php echo $result['message']; ?>");
            window.location.href = window.location.href; 
        <?php endif; ?>

        <?php if (isset($result) && !$result['success']): ?>
            alert("<?php echo $result['message']; ?>");
        <?php endif; ?>
    </script>
</body>

</html>

<?php

$conn->close();
?>