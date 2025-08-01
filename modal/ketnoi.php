<?php 
    class clsKetnoi {
        public function moketnoi() {    
            return mysqli_connect("localhost","root","","secondhandn");
        }
        public function dongketnoi($conn) {
            $conn -> close(); }
    }
?>