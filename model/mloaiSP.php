<?php
  include_once("ketnoi.php");
  class mthuonghieu{
      public function selectAllThuongHieu(){
          $p = new clsketnoi();
          $con = $p->MoKetNoi();
          $truyvan="select*from loaisanpham";
          $kq=mysqli_query($con,$truyvan);
          $p -> DongKetNoi($con);
          return $kq;
      }
      public function selectOneThuonghieu($maTH){
        $p = new clsketnoi();
        $con = $p-> MoKetNoi();
        $truyvan= "SELECT * FROM sanpham s JOIN loaisanpham l ON s.MaLoaiSP = l.MaLoaiSP where t.MaLoaiSP = $maTH";
        $ketqua= mysqli_query($con,$truyvan);
        $p -> DongKetNoi($con);
        return $ketqua;
    }
    
  }
?>