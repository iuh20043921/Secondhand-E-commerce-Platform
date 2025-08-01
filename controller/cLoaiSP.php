<?php
    include_once("../model/mloaiSP.php");
    class controllerThuongHieu{
        public function getAllThuongHieu(){
            $p = new mthuonghieu();
            $kq = $p->selectAllThuongHieu();
            if(mysqli_num_rows($kq)>0){
                return $kq;
            }else{
                return false;
            }
        }
        public function getOneThuonghieu($maTH){
            $p= new mthuonghieu();
            $ketqua = $p->selectOneThuonghieu($maTH);
            if(mysqli_num_rows($ketqua)>0){
                return $ketqua;
            } else return false;
        }
        
    }

?>