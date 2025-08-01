<?php
include_once("ketnoi.php");
date_default_timezone_set('Asia/Ho_Chi_Minh');
class msanpham {
    private $con;

    public function __construct() {
        $p = new clsketnoi();
        $this->con = $p->MoKetNoi();
    }

    public function __destruct() {
        $p = new clsketnoi();
        $p->DongKetNoi($this->con);
    }

    public function selectAllSanpham() {
        $truyvan = "SELECT * FROM sanpham s JOIN loaisanpham l ON s.MaLoaiSP = l.MaLoaiSP ";
        $kq = mysqli_query($this->con, $truyvan);
        if (!$kq) {
            die("Truy vấn thất bại: " . mysqli_error($this->con));
        }
        return $kq;
    }

    public function selectChiTietSanpham($mact) {
        $truyvan = "SELECT 
    s.*, 
    l.*, 
    dg.*, 
    ct.*, 
    dh.*, 
    seller.TenDangNhap AS TenNguoiBan, 
    buyer.TenDangNhap AS TenNguoiMua
FROM 
    loaisanpham l
LEFT JOIN 
    sanpham s ON s.MaLoaiSP = l.MaLoaiSP
LEFT JOIN 
    chitietdonhang ct ON s.MaSP = ct.MaSP
LEFT JOIN 
    donhang dh ON ct.MaDonHang = dh.MaDonHang
LEFT JOIN 
    nguoidung seller ON dh.MaNguoiBan = seller.MaNguoiDung
LEFT JOIN 
    nguoidung buyer ON dh.MaNguoiMua = buyer.MaNguoiDung
LEFT JOIN 
    danhgiasanpham dg ON dg.MaNguoiDung = buyer.MaNguoiDung
WHERE 
    s.MaSP = $mact";
        
        $kq = mysqli_query($this->con, $truyvan);
        if (!$kq) {
            die("Truy vấn thất bại: " . mysqli_error($this->con));
        }
        return $kq;
    }
    

    public function selectAllSanPhamByTH($th) {
        $sql = "SELECT * FROM sanpham s JOIN loaisanpham l ON s.MaLoaiSP = l.MaLoaiSP WHERE s.MaLoaiSP = $th";
        $kq = mysqli_query($this->con, $sql);
        if (!$kq) {
            die("Truy vấn thất bại: " . mysqli_error($this->con));
        }
        return $kq;
    }

    public function selectAllSanphamByName($ten) {
        $ten = mysqli_real_escape_string($this->con, $ten); // Bảo mật truy vấn
        $truyvan = "SELECT * FROM sanpham s JOIN loaisanpham l ON s.MaLoaiSP = l.MaLoaiSP WHERE TenSP LIKE '%$ten%'";
        $ketqua = mysqli_query($this->con, $truyvan);
        if (!$ketqua) {
            die("Truy vấn thất bại: " . mysqli_error($this->con));
        }
        return $ketqua;
    }
    public function selectAllSanphamByTinhTrang($tinhTrang) {
        $tinhTrang = mysqli_real_escape_string($this->con, $tinhTrang); // Sanitize input
        $sql = "SELECT * FROM sanpham WHERE TinhTrang = '$tinhTrang'";
    
        $ketqua = mysqli_query($this->con, $sql);
        if (!$ketqua) {
            die("Truy vấn thất bại: " . mysqli_error($this->con));
        }
        return $ketqua;
    }
    
    
    // In controller cSanPham.php
    public function selectSanPhamByPriceRange($min, $max) {
        $p = new clsketnoi();
        $con = $p->MoKetNoi();
        
        $sql = "SELECT * FROM sanpham WHERE DonGia BETWEEN $min AND $max";
        
        
        $ketqua = mysqli_query($con, $sql);
        $p->DongKetNoi($con);
        return $ketqua;
    }
    
public function selecAllSanPhamtheotin() {
    $p = new clsketnoi();
    $con = $p->MoKetNoi();
    $truyvan = "SELECT * FROM sanpham s JOIN loaisanpham l ON s.MaLoaiSP = l.MaLoaiSP ORDER BY s.NgayThemSP DESC";

    
    $ketqua = mysqli_query($con, $truyvan);
    $p->DongKetNoi($con);
    return $ketqua;
}

public function selecAllSanPhamtheogiathap() {
    $p = new clsketnoi();
    $con = $p->MoKetNoi();
    $truyvan = "SELECT * FROM sanpham s JOIN loaisanpham l ON s.MaLoaiSP = l.MaLoaiSP ORDER BY s.DonGia ASC";

    // If a category filter is provided, add it to the query
    

    $ketqua = mysqli_query($con, $truyvan);
    $p->DongKetNoi($con);
    return $ketqua;
}

public function selecAllSanPhamtheogiacao() {
    $p = new clsketnoi();
    $con = $p->MoKetNoi();
    $truyvan = "SELECT * FROM sanpham s JOIN loaisanpham l ON s.MaLoaiSP = l.MaLoaiSP ORDER BY s.DonGia DESC";

    // If a category filter is provided, add it to the query
    

    $ketqua = mysqli_query($con, $truyvan);
    $p->DongKetNoi($con);
    return $ketqua;
}

