<?php

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
$product_id = $_GET['id']; // ID của sản phẩm

// Kết nối đến cơ sở dữ liệu và lấy thông tin người bán từ sản phẩm
$query = "SELECT * FROM sanpham WHERE MaSP = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $product_id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

// Lấy thông tin người bán (MaNguoiDung)
$seller_id = $product['MaNguoiDung'];

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
    </script>';
    unset($_SESSION['favorite_message']);
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Second-hand Store - Product Details</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css">
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
        }

        a {
            text-decoration: none;
            /* Remove underline */
            color: black;
            /* Set text color to black */
        }

        /* Optional: Change color on hover */
        a:hover {
            color: #007bff;
            /* Optional: Change color on hover (blue) */
        }

        .product-card {
            display: flex;
            align-items: center;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }

        .product-image {
            max-width: 500px;
            max-height: 400px;
            border-radius: 8px;
            margin-right: 15px;
        }

        .product-details {
            flex-grow: 0.5;
        }

        .product-actions {
            margin-top: 10px;
        }

        .user-info {
            display: flex;
            align-items: center;
            margin-top: 15px;
        }

        .user-avatar {
            max-width: 200px;
            max-height: 200px;
            border-radius: 50%;
            margin-right: 15px;
        }

        .user-info-details {
            margin-left: 100px;
        }
        .reviews-section {
            margin-top: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
            border-radius: 5px;
        }

        .reviews-section h5 {
            margin-bottom: 15px;
            font-size: 18px;
            font-weight: bold;
        }

        /* CSS để căn chỉnh avatar và tên người đánh giá bên cạnh nhau */
        .user-avatar-container {
            display: flex;
            align-items: center;
        }

        .user-avatar {
            margin-right: 10px; /* Khoảng cách giữa avatar và tên */
        }

        /* CSS cho phần hình ảnh đánh giá */
        .review-image {
            width: 100px; /* Điều chỉnh kích thước hình ảnh đánh giá */
            height: 100px;
            object-fit: cover;
            margin-top: 10px;
            margin-bottom: 10px;
        }

        /* Thêm dấu gạch ngang vào các mục */
        .review p {
            margin: 5px 0;
        }

        /* Để các dấu gạch ngang được căn chỉnh dễ dàng */
        .review p strong {
            display: inline;
        }


    </style>
</head>

