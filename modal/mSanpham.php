<?php
include_once("ketnoi.php");
class Msanpham{
    public function selectAllSP() {
        $p = new clsKetNoi();
        $conn = $p ->moketnoi();
        if($conn) {
            $str ="Select HinhSP from sanpham";
            $tblSP =$conn ->query($str);
            $p -> dongketnoi($conn);
            return $tblSP;
        } else {
            return false;
        }

    }
    public function selectAllSPbyLoai($loai) { 
        $p = new clsKetNoi();
        $conn = $p ->moketnoi();
        if($conn) {
            $str = "Select * from sanpham where MaLoaiSP = '$loai'";
            $tblSP = $conn -> query($str);
            $p -> dongketnoi($conn);
            return $tblSP;
        } else {
            return false;
            
        }
        
    } 
    public function selectAllSPbyName($name) { 
        $p = new clsKetNoi();
        $conn = $p ->moketnoi();
        if($conn) {
            $str = "Select * from sanpham where TenSP like N'%$name%'";
            $tblSP = $conn -> query($str);
            $p -> dongketnoi($conn);
            return $tblSP;
        } else {
            return false;
            
        }
        
} 
}
?>