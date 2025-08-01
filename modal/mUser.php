<?php
include_once("ketnoi.php");

class MUser {
    public function dangnhap($user, $pass) {
        $p = new clsKetnoi;
        $conn = $p->moketnoi();
        
        // Truy vấn cơ sở dữ liệu
        $stmt = $conn->prepare("SELECT MaNguoiDung, TenDangNhap, TenNguoiDung, MatKhau, Role, status FROM nguoidung WHERE Email = ?");
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $r = $result->fetch_assoc();
            $hashedPassword = md5($pass); // Mã hóa mật khẩu người dùng nhập
            
            if ($r['MatKhau'] === $hashedPassword) {
                if ($r['status'] == 1) {
                    return $r; // Đăng nhập thành công
                } else {
                    return 'Tài khoản của bạn chưa được xác nhận. Vui lòng kiểm tra email.';
                }
            } else {
                return 'Tên đăng nhập hoặc mật khẩu không đúng.';
            }
        }
        return 'Tên đăng nhập hoặc mật khẩu không đúng.';
    }    
    
    
    public function checkEmailExists($email) {
        $p = new clsKetnoi;
        $conn = $p ->moketnoi();
        $sql = "SELECT * FROM nguoidung WHERE Email = '$email'";
        $result = $this->$conn->query($sql);
        return $result->num_rows > 0;
    }

    public function themUser($sql) {
        // Tạo kết nối mới
        $conn = new clsKetNoi();

        // Mở kết nối đến cơ sở dữ liệu
        $con = $conn->moketnoi();

        // Kiểm tra kết nối
        if($con) {
            // Thực thi truy vấn
            if($con->query($sql) === TRUE) {
                // Đóng kết nối
                $conn->dongketnoi($con);
                return true; // Trả về true nếu thêm sản phẩm thành công
            } else {
                // Đóng kết nối
                $conn->dongketnoi($con);
                return false; // Trả về false nếu có lỗi khi thêm sản phẩm
            }
        } else {
            return false; // Trả về false nếu không thể kết nối đến cơ sở dữ liệu
        }

    }
    public function selectUser(){
            $p=new clsKetNoi();
            $con=$p->moketnoi();
            if($con){
                $str="select*from taikhoan";
                $tblSP=$con->query($str);
                $p->dongketnoi($con);
                return $tblSP;
            }else{
            return false;// khoong thể kết nối với CSDL
            }
        }
        public function selectUserbyusername($uname){
            $p=new clsKetNoi();
            $con=$p->moketnoi();
            if($con){
                $str="select*from nguoidung where TenDangNhap='$uname'";
                $tblSP=$con->query($str);
                $p->dongketnoi($con);
                return $tblSP;
            }else{
            return false;// khoong thể kết nối với CSDL
            }
        }
        public function selectUserbyEmail($email){
            $p=new clsKetNoi();
            $con=$p->moketnoi();
            if($con){
                $str="select*from nguoidung where Email='$email'";
                $tblSP=$con->query($str);
                $p->dongketnoi($con);
                return $tblSP;
            }else{
            return false;// khoong thể kết nối với CSDL
            }
        }
        public function getLastInsertedUser($mand) {
            $p = new clsKetnoi();
            $con = $p->moketnoi();
        
            // Chuẩn bị câu truy vấn
            $sql = "SELECT * FROM nguoidung where Email ='$mand'";
            $stmt = $con->prepare($sql);
        
            if ($stmt) {
                // Thực thi truy vấn
                $stmt->execute();
                
                // Lấy kết quả
                $result = $stmt->get_result();
                if ($result && $result->num_rows > 0) {
                    $lastUser = $result->fetch_assoc(); // Lấy bản ghi cuối cùng
                    $stmt->close();
                    $p->dongKetNoi($con);
                    return $lastUser; // Trả về thông tin người dùng
                }
                
                $stmt->close();
            }
        
            $p->dongKetNoi($con);
            return false; // Trả về false nếu không tìm thấy
        }
// Phương thức xác nhận người dùng
public function verifyUser($verificationCode) {
    // Tạo đối tượng kết nối cơ sở dữ liệu
    $p = new clsKetNoi();
    $con = $p->moketnoi();
    
    // Kiểm tra kết nối cơ sở dữ liệu
    if ($con) {
        // Truy vấn để kiểm tra mã xác nhận trong cơ sở dữ liệu
        $str = "SELECT * FROM nguoidung WHERE verification_code = '$verificationCode' AND status = 0";
        $tblSP = $con->query($str);
        
        // Nếu mã xác nhận hợp lệ
        if ($tblSP && $tblSP->num_rows > 0) {
            // Cập nhật trạng thái người dùng thành đã xác nhận
            $strUpdate = "UPDATE nguoidung SET status = 1 WHERE verification_code = '$verificationCode'";
            $con->query($strUpdate);
            
            // Đóng kết nối cơ sở dữ liệu
            $p->dongketnoi($con);
            return true;
        }
        
        // Đóng kết nối nếu không có kết quả
        $p->dongketnoi($con);
        return false;
    } else {
        // Nếu không thể kết nối đến cơ sở dữ liệu
        return false;
    }
}
public function updateUserStatus($userId) {
    $p = new clsKetNoi();  // Assuming clsKetNoi is your database connection class
    $con = $p->moketnoi();  // Open the database connection

    if ($con) {
        // SQL query to update user status
        $str = "UPDATE nguoidung SET status = 1 WHERE MaNguoiDung = '$userId'";
        
        // Execute the query
        if ($con->query($str) === TRUE) {
            $p->dongketnoi($con);  // Close the connection
            return true;  // Successfully updated
        } else {
            $p->dongketnoi($con);  // Close the connection
            return false;  // Query execution failed
        }
    } else {
        return false;  // Connection failed
    }
}

        
    
}


