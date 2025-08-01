<?php
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "secondhandn";

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];


    // $sql = "UPDATE sanpham SET isDeleted = 1 WHERE MaSP = ?";
    $sql = "DELETE FROM sanpham WHERE MaSP = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();


    header("Location: quanlytin.php");
    exit();
}
?>
