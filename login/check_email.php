<?php
include_once("../modal/mUser.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    
    $mUser = new MUser();
    $result = $mUser->selectUserbyEmail($email);
    
    if ($result) {
        echo 'exists';
    } else {
        echo 'not_exists';
    }
}
?>