<body>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
    <link rel="stylesheet" href="styles.css">
    <style>
        a {
            text-decoration: none;
            color: black;
            
        }

        /* Optional: Change color on hover */
        a:hover {
            color: orange;
            /* Optional: Change color on hover (blue) */
        }
    </style>
    <div class="container">
        <div class="product-card">
            <?php
            include_once("../controller/cSanPham.php");
            $p = new controlSanpham();
            $ketqua = $p->getChiTietSanPham($_REQUEST['mact']);
            $mact = isset($_GET['mact']) ? $_GET['mact'] : '';
            $query = "SELECT * FROM sanpham WHERE MaSP = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param('i', $mact);
                $stmt->execute();
                $product = $stmt->get_result()->fetch_assoc();

                // Lấy thông tin người bán (MaNguoiDung)
                $seller_id = $product['MaNguoiDung'];
            if ($ketqua && $row = $ketqua->fetch_assoc()) {

                echo '<form method="POST">
                    <input type="hidden" name="product_id" value="' . htmlspecialchars($mact, ENT_QUOTES, 'UTF-8') . '">
                            <div class="product-card">
                                <img src="../images/' . $row["HinhSP"] . '" alt="Product Image" class="product-image">
                                <div class="product-details">
                                    <h3>THÔNG TIN CHI TIẾT SẢN PHẨM</h3>
                                    <h5><strong>Tên sản phẩm:</strong> ' . $row["TenSP"] . '</h5>
                                    <h5><strong>Tình trạng:</strong> ' . $row["TinhTrang"] . '</h5>
                                    <h5><strong>Giá:</strong> ' . number_format($row["DonGia"], 0, ',', '.') . ' VNĐ</h5>
                                    <h5><strong>Số lượng tồn:</strong> ' . ($row["SoLuongTon"] <= 0 ? 'Hết hàng' : $row["SoLuongTon"]) . '</h5>
                                    <h5><strong>Ngày đăng sản phẩm:</strong> ' . $row["NgayThemSP"] . '</h5>
                                    <h5><strong>Mô tả chi tiết:</strong> ' . $row["MoTa"] . '</h5>
                                    <div class="product-actions">
                                    
                                        <button type="submit" name="add_to_cart" class="btn btn-outline-danger">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart-plus" viewBox="0 0 16 16">
                                                <path d="M9 5.5a.5.5 0 0 0-1 0V7H6.5a.5.5 0 0 0 0 1H8v1.5a.5.5 0 0 0 1 0V8h1.5a.5.5 0 0 0 0-1H9z"/>
                                                <path d="M.5 1a.5.5 0 0 0 0 1h1.11l.401 1.607 1.498 7.985A.5.5 0 0 0 4 12h1a2 2 0 1 0 0 4 2 2 0 0 0 0-4h7a2 2 0 1 0 0 4 2 2 0 0 0 0-4h1a.5.5 0 0 0 .491-.408l1.5-8A.5.5 0 0 0 14.5 3H2.89l-.405-1.621A.5.5 0 0 0 2 1zm3.915 10L3.102 4h10.796l-1.313 7zM6 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0m7 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0"/>
                                            </svg>
                                            Thêm vào giỏ hàng
                                        </button>';
                                        if (isset($_SESSION['MaNguoiDung'])) {

                                            $is_favorite = mysqli_num_rows(mysqli_query($conn, "SELECT MaLuu FROM luusp WHERE MaNguoiDung = '" . $_SESSION['MaNguoiDung'] . "' AND MaSP = '" . $mact . "'")) > 0;
                                            echo '
                                                            <button type="submit" name="toggle_favorite" class="btn btn-outline-danger">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart' .
                                                            (mysqli_num_rows(mysqli_query($conn, "SELECT MaLuu FROM luusp WHERE MaNguoiDung = '" . $_SESSION['MaNguoiDung'] . "' AND MaSP = '" . $mact . "'")) > 0 ? '-fill' : '') .
                                                            '" viewBox="0 0 16 16">
                                                            <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143q.09.083.176.171a3 3 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15"/>
                                                            </svg>
                                                        </button>';
                                       
                                                                if (isset($_SESSION['MaNguoiDung'])) {
                                                                    echo '<a href="../tinnhan/tinnhan.php?seller_id=' . $seller_id . '&product_id=' . $mact . '" class="btn btn-success">
                            <i class="bi bi-chat-dots"></i> Nhắn tin với người bán
                        </a>';
                                                                } else {
                                                                    echo '<p class="text-muted">Vui lòng <a href="login.php">đăng nhập</a> để nhắn tin với người bán.</p>';
                                                                }
                                        }
                                        echo '                     
                                                            </div>
                                                        </div>
                                                    </div>
                                                   ';
             
                        // Ensure $row is a valid product/user information
                        if ($row) {
                            include_once("../controller/cSanPham.php");
                            $t= new controlSanpham();
                            $ten =$t->gettennd($_REQUEST['mact']);
                            if ($ten && $rowt = $ten->fetch_assoc()) {
                            echo '<div class="user-info">';
                            echo '<img src="../images/avt.png" alt="User Avatar" class="user-avatar">';
                            echo '<div class="user-info-details">';
                            echo '<h3><a>Người bán: ' . htmlspecialchars($rowt["TenDangNhap"]) . '</a></h3>';
                            echo '<h5><strong>Địa chỉ: </strong> ' . htmlspecialchars($rowt["DiaChi"]) . '</h5>';}

                            // Fetch all reviews for this product
                            $reviews = $p->getDanhGiaSanPham($row['MaSP']); // Fetch reviews by product ID

                            // Check if there are reviews and display them
                            if ($reviews && $reviews->num_rows > 0) {
                                echo '<div class="reviews-section">';
                                echo '<h5><strong>Đánh giá sản phẩm:</strong></h5>';

                                // Loop through reviews and display each one
                                while ($review = $reviews->fetch_assoc()) {
                                    echo '<div class="review mt-4 p-3 border rounded">';
                                    
                                    // User avatar and name
                                    echo '<div class="user-avatar-container">';
                                    echo '<img src="../images/avt.png" alt="User Avatar" class="user-avatar" style="width: 50px; height: 50px;">';
                                    echo '<h5><strong>' . htmlspecialchars($review["TenNguoiMua"]) . '</strong></h5>';
                                    echo '</div>'; // End of user-avatar-container

                                    // Review Date
                                    echo '<p class="text-muted">';
                                    echo '<i class="fas fa-clock"></i> Ngày đánh giá: ' . date("d/m/Y H:i", strtotime($review['NgayDanhGia']));
                                    echo '</p>';

                                    // Rating and comment
                                    echo '<p><strong>- Số sao:</strong> ' . htmlspecialchars($review['DiemDanhGia']) . '/5 <i class="fas fa-star" style="color: #ffcc00;"></i></p>';

                                    // Display review image if available
                                    if (!empty($review['HinhDG'])) {
                                        echo '<p><strong>- Hình ảnh đánh giá:</strong></p>';
                                        echo '<img src="' . htmlspecialchars($review['HinhDG']) . '" alt="Hình ảnh đánh giá" class="review-image" style="max-width: 100%; height: auto;" />';
                                    }

                                    // Display review content
                                    echo '<p><strong>- Bình luận:</strong> ' . htmlspecialchars($review['NoiDungDanhGia']) . '</p>';
                                    echo '</div>'; // End of review
                                }

                                echo '</div>'; // End of reviews-section
                            } else {
                                // If no reviews exist for the product
                                echo '<p class="text-muted">Chưa có đánh giá nào cho sản phẩm này.</p>';
                            }

                            echo '</div>'; // End of user-info-details
                            echo '</div>'; // End of user-info
                        } else {
                            // If the product doesn't exist
                            echo "<h3 class='text-danger'>Sản phẩm không tồn tại!</h3>";
                        }


                            
                        }
            ?>
        </div>
    </div>




    <!-- Thêm JS Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.min.js"></script>
    <script src="js/index.js"></script>
</body>

</html>