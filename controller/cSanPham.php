<?php
include_once("../model/mSanpham.php");

class controlSanpham {
    public function getAllSanpham() {
        $p = new msanpham();
        $ketqua = $p->selectAllSanpham();
        if (mysqli_num_rows($ketqua) > 0) {
            return $ketqua;
        } else {
            return false; // No products found
        }
    }

   

    public function getChiTietSanPham($mact) {
        $p = new msanpham();
        $kq = $p->selectChiTietSanpham($mact);
        if (mysqli_num_rows($kq) > 0) {
            return $kq;
        } else {
            return false; // No products found in this category
        }
    }

    public function getAllSanPhamByTH($th) {
        $p = new msanpham();
        $kq = $p->selectAllSanPhamByTH($th);
        if (mysqli_num_rows($kq) > 0) {
            return $kq;
        } else {
            return false; // No products found in this category
        }
    }

    public function getAllSanphamByName($ten) {
        $p = new msanpham();
        $ketqua = $p->selectAllSanphamByName($ten);
        if (mysqli_num_rows($ketqua) > 0) {
            return $ketqua;
        } else {
            return false; // No products found with this name
        }
    }

    public function getAllSanphamByTinhTrang($tinhTrang) {
        $p = new msanpham();
        $ketqua = $p->selectAllSanphamByTinhTrang($tinhTrang); // Adding category filter if needed
        if (mysqli_num_rows($ketqua) > 0) {
            return $ketqua;
        } else {
            return false; // No products found with this condition
        }
    }

    public function getSanPhamByPriceRange($min, $max) {
        $p = new msanpham();
        $kq = $p->selectSanPhamByPriceRange($min, $max); // Adding category filter if needed
        return $kq;
    }

    public function getAllSanPhamBytin() {
        $p = new msanpham();
        $kq = $p->selecAllSanPhamtheotin(); // Adding category filter if needed
        if (mysqli_num_rows($kq) > 0) {
            return $kq;
        } else {
            return false; // No products found
        }
    }

    public function getAllSanPhamBygiathap() {
        $p = new msanpham();
        $kq = $p->selecAllSanPhamtheogiathap(); // Adding category filter if needed
        if (mysqli_num_rows($kq) > 0) {
            return $kq;
        } else {
            return false; // No products found
        }
    }

    public function getAllSanPhamBygiacao() {
        $p = new msanpham();
        $kq = $p->selecAllSanPhamtheogiacao(); // Adding category filter if needed
        if (mysqli_num_rows($kq) > 0) {
            return $kq;
        } else {
            return false; // No products found
        }
    }

    public function getFilteredProducts($ml,$min, $max, $sort, $txtTimKiem) {
        $p = new msanpham();
        $kq = $p->getFilteredProducts($ml,$min, $max, $sort, $txtTimKiem); // Adding category filter if needed
        if (mysqli_num_rows($kq) > 0) {
            return $kq;
        } else {
            return false; // No products found
        }
    }

    public function getcthd($madon) {
        $p = new msanpham();
        $kq = $p->getcthd($madon); // Adding category filter if needed
        if (mysqli_num_rows($kq) > 0) {
            return $kq;
        } else {
            return false; // No products found
        }
    }
    public function gettennd($madon) {
        $p = new msanpham();
        $kq = $p->gettennd($madon); // Adding category filter if needed
        if (mysqli_num_rows($kq) > 0) {
            return $kq;
        } else {
            return false; // No products found
        }
    }

    public function checkUserReview($manguoidung, $masp) {
        $p = new msanpham(); // Tạo đối tượng từ model
        $kq = $p->checkUserReview($manguoidung, $masp); // Gọi hàm trong model
        return $kq; // Trả về kết quả
    }
    public function getinsertdanhgia($manguoidung,$masp,$feedback,$rating,$hinhdg) {
        $p = new msanpham();
        $kq = $p->insertdanhgia($manguoidung,$masp,$feedback,$rating,$hinhdg) ; // Adding category filter if needed
        if ($kq) {
            return $kq;
        } else {
            return false; // No products found
        }
    }
    public function getDanhGiaSanPham($mact) {
        $p = new msanpham();
        $kq = $p->selectdanhgia($mact);
        return $kq; // Will return false if no result
    }

    public function getgmail() {
        $model = new msanpham();
        $thongTinDon = $model->getLastInsertedUser();
    
        if ($thongTinDon) {
            return $thongTinDon; 
        }
        return false; // Trả về false nếu thất bại
    }
}


?>
