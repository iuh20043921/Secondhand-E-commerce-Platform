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

if (!isset($_SESSION['MaNguoiDung']) || !isset($_GET['orderid'])) {
    exit('Unauthorized access');
}

$orderId = $_GET['orderid'];
$userId = $_SESSION['MaNguoiDung'];


$orderCheck = $conn->prepare("SELECT MaNguoiMua FROM donhang WHERE MaDonHang = ?");
$orderCheck->bind_param("i", $orderId);
$orderCheck->execute();
$orderCheck->get_result();


$sql = "SELECT ct.*, sp.TenSP, sp.HinhSP 
        FROM chitietdonhang ct
        JOIN sanpham sp ON ct.MaSP = sp.MaSP
        join donhang dh on ct.MaDonHang=dh.MaDonHang
        WHERE ct.MaDonHang = ?";


$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $orderId);
$stmt->execute();
$result = $stmt->get_result();
$orderDetails = $result->fetch_all(MYSQLI_ASSOC);


$total = 0;
?>

<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>Hình ảnh</th>
                <th>Tên sản phẩm</th>
                <th>Số lượng</th>
                <th>Đơn giá</th>
                <th>Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orderDetails as $item): 
                $total += $item['TongTien'];
            ?>
            <tr>
            <td><img src="../images/<?php echo htmlspecialchars($item['HinhSP']); ?>" alt="<?php echo htmlspecialchars($item['TenSP']); ?>" style="width: 50px; height: 50px;"></td>
                <td><?php echo htmlspecialchars($item['TenSP']); ?></td>
                <td><?php echo $item['SoLuongSP']; ?></td>
                <td><?php echo number_format($item['DonGiaSP'], 0, ',', '.') . ' VND'; ?></td>
                <td><?php echo number_format($item['TongTien'], 0, ',', '.') . ' VND'; ?></td>
                
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="text-end"><strong>Tổng cộng:</strong></td>
                <td><strong><?php echo number_format($total, 0, ',', '.') . ' VND'; ?></strong></td>
            </tr>
        </tfoot>
    </table>
</div>