    // Assuming your database connection is already established (e.g., $conn)
    public function getFilteredProducts($ml, $min, $max, $sort, $txtTimKiem) {
        // Kết nối cơ sở dữ liệu
        $p = new clsketnoi();
        $con = $p->MoKetNoi();
    
        // Khởi tạo câu truy vấn
        $sql = "SELECT * FROM sanpham WHERE 1=1";
    
        // Lọc theo mã loại sản phẩm
        if (!empty($ml)) {
            $sql .= " AND MaLoaiSP = " . intval($ml);
        }
    
        // Lọc theo từ khóa tìm kiếm
        if (!empty($txtTimKiem)) {
            $txtTimKiem = mysqli_real_escape_string($con, $txtTimKiem);
            $sql .= " AND TenSP LIKE '%$txtTimKiem%'";
        }
    
        // Lọc theo khoảng giá
        if (!is_null($min) && !is_null($max)) {
            $sql .= " AND DonGia BETWEEN $min AND $max";
        }
    
        // Sắp xếp kết quả
        if (!empty($sort)) {
            if ($sort == 'tin') {
                $sql .= " ORDER BY NgayThemSP DESC";
            } elseif ($sort == 'thap') {
                $sql .= " ORDER BY DonGia ASC";
            } elseif ($sort == 'cao') {
                $sql .= " ORDER BY DonGia DESC";
            }
        }
    
        // Debug SQL nếu cần
        error_log("SQL Query: " . $sql);
    
        // Thực thi truy vấn
        $result = mysqli_query($con, $sql);
    
        // Kiểm tra kết quả
        if (!$result) {
            die("Query failed: " . mysqli_error($con));
        }
    
        return $result; // Trả về kết quả
    }
    
    
    
    public function getcthd($madon) {
        $p = new clsketnoi();
        $con = $p->MoKetNoi();
        $truyvan = "SELECT * FROM chitietdonhang ctdh join sanpham sp on ctdh.MaSP = sp.MaSP where MaDonHang = '$madon'";
    
        $ketqua = mysqli_query($con, $truyvan);
        $p->DongKetNoi($con);
        return $ketqua;
    }
  
        public function checkUserReview($manguoidung, $masp) {
            $p = new clsketnoi(); // Kết nối cơ sở dữ liệu
            $con = $p->MoKetNoi();
    
            // Truy vấn kiểm tra số lượng đánh giá
            $stmt = $con->prepare("SELECT COUNT(*) as total FROM danhgiasanpham WHERE MaNguoiDung = ? AND MaSP = ?");
            $stmt->bind_param("ii", $manguoidung, $masp);
            $stmt->execute();
            $result = $stmt->get_result();
    
            $row = $result->fetch_assoc();
            $p->DongKetNoi($con); // Đóng kết nối
    
            return $row['total'] > 0; // Trả về true nếu đã đánh giá, false nếu chưa
        }
    
    
    

    public function insertdanhgia($manguoidung,$masp,$feedback,$rating,$hinhdg) {
        $ngaylap = date("Y-m-d H:i:s");
        $p = new clsketnoi();
        $con = $p->MoKetNoi();
        $truyvan = "INSERT INTO `danhgiasanpham`(`MaNguoiDung`,`MaSP`, `NoiDungDanhGia`, `DiemDanhGia`, `NgayDanhGia`,`HinhDG`)
                    VALUES ('$manguoidung','$masp','$feedback','$rating','$ngaylap','$hinhdg')";
    
        $ketqua = mysqli_query($con, $truyvan);
        $p->DongKetNoi($con);
        return $ketqua;
    }
    public function gettennd($madon) {
        $p = new clsketnoi();
        $con = $p->MoKetNoi();
        $truyvan = "SELECT * FROM nguoidung nd join sanpham sp on nd.MaNguoiDung = sp.MaNguoiDung where MaSP = '$madon'";
    
        $ketqua = mysqli_query($con, $truyvan);
        $p->DongKetNoi($con);
        return $ketqua;
    }
    public function selectdanhgia($mact) {
        $ngaylap = date("Y-m-d H:i:s");
        $p = new clsketnoi();
        $con = $p->MoKetNoi();
        $truyvan = "SELECT 
    dg.*, 
    nd.TenNguoiDung as TenNguoiMua, 
    sp.TenSP, 
    nd_ban.TenNguoiDung as TenNguoiBan
FROM danhgiasanpham dg
JOIN sanpham sp ON dg.MaSP = sp.MaSP
JOIN nguoidung nd ON dg.MaNguoiDung = nd.MaNguoiDung -- Người mua
JOIN nguoidung nd_ban ON sp.MaNguoiDung = nd_ban.MaNguoiDung -- Người bán
WHERE sp.MaSP = $mact and dg.delete=0 ";
    
        $ketqua = mysqli_query($con, $truyvan);
        $p->DongKetNoi($con);
        return $ketqua;
    }





}
?>
