<?php
session_start();
ob_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "secondhandn";

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Check if user is logged in, if not, show an alert and redirect to login page
if (!isset($_SESSION['MaNguoiDung'])) {
    if (isset($_POST['add_to_cart']) || isset($_POST['toggle_favorite'])) {
        echo '<script>
                alert("Vui lòng đăng nhập để thực hiện thao tác này!");
                window.location.href = "../login/login.php";  // Change this to your login page URL
              </script>';
        exit();
    }
}
if (isset($_POST['add_to_cart']) && isset($_SESSION['MaNguoiDung'])) {
    $product_id = $_POST['product_id'];
    $user_id = $_SESSION['MaNguoiDung'];

 
    $product_check_query = "SELECT SoLuongTon FROM sanpham WHERE MaSP = ? and isConfirm = 1 and isDeleted = 0";
    $product_stmt = $conn->prepare($product_check_query);
    $product_stmt->bind_param("i", $product_id);
    $product_stmt->execute();
    $product_result = $product_stmt->get_result();

    if ($product_result->num_rows == 0) {
        $_SESSION['cart_message'] = "Không tìm thấy sản phẩm.";
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }

    $product = $product_result->fetch_assoc();
    if ($product['SoLuongTon'] <= 0) {
        $_SESSION['cart_message'] = "Sản phẩm đã hết hàng.";
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }


    $cart_query = "SELECT MaGioHang FROM giohang WHERE MaNguoiDung = ?";
    $cart_stmt = $conn->prepare($cart_query);
    $cart_stmt->bind_param("i", $user_id);
    $cart_stmt->execute();
    $cart_result = $cart_stmt->get_result();

    if ($cart_result->num_rows == 0) {
      
        $create_cart_query = "INSERT INTO giohang (MaNguoiDung) VALUES (?)";
        $create_stmt = $conn->prepare($create_cart_query);
        $create_stmt->bind_param("i", $user_id);
        $create_stmt->execute();
        $cart_id = $conn->insert_id;
    } else {
        $cart_row = $cart_result->fetch_assoc();
        $cart_id = $cart_row['MaGioHang'];
    }

  
    $check_product_query = "SELECT * FROM chitietgiohang 
                            WHERE MaGioHang = ? AND MaSP = ?";
    $check_stmt = $conn->prepare($check_product_query);
    $check_stmt->bind_param("ii", $cart_id, $product_id);
    $check_stmt->execute();
    $existing_product_result = $check_stmt->get_result();

    if ($existing_product_result->num_rows > 0) {
     
        $update_query = "UPDATE chitietgiohang 
                         SET SoLuongSP = SoLuongSP + 1 
                         WHERE MaGioHang = ? AND MaSP = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("ii", $cart_id, $product_id);
        $update_stmt->execute();
    } else {
       
        $insert_query = "INSERT INTO chitietgiohang 
                         (MaGioHang, MaSP, SoLuongSP) 
                         VALUES (?, ?, 1)";
        $insert_stmt = $conn->prepare($insert_query);
        $insert_stmt->bind_param("ii", $cart_id, $product_id);
        $insert_stmt->execute();
    }

    $_SESSION['cart_message'] = "Thêm sản phẩm vào giỏ hàng thành công!";
    if ($product['SoLuongTon'] > 0) {
        $decrease_stock_query = "UPDATE sanpham SET SoLuongTon = SoLuongTon - 1 WHERE MaSP = ?";
        $decrease_stmt = $conn->prepare($decrease_stock_query);
        $decrease_stmt->bind_param("i", $product_id);
        $decrease_stmt->execute();
    }


    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}

if (isset($_POST['toggle_favorite']) && isset($_SESSION['MaNguoiDung'])) {
    $product_id = $_POST['product_id'];
    $user_id = $_SESSION['MaNguoiDung'];

    $check_query = "SELECT MaLuu FROM luusp WHERE MaNguoiDung = ? AND MaSP = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("ii", $user_id, $product_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {

        $delete_query = "DELETE FROM luusp WHERE MaNguoiDung = ? AND MaSP = ?";
        $delete_stmt = $conn->prepare($delete_query);
        $delete_stmt->bind_param("ii", $user_id, $product_id);
        $delete_stmt->execute();
        $_SESSION['favorite_message'] = "Đã xóa khỏi danh sách yêu thích!";
    } else {

        $insert_query = "INSERT INTO luusp (MaNguoiDung, MaSP) VALUES (?, ?)";
        $insert_stmt = $conn->prepare($insert_query);
        $insert_stmt->bind_param("ii", $user_id, $product_id);
        $insert_stmt->execute();
        $_SESSION['favorite_message'] = "Đã thêm vào danh sách yêu thích!";
    }

    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}


