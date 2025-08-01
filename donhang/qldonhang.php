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

if (!isset($_SESSION['MaNguoiDung'])) {
    header("Location: login.php");
    exit();
}




$sql = "SELECT dh.*, nd.TenNguoiDung as TenNguoiMua, seller.TenNguoiDung as TenNguoiBan,
        (SELECT SUM(TongTien) FROM chitietdonhang WHERE MaDonHang = dh.MaDonHang) as TongDonHang
        FROM donhang dh 
        JOIN nguoidung nd ON dh.MaNguoiMua = nd.MaNguoiDung
        JOIN nguoidung seller ON dh.MaNguoiBan = seller.MaNguoiDung
        ORDER BY dh.NgayTaoDon DESC";




$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$orders = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>C2C Marketplace - Đơn Bán</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../styles.css">
    <link rel="stylesheet" href="styleCSS.css">
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
    <div class="container content mt-5">
        <h2>QUẢN LÝ ĐƠN HÀNG - ADMIN</h2>
       
        
        <ul class="nav nav-tabs mb-4">
            <li class="nav-item">
                <a class="nav-link active" href="#" data-status="all">Tất cả <span class="badge bg-primary" id="count-all">0</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" data-status="pending">Chờ xác nhận <span class="badge bg-primary" id="count-pending">0</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" data-status="waiting">Chờ giao hàng <span class="badge bg-primary" id="count-waiting">0</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" data-status="delivered">Đã giao <span class="badge bg-primary" id="count-delivered">0</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" data-status="canceled">Đã Hủy đơn <span class="badge bg-primary" id="count-canceled">0</span></a>
            </li>
        </ul>

        <div id="order-table">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Mã Đơn hàng</th>
                        <th>Người mua</th>
                        <th>Người bán</th>
                        <th>Ngày đặt hàng</th>
                        <th>Trạng thái</th>
                        <th>Tổng tiền</th>
                        <th>Địa chỉ giao hàng</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                 
                <?php foreach ($orders as $order): ?>
                        <tr data-status="<?php
                        $status = strtolower($order['TrangThai']);
                        switch ($status) {
                            case 'chờ xác nhận':
                            case 'cho xac nhan':
                                echo 'pending';
                                break;
                                case 'chờ giao hàng':
                                    case 'cho giao hang':
                                        echo 'waiting';
                                break;
                            case 'đã giao':
                            case 'Đã giao':
                                echo 'delivered';
                                break;
                            case 'đã hủy đơn':
                            case 'Đã hủy đơn':
                                echo 'canceled';
                                break;
                            default:
                                echo $status;
                        }
                        ?>">
                            <td><?php echo $order['MaDonHang']; ?></td>
                            <td><?php echo htmlspecialchars($order['TenNguoiMua']); ?></td>
                            <td><?php echo htmlspecialchars($order['TenNguoiBan']); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($order['NgayTaoDon'])); ?></td>
                            <td>
                                <select class="form-select form-select-sm order-status" 
                                        data-order-id="<?php echo $order['MaDonHang']; ?>"
                                        <?php echo ($order['TrangThai'] == 'Đã hủy đơn' || $order['TrangThai'] == 'Đã giao') ? 'disabled' : ''; ?>>
                                    <option value="Chờ xác nhận" <?php echo ($order['TrangThai'] == 'Chờ xác nhận') ? 'selected' : ''; ?>>
                                        Chờ xác nhận
                                    </option>
                                    <option value="Chờ giao hàng" <?php echo ($order['TrangThai'] == 'Chờ giao hàng') ? 'selected' : ''; ?>>
                                        Chờ giao hàng
                                    </option>
                                    <option value="Đã giao" <?php echo ($order['TrangThai'] == 'Đã giao') ? 'selected' : ''; ?>>
                                        Đã giao
                                    </option>
                                    <option value="Đã hủy đơn" <?php echo ($order['TrangThai'] == 'Đã hủy đơn') ? 'selected' : ''; ?>>
                                        Đã hủy đơn
                                    </option>
                                </select>
                            </td>
                            <td><?php echo number_format($order['TongDonHang'], 0, ',', '.') . ' VND'; ?></td>
                            <td><?php echo htmlspecialchars($order['DiaChi']); ?></td>
                            <td class="action-buttons">
                                <button class="btn btn-sm btn-primary" onclick="showOrderDetails(<?php echo $order['MaDonHang']; ?>)">
                                    Chi tiết đơn hàng
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Order Details Modal -->
    <div class="modal fade" id="orderDetailModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Chi tiết đơn hàng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="orderDetailContent">
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            updateOrderCounts();
            $('.nav-link').on('click', function (e) {
                e.preventDefault();
                var status = $(this).data('status');

                // Update active tab
                $('.nav-link').removeClass('active');
                $(this).addClass('active');

                // Show/hide rows based on status
                if (status === 'all') {
                    $('#order-table tbody tr').show();
                } else {
                    $('#order-table tbody tr').each(function () {
                        var rowStatus = $(this).data('status');
                        if (rowStatus === status) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    });
                }
            });

            // Handle order status change
            $('.order-status').on('change', function() {
                const orderId = $(this).data('order-id');
                const newStatus = $(this).val();
                const row = $(this).closest('tr');

                if (confirm('Bạn có chắc chắn muốn thay đổi trạng thái đơn hàng này?')) {
                    $.ajax({
                        url: 'update_order_status_admin.php',
                        type: 'POST',
                        data: {
                            orderid: orderId,
                            status: newStatus
                        },
                        success: function(response) {
                            if (response === 'success') {
                                // Update the row's status data attribute
                                const statusMap = {
                                    'Chờ xác nhận': 'pending',
                                    'Chờ giao hàng': 'waiting',
                                    'Đã giao': 'delivered',
                                    'Đã hủy đơn': 'canceled'
                                };
                                row.data('status', statusMap[newStatus]);
                                
                                // Disable select if order is completed or canceled
                                if (newStatus === 'Đã giao' || newStatus === 'Đã hủy đơn') {
                                    row.find('.order-status').prop('disabled', true);
                                }
                                
                                updateOrderCounts();
                                alert('Cập nhật trạng thái đơn hàng thành công!');
                            } else {
                                alert('Có lỗi xảy ra khi cập nhật trạng thái đơn hàng');
                            }
                        },
                        error: function() {
                            alert('Có lỗi xảy ra khi cập nhật trạng thái đơn hàng');
                        }
                    });
                } else {
                    // Reset the select to its previous value if user cancels
                    $(this).val($(this).find('option:selected').val());
                }
            });

            function updateOrderCounts() {
                let counts = {
                    all: 0,
                    pending: 0,
                    waiting: 0,
                    delivered: 0,
                    canceled: 0
                };

                $('#order-table tbody tr').each(function () {
                    const status = $(this).data('status');
                    counts.all++;

                    if (counts.hasOwnProperty(status)) {
                        counts[status]++;
                    }
                });

                // Update the badges
                for (let status in counts) {
                    $(`#count-${status}`).text(counts[status]);
                }
            }

            window.showOrderDetails = function(orderId) {
                $.ajax({
                    url: 'get_order_details_admin.php',
                    type: 'GET',
                    data: { orderid: orderId },
                    success: function(response) {
                        $('#orderDetailContent').html(response);
                        $('#orderDetailModal').modal('show');
                    }
                });
            };
        });
    </script>
    
</body>
</html>