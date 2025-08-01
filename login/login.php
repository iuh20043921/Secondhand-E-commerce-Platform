<?php
session_start(); // Khởi tạo session
include_once("../modal/mUser.php");

$p = new MUser;

if (isset($_REQUEST['btnLogin'])) {
    $email = $_REQUEST['username'];
    $pass =$_REQUEST['password'];

    // Call the login method to check user credentials
    $userData = $p->dangnhap($email, $pass);
    
    if (is_array($userData)) {
        // If login is successful, check the user's status
        if ($userData['status'] == 0) {
            $error = 'Tài khoản của bạn chưa được xác nhận. Vui lòng kiểm tra email của bạn.';
        } else {
            // If user is confirmed (status = 1), log them in
            $_SESSION['MaNguoiDung'] = $userData['MaNguoiDung'];
            $_SESSION['TenDangNhap'] = $userData['TenDangNhap'];
            $_SESSION['TenNguoiDung'] = $userData['TenNguoiDung'];
            $_SESSION['role'] = $userData['Role'];
            $_SESSION['status'] = $userData['status']; // Store the status in session
            
            // Redirect to homepage or dashboard
            header('location:../index.php');
            exit;
        }
    } else {
        // If login fails or invalid credentials, display the error
        $error = $userData; // The error message returned by dangnhap()
    }
    
    // Display the error message using JavaScript if exists
    if (isset($error)) {
        echo '<script type="text/javascript">
                alert("' . $error . '");
              </script>';
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="Login.css">
    <title>Trang đăng nhập</title>
</head>
<body>
<div id="loadingOverlay" style="display:none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); z-index: 9999;">
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
        <div class="spinner-border text-light" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
</div>

<!-- Then, update the JavaScript in the same file -->
<script>
    // ... (previous JavaScript code)

    $(document).ready(function() {
        function showLoading() {
            $('#loadingOverlay').show();
        }

        function hideLoading() {
            $('#loadingOverlay').hide();
        }

        $('#loginForm').submit(function(e) {
            e.preventDefault();
            showLoading();
            $.ajax({
                // ... (previous AJAX settings)
                success: function(response) {
                    hideLoading();
                    // ... (previous success handling)
                },
                error: function() {
                    hideLoading();
                    // ... (previous error handling)
                }
            });
        });

        $('#registerForm').submit(function(e) {
            e.preventDefault();
            showLoading();
            $.ajax({
                // ... (previous AJAX settings)
                success: function(response) {
                    hideLoading();
                    // ... (previous success handling)
                },
                error: function() {
                    hideLoading();
                    // ... (previous error handling)
                }
            });
        });
    });
</script>

 <div class="wrapper">
    <nav class="nav">
        <div class="nav-logo">
            <p><a href="../tranchu/index.php">NewJean</a></p>
        </div>
        
        <div class="nav-button">
            <button class="btn white-btn" id="loginBtn" onclick="login()">Đăng nhập</button>
            <button class="btn" id="registerBtn" onclick="register()">Đăng ký</button>
        </div>
        <div class="nav-menu-btn">
            <i class="bx bx-menu" onclick="myMenuFunction()"></i>
        </div>
    </nav>

<!----------------------------- Form box ----------------------------------->    
    <div class="form-box">
        <!------------------- login form -------------------------->
        
        <div class="login-container" id="login">
        <form method="post" action="login.php">
            <div class="top">
                <span>Chưa có tài khoản? <a href="#" onclick="register()">Đăng ký ngay</a></span>
                <header>Đăng nhập</header>
            </div>
            <div class="input-box">
                <input type="text" class="input-field" id="username" name="username" placeholder="Email" required> 
                <i class="bx bx-user"></i>
            </div>
            <div class="input-box">
                <input type="password" class="input-field" placeholder="Mật khẩu" id="password" name="password" required>
                <i class="bx bx-lock-alt"></i>
            </div>
            <div class="input-box">
                <input type="submit" class="submit" name = "btnLogin" value="Đăng nhập">
            </div>
            <div class="two-col">
                <div class="one">
                    <input type="checkbox" id="login-check">
                    <label for="login-check">Ghi nhớ tài khoản</label>
                </div>
                <div class="two">
                    <label><a href="#">Quên mật khẩu?</a></label>
                </div>
            </div>
            </form>
        </div>
        
        <!------------------- registration form -------------------------->
        <div class="register-container" id="register">
            <form action="xuly1.php" method="POST" enctype="multipart/form-data">
            <div class="top">
                <span>Đã có tài khoản? <a href="#" onclick="login()">Đăng nhập</a></span>
                <header>Đăng ký</header>
            </div>
            <div id="errorMessages" class="alert alert-danger" style="display: none;"></div>
                <div class="input-box">
                    <input type="text" class="input-field" name = "txtName" placeholder="Tên người dùng">
                    <i class="bx bx-user"></i>
                </div>
                <div class="input-box">
                    <input type="text" class="input-field" name = "txtUsername" placeholder="Tên đăng nhập">
                    <i class="bx bx-user"></i>
                </div>
            <div class="input-box">
                <input type="text" class="input-field" name = "txtEmail" placeholder="Email">
                <i class="bx bx-envelope"></i>
            </div>
            <div class="input-box">
                <input type="text" class="input-field" name = "txtDiachi" placeholder="Địa chỉ">
                <i class="bx bx-envelope"></i>
            </div>
            <div class="input-box">
                <input type="Date" class="input-field" name = "txtNamsinh" placeholder="Năm sinh">
                <i class="bx bx-calendar"></i>
            </div>
            <div class="input-box">
                <input type="text" class="input-field" name = "txtPhone" placeholder="Số điện thoại">
                <i class="bx bx-phone"></i>
            </div>
            <div class="input-box">
                <input type="password" class="input-field" name = "txtPassword" placeholder="Mật khẩu">
                <i class="bx bx-lock-alt"></i>
            </div>
            <div class="input-box">
           
                <input type="submit" name="btnDangky" class="submit" value="Đăng ký">
          
            </div>
            <div class="two-col">
                <div class="one">
                    <input type="checkbox" id="register-check">
                    <label for="register-check">Ghi nhớ tôi</label>
                </div>
                <div class="two">
                    <label><a href="#">Chính sách</a></label>
                </div>
            </form>
            
            </div>
        </div>
    </div>
</div>   

<?php
if (isset($_SESSION['success'])) {
    echo "<p class='success'>" . $_SESSION['success'] . "</p>";
    unset($_SESSION['success']); // Xóa thông báo sau khi đã hiển thị
}
?>

<script>
   
   function myMenuFunction() {
    var i = document.getElementById("navMenu");

    if(i.className === "nav-menu") {
        i.className += " responsive";
    } else {
        i.className = "nav-menu";
    }
   }
 
</script>

<script>

    var a = document.getElementById("loginBtn");
    var b = document.getElementById("registerBtn");
    var x = document.getElementById("login");
    var y = document.getElementById("register");

    function login() {
        x.style.left = "4px";
        y.style.right = "-520px";
        a.className += " white-btn";
        b.className = "btn";
        x.style.opacity = 1;
        y.style.opacity = 0;
    }

    function register() {
        x.style.left = "-510px";
        y.style.right = "5px";
        a.className = "btn";
        b.className += " white-btn";
        x.style.opacity = 0;
        y.style.opacity = 1;
    }

</script>
<script>
    $(document).ready(function() {
        $('#registerForm').submit(function(e) {
            var password = $('#txtPassword').val();
            var email = $('input[name="txtEmail"]').val();
            if (password.length < 8) {
            alert('Mật khẩu phải có ít nhất 8 ký tự.');
            e.preventDefault();
            return;
        }
            $.ajax({
                url: 'check_email.php',
                type: 'POST',
                data: {email: email},
                success: function(response) {
                    if(response == 'exists') {
                        alert('Email đã được sử dụng. Vui lòng chọn email khác.');
                        e.preventDefault();
                    }
                }
            });
        });
    
    function showErrors(errors) {
        var errorDiv = $('#errorMessages');
        errorDiv.empty();
        $.each(errors, function(index, error) {
            errorDiv.append('<p>' + error + '</p>');
        });
        errorDiv.show();
        
        // Scroll to error messages
        $('html, body').animate({
            scrollTop: errorDiv.offset().top - 100
        }, 200);
    }
   });
    </script>
</body>
</html>