if (isset($_SESSION['cart_message'])) {
    echo '<script>
        alert("' . addslashes($_SESSION['cart_message']) . '");
        window.location.reload();
    </script>';
    unset($_SESSION['cart_message']);
}

if (isset($_SESSION['favorite_message'])) {
    echo '<script>
        alert("' . addslashes($_SESSION['favorite_message']) . '");
        window.location.reload();
    </script>';
    unset($_SESSION['favorite_message']);
}



?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Second-hand Store - Product Listing</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css">
    <link rel="stylesheet" href="style.css?v=2">
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
    <header class="banner">
        <div class="container py-5">

        </div>
    </header>

    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-md-3">
                <div class="filter-sidebar">
                    <div class="card mb-4" style="background-color: #FFD15C;">
                        <form class="d-flex mx-auto" method="GET" action="">
                            <input class="form-control me-2" type="search" name="txtTimKiem" placeholder="Tìm kiếm" aria-label="Search" >
                            <button class="btn btn-outline-light" type="submit" name="btnTimKiem" >
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </div>
                    <div class="card mb-4">
                        <div class="card-header " style="background-color: #FFD15C;color:#0B4A3B;">
                            <h5 class="mb-0">Sắp xếp theo</h5>
                        </div>
                        <div class="card-body" style="background-color: #FFF2C9;">
                            <div class="form-check">
                            <form action="" method="post">
                                <input type="submit" value="Sản phẩm mới nhất" name="tin"style="width: 80%; border-radius: 5px; height: 30px; margin-left: 10%; border: 1px solid;background-color: orange; border-color: orange;color:white;"> <br>
                            </form>
                            </div>
                            <div class="form-check">
                            <form action="" method="post">
                                <input type="submit" value="Giá từ thấp đến cao" name="thap"style="width: 80%; border-radius: 5px; height: 30px; margin-left: 10%; border: 1px solid;background-color: orange; border-color: orange;color:white;"> <br>
                            </form>
                            </div>
                            <div class="form-check">
                            <form action="" method="post">
                                <input type="submit" value="Giá từ cao đến thấp" name="cao" style="width: 80%; border-radius: 5px; height: 30px; margin-left: 10%; border: 1px solid;background-color: orange; border-color: orange;color:white;">
                            </form>
                            </div>
                        </div>
                    </div>

                 
                    <div class="card mb-4">
                        <div class="card-header " style="background-color: #FFD15C;color:#0B4A3B;">
                            <h5 class="mb-0">Lọc theo giá</h5>
                        </div>
                        <div class="card-body d-flex gap-2" style="background-color: #FFF2C9;">
                            <form action="" method="post" onsubmit="return validateForm()">
                                <input type="number" name="min" id="min" placeholder="Giá thấp nhất" style="width: 130px; border-radius: 5px; height: 30px; margin-left: 15px; border: 1px solid;" required> -
                                <input type="number" name="max" id="max" placeholder="Giá cao nhất" style="width: 130px; border-radius: 5px; height: 30px; border: 1px solid;" required> <br>
                                <input type="submit" name="btnloc" id="" value="Thực hiện" style="width: 80%; margin-left: 40px; height: 30px; border-radius: 10px; border: 1px solid;color:white; background-color: orange; border-color: orange;">
                            </form>
                        </div>
                    </div>
                    
                        <script>
                        function validateForm() {
                            const minPrice = document.getElementById('min').value;
                            const maxPrice = document.getElementById('max').value;

                            // Check if minPrice is greater than maxPrice
                            if (parseInt(minPrice) > parseInt(maxPrice)) {
                                alert("Giá thấp phải nhỏ hơn hoặc bằng giá cao.");
                                return false; // Prevent form submission
                            }
                            
                            return true; // Allow form submission
                        }
                        </script>

                </div>
            </div>

            <div class="col-md-9">
                <div class="swiper">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <div class="category-card">
                                <a href="?action=viewAll" class="view-all-link" height="100px"; style="color: orange;font-size: 18px;font-weight: bold;">Tất cả</a>
                            </div>
                        </div>
                        <?php
                        include_once("../controller/cLoaiSP.php");
                        $p = new controllerThuongHieu();
                        $kq = $p->getAllThuongHieu();

                        while ($r = $kq->fetch_assoc()) {
                            echo '<div class="swiper-slide">';
                            echo '<div class="category-card">';
                            echo '<a href="?ml=' . $r['MaLoaiSP'] . '" class="category-link">';
                            echo '<img src="../images/' . $r['HinhLoaiSP'] . '" alt="' . $r['TenLoaiSP'] . '" class="category-icon" width="80px">';
                            echo '<span>' . $r['TenLoaiSP'] . '</span>';
                            echo '</a>';
                            echo '</div>'; 
                            echo '</div>';

                        }
                        ?>
                    </div>
                    <!-- Add Pagination -->
                    <div class="swiper-pagination"></div>
                    <!-- Add Navigation -->
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>


                <div class="product-list">
                    <?php
                    
                    include_once("../controller/cSanPham.php");
                    $p = new controlSanpham();
                    
                    $ketqua = '';
                    if (isset($_REQUEST['txtTimKiem'])) {
                        $txtTimKiem = trim($_REQUEST['txtTimKiem']);
                    
                        // Kiểm tra từ khóa có chứa ký tự đặc biệt không
                        if (preg_match('/[^a-zA-Z0-9\sÀ-ỹ]/', $txtTimKiem)) {
                            echo "<script>alert('Từ khóa không hợp lệ, vui lòng nhập lại.');</script>";
                        } else {
                            // Nếu từ khóa hợp lệ, thực hiện tìm kiếm
                            $ketqua = $p->getAllSanphamByName($txtTimKiem);
                        }
                                            
                    } elseif (isset($_REQUEST['ml'])) {
                        // Lọc theo danh mục và các điều kiện khác
                        $ml = intval($_REQUEST['ml']);
                        $txtTimKiem = isset($_REQUEST['txtTimKiem']) ? trim($_REQUEST['txtTimKiem']) : '';
                        $min = isset($_POST['min']) ? intval($_POST['min']) : null;
                        $max = isset($_POST['max']) ? intval($_POST['max']) : null;
                        $sort = null;

                        // Xác định loại sắp xếp
                        if (isset($_POST['tin'])) {
                            $sort = 'tin'; // Sản phẩm mới nhất
                        } elseif (isset($_POST['thap'])) {
                            $sort = 'thap'; // Giá thấp đến cao
                        } elseif (isset($_POST['cao'])) {
                            $sort = 'cao'; // Giá cao đến thấp
                        }

                        // Xử lý khoảng giá khi người dùng nhấn nút lọc
                        elseif (isset($_POST['btnloc']) && !empty($_POST['min']) && !empty($_POST['max'])&&isset($_POST['tin'])&&isset($_POST['thap'])&&isset($_POST['cao'])) {
                            $min = intval($_POST['min']);
                            $max = intval($_POST['max']);
                        }

                        // Gọi phương thức getFilteredProducts với các tham số
                        $ketqua = $p->getFilteredProducts($ml, $min, $max, $sort, $txtTimKiem);
                    }

                                        
                     elseif (isset($_POST['btnloc'])) {
                        // Lọc theo khoảng giá
                        $min = intval($_POST['min']);
                        $max = intval($_POST['max']);
                        $ketqua = $p->getSanPhamByPriceRange($min, $max);
                    }  elseif (isset($_POST['tin'])) {
                        // Lọc theo sản phẩm mới nhất
                        $ketqua = $p->getAllSanPhamBytin();
                    } elseif (isset($_POST['thap'])) {
                        // Lọc theo giá thấp nhất
                        $ketqua = $p->getAllSanPhamBygiathap();
                    } elseif (isset($_POST['cao'])) {
                        // Lọc theo giá cao nhất
                        $ketqua = $p->getAllSanPhamBygiacao();
                    } else {
                        // Mặc định: lấy tất cả sản phẩm
                        $ketqua = $p->getAllSanpham();
                    }
                    
                    if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'chitietsp') {
                        include_once("../view/chitietsp.php");
                    }
                                
                    if (!$ketqua || mysqli_num_rows($ketqua) === 0) {
                        echo "<p>Không có sản phẩm nào!</p>";
                    } else {
                        while ($r = mysqli_fetch_assoc($ketqua)) {
                            echo "<div class='product'>";
                            echo "<img src='../images/" . htmlspecialchars($r['HinhSP']) . "' alt='" . htmlspecialchars($r['TenSP']) . "' style='width: 250px; height: 250px; object-fit: cover;'>";
                            echo "<h5><a href='?action=chitietsp&mact=" . htmlspecialchars($r['MaSP']) . "'>" . htmlspecialchars($r['TenSP']) . "</a></h5>";
                            echo "<p>Giá Bán: " . number_format($r['DonGia'], 0, ',', '.') . " VNĐ</p>";
                            echo '<div class="button-group">
                            <form method="post" style="display: inline-block; margin-right: 5px;">
                              <input type="hidden" name="product_id" value="' . $r['MaSP'] . '">
                              <button type="submit" name="add_to_cart" class="btn btn-outline-danger">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart-plus" viewBox="0 0 16 16">
                                  <path d="M9 5.5a.5.5 0 0 0-1 0V7H6.5a.5.5 0 0 0 0 1H8v1.5a.5.5 0 0 0 1 0V8h1.5a.5.5 0 0 0 0-1H9z"/>
                                  <path d="M.5 1a.5.5 0 0 0 0 1h1.11l.401 1.607 1.498 7.985A.5.5 0 0 0 4 12h1a2 2 0 1 0 0 4 2 2 0 0 0 0-4h7a2 2 0 1 0 0 4 2 2 0 0 0 0-4h1a.5.5 0 0 0 .491-.408l1.5-8A.5.5 0 0 0 14.5 3H2.89l-.405-1.621A.5.5 0 0 0 2 1zm3.915 10L3.102 4h10.796l-1.313 7zM6 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0m7 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0"/>
                                </svg>
                                Thêm vào giỏ hàng
                              </button>
                            </form>
                            
                            <form method="post" style="display: inline-block;">
                              <input type="hidden" name="product_id" value="' . $r['MaSP'] . '">
                              <button type="submit" name="toggle_favorite" class="btn btn-outline-danger">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" 
                                  class="bi bi-heart' . (isset($_SESSION['MaNguoiDung']) && mysqli_num_rows(mysqli_query($conn, "SELECT MaLuu FROM luusp WHERE MaNguoiDung = '" . $_SESSION['MaNguoiDung'] . "' AND MaSP = '" . $r['MaSP'] . "'")) > 0 ? '-fill' : '') . '" 
                                  viewBox="0 0 16 16">
                                  <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143q.09.083.176.171a3 3 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15"/>
                                </svg>
                              </button>
                            </form>
                          </div>';
                            echo "</div>";
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <div class="footer-container">
            <div class="footer-section">
                <h4>Liên hệ</h4>
                <p>Địa chỉ: 123 Đường ABC, Phường XYZ, TP.HCM</p>
                <p>Email: support@example.com</p>
                <p>Điện thoại: 0123-456-789</p>
            </div>
            <div class="footer-section">
                <h4>Hỗ trợ</h4>
                <ul>
                    <li><a href="#">Câu hỏi thường gặp</a></li>
                    <li><a href="#">Chính sách đổi trả</a></li>
                    <li><a href="#">Chính sách bảo mật</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Kết nối</h4>
                <a href="#"><i class="fab fa-facebook fa-lg me-3"></i></a>
                <a href="#"><i class="fab fa-instagram fa-lg me-3"></i></a>
                <a href="#"><i class="fab fa-twitter fa-lg"></i></a>
            </div>
        </div>
    </footer>

    <!-- JavaScript for Swiper Initialization -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
    <script>
        const swiper = new Swiper('.swiper', {
            slidesPerView: 6,
            spaceBetween: 5,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
        });




    </script>



</body>

</html>