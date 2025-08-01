<?php
session_start();
include_once("../modal/mUser.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form đăng ký
    $tenNguoiDung = trim($_POST['txtName']);
    $tenDangNhap = trim($_POST['txtUsername']);
    $email = trim($_POST['txtEmail']);
    $diaChi = trim($_POST['txtDiachi']);
    $ngaySinh = trim($_POST['txtNamsinh']);
    $soDienThoai = trim($_POST['txtPhone']);
    $matKhau = trim($_POST['txtPassword']);

    // Tạo đối tượng CUser để xử lý
    include_once("../controller/cUser.php");
    $cUser = new CUser();

    // Kiểm tra xem tên đăng nhập đã tồn tại chưa
    $checkUsername = $cUser->getUserbyusername($tenDangNhap);
    if ($checkUsername !== 0 && $checkUsername !== -1) {
        echo "Tên đăng nhập đã tồn tại!";
        exit;
    }

    // Kiểm tra xem email đã tồn tại chưa
    $checkEmail = $cUser->getUserbyEmail($email);
    if ($checkEmail !== 0 && $checkEmail !== -1) {
        echo "Email đã được sử dụng!";
        exit;
    }

    // Kiểm tra mật khẩu
    if (strlen($matKhau) <= 8) {
        echo "Mật khẩu phải có ít nhất 8 ký tự!";
        exit;
    }
    $hashedPassword = md5($matKhau); // Mã hóa mật khẩu

    // Tạo câu truy vấn SQL để thêm người dùng
    $sql = "INSERT INTO nguoidung (TenNguoiDung, TenDangNhap, MatKhau, Email, DiaChi, NgaySinh, SDT) 
            VALUES ('$tenNguoiDung', '$tenDangNhap', '$hashedPassword', '$email', '$diaChi', '$ngaySinh', '$soDienThoai')";

    // Gọi hàm để thêm người dùng
    $mUser = new MUser();
    $result = $mUser->themUser($sql);

    // Kiểm tra và phản hồi
    if ($result) {
        // Tạo mã xác nhận
        $verificationCode = md5(uniqid(rand(), true)); // Tạo mã xác nhận ngẫu nhiên

        // Cập nhật mã xác nhận vào CSDL
        $updateSql = "UPDATE nguoidung SET verification_code = '$verificationCode' WHERE Email = '$email'";
        $mUser->themUser($updateSql); // Cập nhật mã xác nhận vào CSDL

        // Gửi email xác nhận
        if (sendConfirmationEmail($email, $tenNguoiDung, $verificationCode)) {
            echo "<script>
                    alert('Đăng ký thành công! Một email xác nhận đã được gửi.');
                    window.location.href = 'login.php';  // Chuyển hướng đến trang login.php
                  </script>";
            exit;
        } else {
            echo "<script>
                    alert('Đăng ký thành công, nhưng không thể gửi email xác nhận!');
                  </script>";
        }
    } else {
        echo "<script>
                alert('Đăng ký thất bại. Vui lòng thử lại.');
              </script>";
    }
}

// Hàm gửi email xác nhận
function sendConfirmationEmail($toEmail, $toName, $verificationCode) {
    // Include PHPMailer manually
    require("../PHPMailer-master/src/PHPMailer.php");
    require("../PHPMailer-master/src/SMTP.php");
    require("../PHPMailer-master/src/Exception.php");

    $mail = new PHPMailer\PHPMailer\PHPMailer();
    try {
        // Cấu hình
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        $mail->Username = "ngocthuyyy0710@gmail.com";  // Thay bằng tài khoản Gmail của bạn
        $mail->Password = "xljsbnxyeclwnvua";  // Thay bằng mật khẩu ứng dụng của bạn
        $mail->setFrom("ngocthuyyy0710@gmail.com", "NewJean");
        $mail->Subject = "Website NewJean!";
        $mail->addAddress($toEmail, $toName); // Thêm người nhận

        // Cấu hình email
        $mail->isHTML(true);
        $mail->Body = '
            <!DOCTYPE html>
<html>
<head>
    <style>
        /* Reset default styles */
        body, table, td, a {
            margin: 0;
            padding: 0;
            text-size-adjust: none;
            font-family: Arial, sans-serif;
        }
        img {
            border: 0;
            display: block;
        }
        /* General Styles */
        body {
            background-color: #f4f4f4;
            color: #333333;
            font-size: 16px;
            line-height: 1.6;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #FF7F00; /* Orange color */
            text-align: center;
            font-size: 24px;
            font-weight: bold;
        }
        p {
            margin-bottom: 20px;
            font-size: 16px;
        }
        a {
            color: #4CAF50; /* Green color for the link */
            font-weight: bold;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline; /* Underline the link on hover */
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #888888;
            margin-top: 40px;
        }
    </style>
</head>
<body>
    <!-- Main Container -->
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center">
                <table role="presentation" class="container" cellpadding="0" cellspacing="0">
                    <tr>
                        <td>
                            <h1>Chào '.$toName.'</h1>
                            <p>Cảm ơn bạn đã đăng ký tài khoản trên website của chúng tôi.</p>
                            <p>Để tiếp tục, vui lòng <a href="http://localhost/bandocu1/login/xacnhan.php?code=' . $verificationCode . '">Xác nhận đăng ký tại đây</a></p>
                        </td>
                    </tr>
                    <tr>
                        <td class="footer">
                            <p>Chúc bạn có một ngày tuyệt vời!</p>
                            <p>Website của chúng tôi luôn sẵn sàng hỗ trợ bạn.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
';

        // Gửi email
        return $mail->send();
    } catch (Exception $e) {
        // Nếu có lỗi, trả về false
        echo "Mailer Error: " . $mail->ErrorInfo;
        return false;
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="Login.css">
    <title>Trang đăng nhập</title>
</head>
<body>
 <div class="wrapper">
    <nav class="nav">
        <div class="nav-logo">
            <p><a href="../index.php">NewJean</a></p>
        </div>
        
        <div class="nav-button">
            <button class="btn white-btn" id="loginBtn" onclick="login()">Đăng nhập</button>
            <button class="btn" id="registerBtn" onclick="register()">Đăng ký</button>
        </div>
        <div class="nav-menu-btn">
            <i class="bx bx-menu" onclick="myMenuFunction()"></i>
        </div>
    </nav>



</body>
</html>