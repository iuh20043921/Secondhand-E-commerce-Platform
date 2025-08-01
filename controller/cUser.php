<?php
    include_once("../modal/mUser.php");
    class CUser{
        public function getUser(){
            $p= new MUser();
            $tblSP=$p->selectUser();
            if(!$tblSP){
                return -1;
            }else{
                if($tblSP->num_rows>0){
                    return $tblSP;
                }else{
                    return 0; // 0 có dòng dữ liệu
                }
            }
        }
        public function getUserbyusername($uname){
            $p= new MUser();
            $tblSP=$p->selectUserbyusername($uname);
            if(!$tblSP){
                return -1;
            }else{
                if($tblSP->num_rows>0){
                    return $tblSP;
                }else{
                    return 0; // 0 có dòng dữ liệu
                }
            }
        }   
        public function getUserbyEmail($email){
            $p= new MUser;
            $tblSP=$p->selectUserbyEmail($email);
            if(!$tblSP){
                return -1;
            }else{
                if($tblSP->num_rows>0){
                    return $tblSP;
                }else{
                    return 0; // 0 có dòng dữ liệu
                }
            }
        }
        public function getgmail($mand) {
            $model = new MUser();
            $thongTinDon = $model->getLastInsertedUser($mand);
        
            if ($thongTinDon) {
                return $thongTinDon; 
            }
            return false; // Trả về false nếu thất bại
        }
       
        
        
       
        
    }

  
?>