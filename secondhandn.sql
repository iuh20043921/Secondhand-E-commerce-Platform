-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 09, 2024 at 02:06 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `secondhandn`
--

-- --------------------------------------------------------

--
-- Table structure for table `chitietdonhang`
--

CREATE TABLE `chitietdonhang` (
  `id` int(11) NOT NULL,
  `MaDonHang` int(11) NOT NULL,
  `MaSP` int(11) NOT NULL,
  `SoLuongSP` int(11) NOT NULL,
  `DonGiaSP` double NOT NULL,
  `TongTien` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `chitietdonhang`
--

INSERT INTO `chitietdonhang` (`id`, `MaDonHang`, `MaSP`, `SoLuongSP`, `DonGiaSP`, `TongTien`) VALUES
(1, 1, 2, 1, 65000, 65000),
(2, 2, 3, 1, 50000, 50000),
(7, 7, 5, 1, 530000, 530000),
(8, 8, 4, 1, 88000, 88000),
(9, 9, 6, 1, 200000, 200000),
(11, 11, 11, 1, 150000, 150000),
(12, 12, 12, 2, 100000, 200000),
(14, 13, 4, 1, 88000, 88000),
(15, 13, 7, 1, 250000, 250000),
(16, 14, 10, 1, 25000, 25000),
(17, 15, 26, 1, 40000, 40000),
(18, 16, 9, 1, 30000, 30000),
(19, 16, 10, 1, 25000, 25000),
(20, 17, 27, 1, 200000, 200000),
(21, 18, 7, 1, 250000, 250000),
(22, 18, 31, 1, 54100, 54100),
(23, 18, 1, 1, 80000, 80000),
(24, 19, 5, 1, 530000, 530000),
(25, 20, 35, 1, 147000, 147000);

-- --------------------------------------------------------

--
-- Table structure for table `chitietgiohang`
--

CREATE TABLE `chitietgiohang` (
  `MaChiTietGioHang` int(11) NOT NULL,
  `MaGioHang` int(11) NOT NULL,
  `MaSP` int(11) NOT NULL,
  `SoLuongSP` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `chitietgiohang`
--

INSERT INTO `chitietgiohang` (`MaChiTietGioHang`, `MaGioHang`, `MaSP`, `SoLuongSP`) VALUES
(33, 4, 28, 1),
(38, 4, 34, 1);

-- --------------------------------------------------------

--
-- Table structure for table `danhgiasanpham`
--

CREATE TABLE `danhgiasanpham` (
  `MaDanhGia` int(11) NOT NULL,
  `MaNguoiDung` int(11) NOT NULL,
  `MaSP` int(11) NOT NULL,
  `NoiDungDanhGia` char(255) NOT NULL,
  `DiemDanhGia` int(11) NOT NULL,
  `NgayDanhGia` datetime NOT NULL,
  `HinhDG` varchar(255) NOT NULL,
  `delete` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `danhgiasanpham`
--

INSERT INTO `danhgiasanpham` (`MaDanhGia`, `MaNguoiDung`, `MaSP`, `NoiDungDanhGia`, `DiemDanhGia`, `NgayDanhGia`, `HinhDG`, `delete`) VALUES
(1, 2, 9, 'Sách nhận như hình.', 4, '2024-11-29 17:16:01', '../uploads/reviews/674994615df72_SachChangVang.jpg', 0),
(2, 2, 10, 'Đã nhận được hàng, giống như trông hình ạ.', 5, '2024-11-30 13:46:42', '', 0),
(3, 8, 2, 'Sản phẩm oke. Đã nhận được hàng.', 4, '2024-11-30 13:52:58', '', 0),
(4, 8, 12, 'Mũ nhận còn mới ạ. Cảm ơn sự nhiệt tình của shop ạ', 5, '2024-11-30 13:54:32', '../uploads/reviews/674ab6a89a5ff_muluoichaiAcer.jpg', 0),
(5, 1, 10, 'Đã nhận được hàng. Shop chu đáo ạ.', 4, '2024-11-30 14:04:17', '', 0),
(6, 1, 5, 'Giày đẹp lắm ạ. Rất ưng', 5, '2024-12-05 17:06:38', '../uploads/reviews/67517b2e9b850_giayniketrang.jpg', 0),
(7, 5, 35, 'Quá đẹp luôn ạ.', 5, '2024-12-06 11:52:28', '../uploads/reviews/6752830c8e5fb_giaylolita.png', 0);

-- --------------------------------------------------------

--
-- Table structure for table `donhang`
--

CREATE TABLE `donhang` (
  `MaDonHang` int(11) NOT NULL,
  `MaNguoiBan` int(11) NOT NULL,
  `MaNguoiMua` int(11) NOT NULL,
  `TenDonHang` char(50) NOT NULL,
  `NgayTaoDon` date NOT NULL,
  `DiaChi` char(255) NOT NULL,
  `NgayVanChuyen` date NOT NULL,
  `TrangThai` char(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `donhang`
--

INSERT INTO `donhang` (`MaDonHang`, `MaNguoiBan`, `MaNguoiMua`, `TenDonHang`, `NgayTaoDon`, `DiaChi`, `NgayVanChuyen`, `TrangThai`) VALUES
(1, 4, 8, 'Đơn hàng 20241120080737694', '2024-11-20', 'admin', '0000-00-00', 'Đã giao'),
(2, 4, 8, 'Đơn hàng 20241120082318360', '2024-11-20', 'admin', '0000-00-00', 'Đã hủy đơn'),
(3, 8, 5, 'Đơn hàng 20241120083812908', '2024-11-20', 'admin', '0000-00-00', 'Đã giao'),
(4, 8, 9, 'Đơn hàng 20241120091201596', '2024-11-20', 'test', '0000-00-00', 'Đã giao'),
(5, 9, 8, 'Đơn hàng 20241120091512171', '2024-11-20', 'admin', '0000-00-00', 'Đã giao'),
(6, 9, 8, 'Đơn hàng 20241120100347334', '2024-11-20', 'admin', '0000-00-00', 'Đã giao'),
(7, 4, 8, 'Đơn hàng 20241120125531336', '2024-11-20', 'admin', '0000-00-00', 'Đã hủy đơn'),
(8, 3, 8, 'Đơn hàng 20241120125531303', '2024-11-20', 'admin', '0000-00-00', 'Đã Hủy đơn'),
(9, 3, 8, 'Đơn hàng 20241120125617423', '2024-11-20', 'admin', '0000-00-00', 'Đã Hủy đơn'),
(10, 9, 8, 'Đơn hàng 20241120125617991', '2024-11-20', 'admin', '0000-00-00', 'Đã Hủy đơn'),
(11, 1, 8, 'Đơn hàng 20241120125925374', '2024-11-20', 'admin', '0000-00-00', 'Đã Hủy đơn'),
(12, 1, 8, 'Đơn hàng 20241120125942110', '2024-11-20', 'admin', '0000-00-00', 'Đã giao'),
(13, 9, 8, 'Đơn hàng 20241120130017333', '2024-11-20', 'admin', '0000-00-00', 'Chờ xác nhận'),
(14, 4, 2, 'Đơn hàng 20241122203843858', '2024-11-23', 'Quận 1, HCM', '0000-00-00', 'Đã giao'),
(15, 2, 5, 'Đơn hàng 20241122205133689', '2024-11-23', 'Quận 1, HCM', '0000-00-00', 'Chờ xác nhận'),
(16, 4, 1, 'Đơn hàng 20241124115114159', '2024-11-24', 'Quận 1, HCM', '0000-00-00', 'Đã giao'),
(17, 2, 8, 'Đơn hàng 20241127110445253', '2024-11-27', 'admin', '0000-00-00', 'Chờ xác nhận'),
(18, 1, 8, 'Đơn hàng 20241127114237497', '2024-11-27', 'admin', '0000-00-00', 'Chờ xác nhận'),
(19, 4, 1, 'Đơn hàng 20241205110415113', '2024-12-05', 'Gò Vấp, HCM', '0000-00-00', 'Đã giao'),
(20, 2, 5, 'Đơn hàng 20241206052751488', '2024-12-06', 'Tiền Giang', '0000-00-00', 'Đã giao');

-- --------------------------------------------------------

--
-- Table structure for table `giohang`
--

CREATE TABLE `giohang` (
  `MaGioHang` int(11) NOT NULL,
  `MaNguoiDung` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `giohang`
--

INSERT INTO `giohang` (`MaGioHang`, `MaNguoiDung`) VALUES
(5, 1),
(4, 2),
(6, 5),
(3, 8),
(2, 9);

-- --------------------------------------------------------

--
-- Table structure for table `hopthoaitinnhan`
--

CREATE TABLE `hopthoaitinnhan` (
  `MaHopThoai` int(11) NOT NULL,
  `MaNguoiDung_1` int(11) NOT NULL,
  `MaNguoiDung_2` int(11) NOT NULL,
  `MaTinNhan` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loaisanpham`
--

CREATE TABLE `loaisanpham` (
  `MaLoaiSP` int(11) NOT NULL,
  `TenLoaiSP` char(50) NOT NULL,
  `HinhLoaiSP` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `loaisanpham`
--

INSERT INTO `loaisanpham` (`MaLoaiSP`, `TenLoaiSP`, `HinhLoaiSP`) VALUES
(1, 'Giày', 'Giay.jpg'),
(2, 'Quần', 'quan.jpg'),
(3, 'Phụ kiện', 'phukien.jpg'),
(4, 'Túi xách', 'tuisach.jpg'),
(5, 'Nón', 'non.jpg'),
(6, 'Mỹ phẩm', 'mypham.jpg'),
(7, 'Sách', 'sach.jpg'),
(8, 'Áo', 'Ao.jpg'),
(9, 'Đồ gia dụng', 'dogiadung.jpg'),
(10, 'Đồ điện tử', 'dodientu.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `luusp`
--

CREATE TABLE `luusp` (
  `MaLuu` int(11) NOT NULL,
  `MaNguoiDung` int(11) NOT NULL,
  `MaSP` int(11) NOT NULL,
  `NgayLuu` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `luusp`
--

INSERT INTO `luusp` (`MaLuu`, `MaNguoiDung`, `MaSP`, `NgayLuu`) VALUES
(11, 2, 5, '2024-11-22 19:11:17'),
(15, 8, 27, '2024-11-24 11:00:26'),
(16, 8, 5, '2024-11-24 11:00:46'),
(18, 8, 31, '2024-11-27 10:11:04'),
(19, 2, 34, '2024-11-29 08:20:16'),
(20, 1, 34, '2024-12-05 10:03:25'),
(21, 1, 38, '2024-12-05 10:03:34'),
(23, 8, 45, '2024-12-05 17:13:55'),
(24, 5, 5, '2024-12-06 05:46:02');

-- --------------------------------------------------------

--
-- Table structure for table `nguoidung`
--

CREATE TABLE `nguoidung` (
  `MaNguoiDung` int(11) NOT NULL,
  `TenNguoiDung` varchar(255) NOT NULL,
  `TenDangNhap` char(50) NOT NULL,
  `MatKhau` char(50) NOT NULL,
  `Email` char(50) NOT NULL,
  `SDT` int(11) NOT NULL,
  `DiaChi` char(255) NOT NULL,
  `NgaySinh` date NOT NULL,
  `Role` char(50) NOT NULL DEFAULT '1',
  `LastOnline` datetime DEFAULT current_timestamp(),
  `IsTyping` tinyint(4) DEFAULT 0,
  `TypingWith` int(11) DEFAULT NULL,
  `TypingProduct` int(11) DEFAULT NULL,
  `LastTypingUpdate` datetime DEFAULT NULL,
  `verification_code` varchar(255) DEFAULT NULL,
  `status` tinyint(4) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `nguoidung`
--

INSERT INTO `nguoidung` (`MaNguoiDung`, `TenNguoiDung`, `TenDangNhap`, `MatKhau`, `Email`, `SDT`, `DiaChi`, `NgaySinh`, `Role`, `LastOnline`, `IsTyping`, `TypingWith`, `TypingProduct`, `LastTypingUpdate`, `verification_code`, `status`) VALUES
(1, 'Na De Thuong', 'Nardethuong', 'e10adc3949ba59abbe56e057f20f883e', 'Nar@gmail.com', 123456789, 'Gò Vấp, HCM', '2003-02-26', '1', '2024-11-28 11:36:09', 0, NULL, NULL, NULL, NULL, 1),
(2, 'Tiến Xinh Trai', 'Tienxinhtrai', 'e10adc3949ba59abbe56e057f20f883e', 'Tientien123@gmail.com', 123456789, 'Quận 1, HCM', '2003-01-23', '1', '2024-11-28 11:53:49', 0, NULL, NULL, NULL, NULL, 1),
(3, 'Hiền Xinh Gái', 'Hienxinhgai', 'e10adc3949ba59abbe56e057f20f883e', 'Hiencute@gmail.com', 987654321, 'Quận 2, HCM', '2002-09-24', '1', '2024-11-28 11:54:38', 0, NULL, NULL, NULL, NULL, 1),
(4, 'Lê Minh Tấn', 'LeMinhTan232', 'e10adc3949ba59abbe56e057f20f883e', 'Leminhtan1231@gmail.com', 987987987, 'Quận 4, HCM', '2024-02-24', '1', '2024-11-28 11:36:09', 0, NULL, NULL, NULL, NULL, 1),
(5, 'Ngọc Thúy', 'NgocThuy', 'e10adc3949ba59abbe56e057f20f883e', 'NgocThuy@gmail.com', 814305505, 'Tiền Giang', '2024-10-07', '1', '2024-11-28 11:36:09', 0, NULL, NULL, NULL, NULL, 1),
(6, 'Thái 2', 'Naruma1231', 'e10adc3949ba59abbe56e057f20f883e', 'Naru1@gmail.com', 814305505, 'Cà Mau', '2024-10-11', '1', '2024-11-28 11:36:09', 0, NULL, NULL, NULL, NULL, 1),
(7, 'Thái 3', 'Naruma12311', 'e10adc3949ba59abbe56e057f20f883e', 'Naru12@gmail.com', 814305505, 'Cà Mau', '2024-08-09', '1', '2024-11-28 11:36:09', 0, NULL, NULL, NULL, NULL, 1),
(8, 'Quản Trị Viên', 'admin', 'e10adc3949ba59abbe56e057f20f883e', 'ngocthuyyy0710@gmail.com', 123456789, 'TP HCM\r\n', '2024-10-30', '2', '2024-11-28 11:36:09', 0, NULL, NULL, NULL, NULL, 1),
(9, 'pham duc tuong', 'test', 'e10adc3949ba59abbe56e057f20f883e', 'test@gmail.com', 33432, 'test', '2000-11-22', '1', '2024-11-28 11:36:09', 0, NULL, NULL, NULL, NULL, 1),
(16, 'Thúy Lê', 'NgocThuy@gmail.com', '25f9e794323b453885f5181f1b624d0b', 'thuyyyle56@gmail.com', 521478999, 'Gò Vấp', '2003-07-10', '1', '2024-12-09 10:56:42', 0, NULL, NULL, NULL, 'c4f51388278cd0c0c3c05ea8a4152356', 1);

-- --------------------------------------------------------

--
-- Table structure for table `nguoidung_block`
--

CREATE TABLE `nguoidung_block` (
  `MaNguoiDungChan` int(11) NOT NULL,
  `MaNguoiDungBiChan` int(11) NOT NULL,
  `NgayChan` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sanpham`
--

CREATE TABLE `sanpham` (
  `MaSP` int(11) NOT NULL,
  `TenSP` char(50) NOT NULL,
  `MaLoaiSP` int(11) NOT NULL,
  `DonGia` double NOT NULL,
  `SoLuongTon` int(11) NOT NULL,
  `TinhTrang` varchar(50) NOT NULL,
  `MoTa` text NOT NULL,
  `HinhSP` varchar(255) NOT NULL,
  `MaNguoiDung` int(11) NOT NULL,
  `NgayThemSP` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `isDeleted` tinyint(1) NOT NULL DEFAULT 0,
  `isConfirm` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `sanpham`
--

INSERT INTO `sanpham` (`MaSP`, `TenSP`, `MaLoaiSP`, `DonGia`, `SoLuongTon`, `TinhTrang`, `MoTa`, `HinhSP`, `MaNguoiDung`, `NgayThemSP`, `isDeleted`, `isConfirm`) VALUES
(1, 'Áo giữ nhiệt nam cũ', 8, 80000, 20, '99% mới', 'Size L Xl 2Xl\r\n3 màu trắng , xanh , xám\r\nSale 120k 3 áo 3 màu \r\nLẻ 50k 1 áo', 'aogiunhiet.jpg', 1, '2024-11-27 10:42:37', 0, 1),
(2, 'Áo nỉ xanh rêu', 8, 65000, 8, '95% mới', 'áo free size,80,90kg cũng vừa.màu và hình thức như hình.', 'aonixanhreu.jpg', 4, '2024-11-27 09:56:21', 0, 1),
(3, 'Quần thể thao xanh', 8, 50000, 5, '90% mới', 'quần size L,màu xanh than,vải dày đẹp,cạp chun co dãn ,có dây rút\r\n\r\n', 'Quanthethao.jpg', 4, '2024-11-27 09:56:28', 0, 1),
(4, 'Đầm cánh sen màu trắng', 8, 88000, 0, '99% mới', 'Em có một ít váy vóc, quần áo size S M L. Ko mặc đến nữa em pass lại nhé ạ.', 'damcanhsen.jpg', 3, '2024-11-27 09:56:39', 0, 1),
(5, 'Giày nike trắng', 1, 530000, 6, '98% mới', 'Ni-ke revolution 5\r\nSize 41 fix chân 40\r\nGiày đã qua sử dụng tình trạng giày còn rất đẹp\r\nChạy nhảu thoải maid ae xem ảnh keo chỉ còn nguyên luôn ae ạ quá mới \r\ngiad e bán 650k mà mua mới 2tr5 ạ\r\ncam kết hàng chính hãng✅\r\nnhận hàng kiểm tra hàng ✅\r\nnhận ship cod toàn quốc ✅\r\nshop giày authentic?', 'giayniketrang.jpg', 4, '2024-12-06 05:46:07', 0, 1),
(6, 'Giày cao gót đen', 1, 200000, 5, 'Mới', 'Chị gái mình bị nghiện shoping thừa quá nhiều đa dạng các mẫu và hoàn toàn chưa sử dụng. Size 36_ 37', 'giaycaogot.jpg', 3, '2024-11-29 10:19:38', 0, 1),
(7, 'Giày ananas cũ', 1, 250000, 3, '96% mới', 'giày cũ còn đẹp như ảnh\r\nsize 45 chính hãng ananas Việt Nam\r\ngiày màu trắng hoạ tiết ong với hoa', 'Giayananas.jpg', 1, '2024-11-27 10:42:37', 0, 1),
(8, 'Thanh lý tủ sách cũ', 7, 200000, 7, '80% mới', '- Bán sách tiếng Anh ở mức 30% giá bìa.\r\n\r\n- Em cam kết không bán quyển nào hơn 40% giá bìa, mua nhiều em giảm thêm nhiều!\r\n\r\nHầu hết tất cả các sách em mua từ tiki và fahasa, có nhiều bộ trọn vẹn, được giữ gìn rất tốt, nhiều cuốn còn nguyên màng bọc!', 'Sachcu1.jpg', 2, '2024-11-27 09:57:21', 0, 1),
(9, 'Sách Chạng Vạng', 7, 30000, 8, '85% mới', '? Sách bán 23.10 (3)\r\n?Chạng vạng\r\n?Các vị thần thời hùng vương\r\n?Các vị thần thời Lý\r\n?Bên rặng Tuyết sơn\r\n?Trở về từ xứ tuyết\r\n?Trở về từ cõi sáng\r\n-------------------------------------\r\n?Shop chỉ nhận CK + Không ship Cod.\r\n?Chốt đơn từ 100K trở lên vào ngày 15 và 30 hàng tháng.\r\n?Freeship cho đơn từ 700K.\r\n?Xem và đặt sách tại zalo.', 'SachChangVang.jpg', 2, '2024-11-27 09:57:44', 0, 1),
(10, 'Sách Tình Yêu và Sự Cô Đơn', 7, 25000, 8, '90% mới', '? Sách bán 16.10 (2)\r\n?Tình yêu và sự cô đơn\r\n?Hiển chân phá vọng\r\n?Sáng tạo bừng cháy sức mạnh bên trong\r\n?Băng\r\n?Giang hồ tung cánh\r\n?Tuyển tập tiểu thuyết lịch sử Nguyễn Triệu Luật.\r\n-------------------------------------\r\n?Shop chỉ nhận CK + Không ship Cod.\r\n?Chốt đơn từ 100K trở lên vào ngày 15 và 30 hàng tháng.\r\n?Freeship cho đơn từ 700K.', 'SachTinhYeuVaSuCoDon.jpg', 4, '2024-11-27 09:57:52', 0, 1),
(11, 'Mũ lưỡi chai xanh unisex', 5, 150000, 7, '99% mới', 'Mũ lưỡi chai unisex Un.derAour cao cấp xuất khẩu :\r\nSize M/L và L/XL dành cho Anh Em. Size M/L tương đương với Chu Vi vòng đầu 56 đến 58cm – L/XL (59-62cm)\r\n *** Thông tin cửa hàng NamFashion\r\n 63 Ngõ 105 Bạch Mai - Hai Bà Trưng - Hà Nội (đi hết ngõ to rẽ phải 30m thấy biển NamFashion hoặc hỏi ngách 66 ngõ Đình Đông) \r\nMở cửa: 9 - 21h (hàng ngày)', 'muluoichaixanh.jpg', 1, '2024-11-27 09:57:58', 0, 1),
(12, 'Mũ lưỡi chai Acer Predator', 5, 100000, 7, '99% mới', 'Mới tinh\r\n50k / 1 chiếc\r\nGiao dịch trực tiếp tại nhà', 'muluoichaiAcer.jpg', 1, '2024-11-27 09:58:06', 0, 1),
(26, 'Sách Đắc Nhân Tâm', 7, 40000, 14, '95% mới', 'sach dac nhan tam phien ban 2024', 'R.jpg', 2, '2024-11-27 09:58:23', 0, 1),
(27, 'Sữa rửa mặt Nevia', 6, 200000, 4, '95% mới', 'Sữa rửa mặt Nevia', 'nevia.jpg', 2, '2024-11-29 10:19:55', 0, 1),
(28, 'Túi xách LV', 4, 9999999, 4, 'Like new 95%', 'Túi xách LV ', 'tuiLV.jpg', 8, '2024-11-24 10:55:11', 0, 1),
(29, 'Son môi Hàn Quốc CLIO', 6, 599000, 10, ' 99% mới', 'Son môi Hàn Quốc CLIO', 'sonmoiCLIO.jpg', 8, '2024-11-27 09:57:36', 0, 1),
(30, 'Áo MongToghi', 8, 88000, 5, '99% mới', 'SHOP  Tri Ân Khách Hàng Lẻ Có Quà Tặng , Sale Rẻ Đồng Giá Bán Lẻ Đầy Đủ Các Mặt Hàng Quần Áo Nam Nữ Trẻ Em 10k - 20k - 30k – 50k – 80k,Nhanh Chân Tới Lựa Khách Ơi\r\nƯu Đãi Lớn :\r\nMua 400k Tặng 1 Áo Thun\r\nMua 600k Tặng 1 Ấm Đun Siêu Tốc\r\nMua 1 Triệu 200 Tặng 1 Ấm Đun Siêu Tốc\r\n', 'aomi.png', 8, '2024-11-27 10:23:50', 0, 1),
(31, 'Quần nữ Fasafas phiên bản Hàn Quốc', 2, 54100, 8, '99% mới', '\r\nChất liệu: Bông + sợi polyeste + khác\r\n\r\n“Chỉ để tham khảo, không có tiêu chuẩn thực tế ”\r\n\r\n     Do màn hình hiển thị và hiệu ứng ánh sáng khác nhau, màu sắc thực tế của mặt hàng có thể hơi khác so với màu hiển thị trong hình ảnh. Cảm ơn bạn.\r\n\r\n     Nếu sản phẩm của chúng tôi không có kích thước hoặc màu sắc yêu thích của bạn, hoặc bạn muốn tìm hiểu thêm thông tin, vui lòng liên hệ với chúng tôi. Cảm ơn bạn.\r\n\r\n    ', 'quancaro.png', 6, '2024-12-02 14:45:47', 0, 1),
(32, 'Quần jeans nữ ống loe co giãn Grind Wash', 2, 84000, 50, 'Mới', 'kiểu dáng : quần ống loe\r\nxuất xứ : hàng việt nam\r\nchất liệu .vải bò denim\r\nhọa tiết : trơn\r\nmàu sắc : xám khói / xanh đậm / xanh nhạt/ Đen/ retro\r\nchi tiết : chất liệu co giãn tốt, ống loe nhẹ tạo dáng vẻ thời trang\r\nchiều dài quần : 94-96 cm\r\ncạp cạo qua rốn 9cm\r\nLưu ý : Thông số shop đưa ra chỉ mang tính tương đổi không cam kết tuyệt đối 100%\r\nloại trang phục thanh lịch ,công sở phù hợp cho học sinh sinh viên hoặc dân văn phòng mặc đi học đi chơi đi làm kín đáo lịch sự ', 'quanjeannu.png', 7, '2024-11-27 10:24:09', 0, 1),
(33, 'Quần Short Sờ tu sy Unisex Nam', 2, 75000, 60, 'Mới', 'Quần Short Sờ tu sy unisex nam nữ chất cotton cao cấp, phong cách basic, thể thao, mặc thoáng mát\r\n\r\n    ✪ Chất Liệu Vải :  cotton cao cấp 100%, vải mềm, mịn, thoáng mát, không xù lông.\r\n\r\n    ✪ Kiểu Dáng :Form Rộng Thoải Mái\r\n\r\n    ✪ Full size nam nữ : 40 - 85 kg', 'quanngan.png', 1, '2024-11-27 10:24:18', 0, 1),
(34, 'Dép bông sang trọng kiểu mới mùa thu đông', 1, 29000, 44, '95% mới', 'Chiều cao gót: 1,5-3cm\r\nPhong cách: Baotou\r\nMùa năm niêm yết (Thời gian đưa ra thị trường): Mùa thu 2024\r\nChất liệu bên trong: Tóc nhân tạo\r\nMàu sắc: Trắng [Mẫu nâng cấp] Ấm-Chất lượng cao không dễ nứt + 1, Kaki [Mẫu nâng cấp] Ấm-Chất lượng cao không dễ nứt + 1, Briquettes nhỏ màu đen và trắng [Mẫu Pin] - Màu cam rơi tất cả Trên mặt đất, màu trắng [Hoàng hôn cổ tích] - Hoa tình bướm + 1, Kaki [Hoàng hôn cổ tích] - Hoa tình yêu bướm + 1, Ong nhỏ sọc [Mẫu ghim] - Màu cam rơi trên mặt đất', 'depxinh.png', 3, '2024-11-30 06:45:50', 0, 1),
(35, 'Giày Oxford Lolita - Giày Búp Bê Nữ Đính Nơ Dễ Thư', 1, 147000, 11, 'Mới', 'Màu sắc: Đen Bóng, Trắng Kem\r\n- Chấtt liệu: Da Bóng, Đế Pu Cao Cấp\r\n- Mẫu Fom Nhỏ Tăng Size\r\n- Phong Cách : Thời Trang\r\n- Xu Hướng : Mới\r\n- kiểu dáng thời trang\r\n- phù hợp với mọi lứa tuổi \r\n- Kích thước: 35,36,37,38,39\r\n- Phù hợp đi học, đi làm, đi chơi, du lịch.\r\n- Kiểu dáng thời trang, dễ phối đồ. \r\n- Đến mùa du lịch rồi, mọi người nhanh tay rinh em nó về thôi, để phối với váy nè, quần đùi, quần sóc, chân váy, thích hợp với bạn có thể muốn đi du lịch đi chơi, mà ngại đeo giày thể thao, đi làm, đi học, cực kỳ tiện dụng.', 'giaylolita.png', 2, '2024-12-06 04:27:51', 0, 1),
(36, 'Nơ Tóc Ruy Băng Mini Ngọt Ngào Dễ Thương Ruy Băng', 3, 19000, 100, 'Mới', 'Chất liệu: Vải\r\n\r\n Danh mục kẹp tóc: Kẹp mỏ vịt\r\n\r\n Yếu tố phổ biến: Cung\r\n\r\n Thích hợp cho: Phụ nữ\r\n\r\n Kích thước: xấp xỉ. Dài 4cm', 'no.png', 2, '2024-11-27 10:24:43', 0, 1),
(37, 'Vòng cổ bạc MAYEBE LAVEND Punk', 3, 12000, 30, '98% mới', 'Thương hiệu: Mayebe Lavendar Jewelry \r\n\r\nQuy trình xử lý: Mạ điện\r\n\r\nLoại: Vòng cổ\r\n\r\nMàu bạc\r\n\r\nChất liệu: Hợp kim\r\n\r\nPhong cách: Unisex\r\n\r\nPhong cách: hip-hop, punk\r\n\r\nDanh sách sản phẩm: 1 ” Vòng cổ', 'daychuyen.png', 4, '2024-11-27 10:25:11', 0, 1),
(38, 'Ren Scrunchy Tóc Cô Gái Mùa Hè', 3, 27000, 50, 'Mới', '★Tên: Vòng tóc\r\n★Vật chất: Vật liệu hỗn hợp\r\n★Kích thước: Một kích thước\r\n★Phong cách: Ngọt ngào\r\n★Tình trạng sản phẩm: 100% thương hiệu mới \r\n★Thời gian đưa ra thị trường: 2024\r\n?Có Một Số Lượng Lỗi Nhỏ Trong Đo Thủ Công, Vui Lòng Hiểu!\r\n', 'buoctoc.png', 3, '2024-11-27 10:25:23', 0, 1),
(39, 'Tua Rua Phong Cách Cổ Xưa Kẹp Tóc', 3, 24000, 34, '98% mới', 'Thương hiệu: NoBrand					\r\n\r\nChất liệu: Khác						\r\n\r\nChất lượng: 100% thương hiệu mới và chất lượng cao						\r\n\r\nPhong cách: Kẹp tóc	\r\n\r\nKích thước: aspictureshow						\r\n\r\nMạ điện: 3 lần chọn lọc, xử lý bề mặt mịn, neasytofade						\r\n\r\nUsescenario: hàng ngày, tiệc tùng, đám cưới, sinh nhật, đính hôn, v.v.						\r\n\r\nYoucanvisitorstoreandfollowus!Thêm sản phẩm, nhiều phân phối hơn!', 'tram.png', 2, '2024-11-27 10:25:42', 0, 1),
(40, 'Túi xách nữ da PU cao cấp', 4, 99000, 5, '95% mới', '✦ Style Hàn Quốc\r\n✦ Thiết kế hiện đại trẻ trung, hợp xu hướng\r\n✦ Chất liệu: Da PU\r\n\r\nMình mang túi được 2 lần đi chụp hình. Muốn đổi kiểu nên xin pass ạ.', 'tuino.png', 2, '2024-11-27 10:25:32', 0, 1),
(41, 'Túi xách nữ công sở bản to', 4, 159000, 2, '98% mới', '✪ Thiết kế trẻ trung, cá tính, năng động, hợp xu hướng thời trang mới nhất.\r\n✪ Gam màu tinh tế rất dễ mix đồ.\r\n✪ Chất liệu da cao cấp, bền đẹp. Đường may tỉ mỉ, chắc chắn.\r\n✪ Kiểu dáng độc đáo, tạo cảm giác sang chảnh khi mang theo, cuốn hút người đối diện.\r\n✪ Ngăn lớn đựng đồ tiện dụng và linh hoạt.\r\n✪ Giá cả hợp lí, phù hợp với mọi đối tượng.\r\n\r\nMình mua dùng được 1 lần đi tiệc. Muốn đổi kiểu nên xin pass ạ', 'tuito.png', 4, '2024-11-27 10:25:51', 0, 1),
(42, 'Túi xách nữ dáng cốp', 4, 93000, 10, '99% mới', '- Kích thước: 18 x 15 x7cm\r\n\r\n- Mẫu túi trẻ trung, cá tính\r\n\r\n- Phù hợp mọi lứa tuổi, đi chơi, đi làm, đi dự tiệc, đi họp, đi shopping, du lịch,..\r\n\r\n- Chất liệu: Da PU cao cấp, mềm, bóng, không nhăn, dễ lau chùi và bảo quản \r\nShop e xả kho về hàng mới nên muốn bán giá rẻ ạ. Ai có nhu cầu xin liên hệ.', 'tuihop.png', 2, '2024-11-27 10:26:01', 0, 1),
(43, 'Túi xách đeo vai nữ dáng vuông', 4, 89000, 27, '98% mới', 'TUI XINHH\r\nTUI XINH\r\nTUIS XINH QUA !!!\r\nQuá Xinh ạ\r\nTúi xách đeo vai nữ dáng vuông túi nữ đẹp đi chơi đi tiệc kiểu dáng basic, hot nhất hiện nay Túi Xinh LaiKa TN18\r\n\r\nMô Tả Sản Phẩm:\r\n♥️ Màu sắc: Da, Nâu, Đen\r\n Kích Thước: 25cm x 22cm x 7cm\r\n♥️ Phong Cách nữ tính, trẻ trung chuẩn Hàn Quốc.\r\n♥️ Chất liệu cao cấp chống thấm nước tốt. ', 'tuivuong.png', 3, '2024-11-27 10:26:11', 0, 1),
(44, 'Range ~ Son Bóng Tinh Chất Băng Trong Suốt', 6, 79000, 20, '99% mới', 'Em có vài cây son hàng Quảng Đông, giá rẻ,muốn pass cho chị em nào cần ạ.\r\nThời hạn sử dụng: 3 năm\r\nMàu sắc: 01#Bingtou Peach 02#Bingtou Sunset 03#Bingtou Sydney 04#Bingtou Tomato 05#Bingtou Cherry 06#Bingtou Bean Paste', 'sonbong.png', 1, '2024-11-27 10:26:24', 0, 1),
(45, 'Son Kem Black Rouge Double Layer Jewelry Over Velv', 6, 111000, 10, '98% mới', 'Lớp son bóng nhẹ nhàng trên nền son nhung chính là điểm đặc biệt của dòng Black Rouge Double Layer Jewelry Over Velvet, trở lại đường đua với 7 màu son hầu như phù hợp mọi làn da và cân được các makeup look từ daily đến làm tâm điểm của các buổi tiệc tối.\r\nSon Kem Black Rouge Double Layer Jewelry Over Velvet với thiết kế trong suốt với tông xanh ngọc lục bảo với logo được khắc laser chìm, phủ màu champagne nhất định sẽ khiến các nàng xiêu lòng từ cái nhìn đầu tiên.\r\nSon Kem Black Rouge Double Layer Jewelry Over Velvet với bảng màu:\r\nDL15 - Màu đỏ cam hổ phách\r\nDL16 - Màu cánh hồng khô MLBB\r\nDL17 - Màu đỏ thẩm pha ánh hồng\r\nDL18 - Màu đỏ chili cực chill\r\nDL19 - Màu đỏ nâu khói\r\nDL20 - Màu tím mận\r\nDL21 - Màu nâu gạch \r\n', 'sonbr.png', 4, '2024-11-27 10:26:42', 0, 1),
(46, 'Ershiqi 16 Màu Phấn Mắt Ngọc Trai Mờ Sequins Flash', 6, 27000, 15, '99% mới', 'Em còn vài bảng mẫu phấn mắt, giá rẻ. Ai quan tâm xin ib ạ.\r\nLoại bao bì\r\nBảng màu\r\nXuất xứ\r\nTrung Quốc\r\nCông Thức\r\nMỏng nhẹ\r\nLớp nền hoàn thành\r\nNhũ\r\nTrọng lượng\r\n10g\r\nHạn sử dụng\r\n36 tháng\r\nGửi từ\r\nNước ngoài', 'bangmat.png', 1, '2024-11-27 10:26:34', 0, 1),
(47, 'Kiểm Soát Dầu Mặt Bột Bấm Bột Kem Che Khuyết Điểm', 6, 33000, 30, '98% mới', 'Bột NỀN TẢNG ÉP: No.1 phù hợp với làn da White Natural và No.2 là Natural Pink có thể được sử dụng với mọi màu da khuôn mặt xinh đẹp của bạn.\r\n\r\nBột KIỂM SOÁT DẦU MẶT: Phấn nền ép mặt chất lượng lâu trôi 8 giờ. Mang lại làn da mịn màng và mờ ảo tự nhiên trông không tì vết. Da mặt không lỗ chân lông, Cung cấp độ che phủ trung bình. Hiệp ước kiểm soát bã nhờn cắt dầu cho da nhờn\r\n', 'phanphu.png', 2, '2024-07-06 01:27:09', 1, 1),
(48, 'Xịt Khóa makeup Nền DAZZLE ME', 6, 79000, 8, '95% mới', 'B1: dùng sản phẩm sau khi dưỡng da và trước khi đánh nền\r\n\r\nB2: Sao khi apply lớp nền, che khuyết điểm, má hồng, tiếp tục xịt, đợi 10-15s cho lớp nền khô hẳn\r\n\r\nB3: Apply phấn phủ lên, sau khi hoàn thành makeup, tiếp tục lớp cuối cùng\r\n\r\nXịt cách mặt tầm 40cm, xịt theo dạng phun sương, không xịt để nước thành giọt đọng trên nền\r\n\r\n*lưu ý sản phẩm không dùng để khóa son\r\n\r\n', 'xitkhoa.png', 4, '2024-11-27 10:26:51', 0, 1),
(49, 'Nước tẩy trang L\'Oreal Paris 3-in-1 Micellar Water', 6, 179000, 30, '98% mới', 'Nước tẩy trang tươi mát 3 in 1 L\'Oreal Paris Micellar Water giúp tẩy trang, làm sạch, giữ ẩm và dưỡng mềm da đồng thời chỉ trong một sản phẩm. Thành phần an toàn và công nghệ tiên tiến giúp da giữ nước, thông thoáng, mềm mượt chỉ trong 1 bước.\r\n\r\n', 'taytrang.png', 3, '2024-11-27 10:27:01', 0, 1),
(50, 'Tủ gỗ', 9, 3000000, 2, '85% mới', 'Hàng viêt nam chât liêu bàng gô thao lao xüa ngan 1 m rông 43 cm cao 1.7 m. Ai có nhu câu sü dung mình bán laį à', 'tugo.png', 1, '2024-11-27 10:27:13', 0, 1),
(51, 'Cây nước nóng lạnh Karofi HC18', 9, 1800000, 13, '89% mới', 'Mua nhưng ít dùng, mua mới hơn 6tr.  Để lại cho ace nào có nhu cầu cần sử dụng. 3 vòi riêng biệt, thiết kế giấu bình ở dưới', 'tuhut.png', 2, '2024-11-27 10:27:24', 0, 1),
(52, 'BÀN LÀM VIỆC KÈM HỘC TỦ + KỆ SÁCH ĐA NĂNG', 9, 1550000, 10, '95% mới', 'CHẤT LIỆU:\r\nLàm từ gỗ MDF phủ melamine chống nước, chống trầy xước\r\nKhung Xương Kệ làm bằng sắt 20x40 sơn tĩnh điện chống trầy xước\r\nHộc Tủ Ray Bi Trượt Êm Ái\r\nKích Thước: 1m2x60 ( chiều cao mặt bàn 75cm) Tổng Chiều Cao Kèm Kệ 155cm\r\nGIÁ 1550K\r\nCHÍNH SÁCH BÁN HÀNG:\r\nGIAO HÀNG TẬN NƠI( GIAO HỎA TỐC TRONG VÒNG 1 GIỜ)\r\nGIAO HÀNG CÓ THỢ TỚI LẮP RÁP, KHUÂN VÁC LÊN TẬN NƠI LẮP ĐẶT CHO KHÁCH\r\nNHẬN HÀNG KIỂM TRA HÀNG RỒI MỚI THANH TOÁN\r\nĐƯỢC TEST HÀNG THOẢI MÁI', 'tuhoc.png', 1, '2024-11-27 10:27:34', 0, 1),
(53, 'Máy giặt LG Inverter giặt êm tiết kiệm điện', 10, 3000000, 3, '80% mới', 'Lò nướng\r\nMình có máy giặt lồng ngang hiệu lg inverter, máy giặt êm tiết kiệm điện giặt quần áo sạch sẽ và tủ mát hiệu Heniken dung tích 400 lít', 'maygiat.png', 8, '2024-11-27 10:27:43', 0, 1),
(54, 'Máy lạnh Sharp inverter trắng 1HP mới 95%', 10, 5000000, 7, '82% mới', 'Máy lạnh sharp inverter 1HP đã qua sử dụng, mới 95%. Sử dụng rất ít và máy nhìn còn mới như mới mua về. Tuổi thọ sử dụng chỉ vài tháng.', 'maylanh.png', 8, '2024-11-27 10:25:02', 0, 1),
(55, 'Thanh lý máy lạnh toshiba mono 1.5 hp', 10, 3500000, 6, '89% mới', 'Máy toshiba 1.5 hp mono, miễn phí vận chuyển, miễn phí lắp đặt, bao 2m ống đồng, ke đỡ máy, đây điện, đây xã, CB, bảo hành 6 tháng', 'cucmay.png', 5, '2024-11-27 10:24:52', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `thongbao`
--

CREATE TABLE `thongbao` (
  `MaThongBao` int(11) NOT NULL,
  `MaNguoiDung` int(11) DEFAULT NULL,
  `LoaiThongBao` varchar(50) DEFAULT NULL,
  `NoiDung` text DEFAULT NULL,
  `TrangThai` tinyint(4) DEFAULT 0,
  `NgayTao` datetime DEFAULT NULL,
  `MaThamChieu` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tinnhan`
--

CREATE TABLE `tinnhan` (
  `MaTinNhan` int(11) NOT NULL,
  `MaNguoiGui` int(11) NOT NULL,
  `MaNguoiNhan` int(11) NOT NULL,
  `NoiDung` char(255) NOT NULL,
  `NgayNhanTin` datetime NOT NULL,
  `MaSP` int(255) NOT NULL,
  `DaDoc` tinyint(1) NOT NULL,
  `SoTinNhanChuaDoc` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tinnhan`
--

INSERT INTO `tinnhan` (`MaTinNhan`, `MaNguoiGui`, `MaNguoiNhan`, `NoiDung`, `NgayNhanTin`, `MaSP`, `DaDoc`, `SoTinNhanChuaDoc`) VALUES
(43, 2, 1, 'ád', '2024-11-28 04:10:55', 7, 1, 0),
(44, 2, 1, 'hi', '2024-11-28 04:11:20', 7, 1, 0),
(45, 2, 1, 'hoho', '2024-11-28 04:13:10', 7, 1, 0),
(46, 2, 3, 'hi', '2024-11-28 11:36:23', 6, 1, 0),
(47, 2, 3, 'hi', '2024-11-28 11:36:24', 6, 1, 0),
(48, 2, 3, 'hi', '2024-11-28 11:36:25', 6, 1, 0),
(49, 3, 2, 'chào', '2024-11-28 11:54:38', 6, 1, 0),
(50, 2, 3, 's', '2024-11-29 23:42:03', 6, 1, 0),
(51, 2, 1, 'hi', '2024-11-29 23:42:08', 7, 1, 0),
(52, 3, 2, 'hi', '2024-11-29 23:43:08', 6, 1, 0),
(53, 3, 2, 'ban muon mua gi', '2024-11-29 23:43:13', 6, 1, 0),
(54, 2, 3, 'toi muon mua hang', '2024-11-29 23:43:37', 6, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tinnhan_baocao`
--

CREATE TABLE `tinnhan_baocao` (
  `MaBaoCao` int(11) NOT NULL,
  `MaTinNhan` int(11) DEFAULT NULL,
  `MaNguoiDungBaoCao` int(11) DEFAULT NULL,
  `LyDo` text DEFAULT NULL,
  `NgayBaoCao` datetime DEFAULT NULL,
  `TrangThai` tinyint(4) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trangthai`
--

CREATE TABLE `trangthai` (
  `MaTrangThai` int(11) NOT NULL,
  `TenTrangThai` char(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trangthai`
--

INSERT INTO `trangthai` (`MaTrangThai`, `TenTrangThai`) VALUES
(1, 'Chờ Giao Hàng'),
(3, 'Chờ Nhận Hàng'),
(4, 'Đã nhận'),
(2, 'Đang Giao');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chitietdonhang`
--
ALTER TABLE `chitietdonhang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `MaDonHang` (`MaDonHang`),
  ADD KEY `MaSP` (`MaSP`);

--
-- Indexes for table `chitietgiohang`
--
ALTER TABLE `chitietgiohang`
  ADD PRIMARY KEY (`MaChiTietGioHang`),
  ADD KEY `MaGioHang` (`MaGioHang`),
  ADD KEY `MaSP` (`MaSP`);

--
-- Indexes for table `danhgiasanpham`
--
ALTER TABLE `danhgiasanpham`
  ADD PRIMARY KEY (`MaDanhGia`),
  ADD KEY `MaSP` (`MaSP`),
  ADD KEY `danhgiasanpham_ibfk_1` (`MaNguoiDung`);

--
-- Indexes for table `donhang`
--
ALTER TABLE `donhang`
  ADD PRIMARY KEY (`MaDonHang`),
  ADD KEY `MaNguoiBan` (`MaNguoiBan`),
  ADD KEY `MaNguoiMua` (`MaNguoiMua`),
  ADD KEY `TrangThai` (`TrangThai`);

--
-- Indexes for table `giohang`
--
ALTER TABLE `giohang`
  ADD PRIMARY KEY (`MaGioHang`),
  ADD KEY `MaNguoiDung` (`MaNguoiDung`);

--
-- Indexes for table `hopthoaitinnhan`
--
ALTER TABLE `hopthoaitinnhan`
  ADD PRIMARY KEY (`MaHopThoai`),
  ADD KEY `MaNguoiDung_1` (`MaNguoiDung_1`),
  ADD KEY `MaNguoiDung_2` (`MaNguoiDung_2`),
  ADD KEY `MaTinNhan` (`MaTinNhan`);

--
-- Indexes for table `loaisanpham`
--
ALTER TABLE `loaisanpham`
  ADD PRIMARY KEY (`MaLoaiSP`);

--
-- Indexes for table `luusp`
--
ALTER TABLE `luusp`
  ADD PRIMARY KEY (`MaLuu`),
  ADD UNIQUE KEY `unique_favorite` (`MaNguoiDung`,`MaSP`),
  ADD KEY `MaSP` (`MaSP`);

--
-- Indexes for table `nguoidung`
--
ALTER TABLE `nguoidung`
  ADD PRIMARY KEY (`MaNguoiDung`),
  ADD KEY `Role` (`Role`);

--
-- Indexes for table `nguoidung_block`
--
ALTER TABLE `nguoidung_block`
  ADD PRIMARY KEY (`MaNguoiDungChan`,`MaNguoiDungBiChan`);

--
-- Indexes for table `sanpham`
--
ALTER TABLE `sanpham`
  ADD PRIMARY KEY (`MaSP`),
  ADD KEY `MaLoaiSP` (`MaLoaiSP`),
  ADD KEY `MaNguoiDung` (`MaNguoiDung`);

--
-- Indexes for table `thongbao`
--
ALTER TABLE `thongbao`
  ADD PRIMARY KEY (`MaThongBao`);

--
-- Indexes for table `tinnhan`
--
ALTER TABLE `tinnhan`
  ADD PRIMARY KEY (`MaTinNhan`),
  ADD KEY `MaNguoiGui` (`MaNguoiGui`),
  ADD KEY `MaNguoiNhan` (`MaNguoiNhan`),
  ADD KEY `MaSP` (`MaSP`);

--
-- Indexes for table `tinnhan_baocao`
--
ALTER TABLE `tinnhan_baocao`
  ADD PRIMARY KEY (`MaBaoCao`);

--
-- Indexes for table `trangthai`
--
ALTER TABLE `trangthai`
  ADD PRIMARY KEY (`MaTrangThai`),
  ADD KEY `TenTrangThai` (`TenTrangThai`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chitietdonhang`
--
ALTER TABLE `chitietdonhang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `chitietgiohang`
--
ALTER TABLE `chitietgiohang`
  MODIFY `MaChiTietGioHang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `danhgiasanpham`
--
ALTER TABLE `danhgiasanpham`
  MODIFY `MaDanhGia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `donhang`
--
ALTER TABLE `donhang`
  MODIFY `MaDonHang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `giohang`
--
ALTER TABLE `giohang`
  MODIFY `MaGioHang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `hopthoaitinnhan`
--
ALTER TABLE `hopthoaitinnhan`
  MODIFY `MaHopThoai` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `loaisanpham`
--
ALTER TABLE `loaisanpham`
  MODIFY `MaLoaiSP` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `luusp`
--
ALTER TABLE `luusp`
  MODIFY `MaLuu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `nguoidung`
--
ALTER TABLE `nguoidung`
  MODIFY `MaNguoiDung` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `sanpham`
--
ALTER TABLE `sanpham`
  MODIFY `MaSP` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `thongbao`
--
ALTER TABLE `thongbao`
  MODIFY `MaThongBao` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tinnhan`
--
ALTER TABLE `tinnhan`
  MODIFY `MaTinNhan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `tinnhan_baocao`
--
ALTER TABLE `tinnhan_baocao`
  MODIFY `MaBaoCao` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trangthai`
--
ALTER TABLE `trangthai`
  MODIFY `MaTrangThai` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chitietdonhang`
--
ALTER TABLE `chitietdonhang`
  ADD CONSTRAINT `chitietdonhang_ibfk_1` FOREIGN KEY (`MaDonHang`) REFERENCES `donhang` (`MaDonHang`),
  ADD CONSTRAINT `chitietdonhang_ibfk_2` FOREIGN KEY (`MaSP`) REFERENCES `sanpham` (`MaSP`);

--
-- Constraints for table `chitietgiohang`
--
ALTER TABLE `chitietgiohang`
  ADD CONSTRAINT `chitietgiohang_ibfk_1` FOREIGN KEY (`MaGioHang`) REFERENCES `giohang` (`MaGioHang`),
  ADD CONSTRAINT `chitietgiohang_ibfk_2` FOREIGN KEY (`MaSP`) REFERENCES `sanpham` (`MaSP`);

--
-- Constraints for table `danhgiasanpham`
--
ALTER TABLE `danhgiasanpham`
  ADD CONSTRAINT `danhgiasanpham_ibfk_1` FOREIGN KEY (`MaNguoiDung`) REFERENCES `nguoidung` (`MaNguoiDung`),
  ADD CONSTRAINT `danhgiasanpham_ibfk_2` FOREIGN KEY (`MaSP`) REFERENCES `sanpham` (`MaSP`);

--
-- Constraints for table `donhang`
--
ALTER TABLE `donhang`
  ADD CONSTRAINT `donhang_ibfk_1` FOREIGN KEY (`MaNguoiBan`) REFERENCES `nguoidung` (`MaNguoiDung`),
  ADD CONSTRAINT `donhang_ibfk_2` FOREIGN KEY (`MaNguoiMua`) REFERENCES `nguoidung` (`MaNguoiDung`);

--
-- Constraints for table `giohang`
--
ALTER TABLE `giohang`
  ADD CONSTRAINT `giohang_ibfk_1` FOREIGN KEY (`MaNguoiDung`) REFERENCES `nguoidung` (`MaNguoiDung`);

--
-- Constraints for table `hopthoaitinnhan`
--
ALTER TABLE `hopthoaitinnhan`
  ADD CONSTRAINT `hopthoaitinnhan_ibfk_1` FOREIGN KEY (`MaNguoiDung_1`) REFERENCES `nguoidung` (`MaNguoiDung`),
  ADD CONSTRAINT `hopthoaitinnhan_ibfk_2` FOREIGN KEY (`MaNguoiDung_2`) REFERENCES `nguoidung` (`MaNguoiDung`),
  ADD CONSTRAINT `hopthoaitinnhan_ibfk_3` FOREIGN KEY (`MaTinNhan`) REFERENCES `tinnhan` (`MaTinNhan`);

--
-- Constraints for table `luusp`
--
ALTER TABLE `luusp`
  ADD CONSTRAINT `luusp_ibfk_1` FOREIGN KEY (`MaNguoiDung`) REFERENCES `nguoidung` (`MaNguoiDung`),
  ADD CONSTRAINT `luusp_ibfk_2` FOREIGN KEY (`MaSP`) REFERENCES `sanpham` (`MaSP`);

--
-- Constraints for table `sanpham`
--
ALTER TABLE `sanpham`
  ADD CONSTRAINT `sanpham_ibfk_1` FOREIGN KEY (`MaLoaiSP`) REFERENCES `loaisanpham` (`MaLoaiSP`),
  ADD CONSTRAINT `sanpham_ibfk_2` FOREIGN KEY (`MaNguoiDung`) REFERENCES `nguoidung` (`MaNguoiDung`);

--
-- Constraints for table `tinnhan`
--
ALTER TABLE `tinnhan`
  ADD CONSTRAINT `MaSSP` FOREIGN KEY (`MaSP`) REFERENCES `sanpham` (`MaSP`),
  ADD CONSTRAINT `tinnhan_ibfk_1` FOREIGN KEY (`MaNguoiGui`) REFERENCES `nguoidung` (`MaNguoiDung`),
  ADD CONSTRAINT `tinnhan_ibfk_2` FOREIGN KEY (`MaNguoiNhan`) REFERENCES `nguoidung` (`MaNguoiDung`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
