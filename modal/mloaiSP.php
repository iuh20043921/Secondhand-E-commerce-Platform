<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        ul li {
            text-decoration: none;
        }
    </style>
</head>
<body>
    <?php
    include_once("ketnoi.php");
    class LSanpham {
        public function SelectAllLSP(){
            $p = new clsKetNoi();
            $conn = $p -> moketnoi();
            if($conn) {
                $str = "select * from loaisanpham";
                $tblSP = $conn -> query("$str");
                $p -> dongketnoi($conn);
                return $tblSP;
            }
            else {
                return false;
        }
    }
    public function SelectAllLSPbyname($name){
        $p = new clsKetNoi();
        $conn = $p -> moketnoi();
        if($conn) {
            $str = "select * from loaisanpham Where TenLoaiSP = '$name'";
            $tblSP = $conn -> query("$str");
            $p -> dongketnoi($conn);
            return $tblSP;
        }
        else {
            return false;
    }
}

    }
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
</body>
</html>