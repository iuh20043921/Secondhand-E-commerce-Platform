<?php
include_once("../modal/mUser.php");

if (isset($_GET['code'])) {
    $verificationCode = $_GET['code'];

    // Kiểm tra mã xác nhận có hợp lệ không
    $mUser = new MUser();
    $result = $mUser->verifyUser($verificationCode);

    if ($result) {
        echo "<script>
                alert('Xác nhận thành công! Bạn có thể đăng nhập ngay bây giờ.');
                window.location.href = 'login.php';  // Chuyển hướng đến trang đăng nhập
              </script>";
    } else {
        echo "<script>
                alert('Mã xác nhận không hợp lệ hoặc đã hết hạn.');
              </script>";
    }
}
?>
