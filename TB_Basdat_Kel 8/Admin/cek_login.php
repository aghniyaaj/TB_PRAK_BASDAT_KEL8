<?php
session_start();
include '../koneksi.php';

$user = $_POST['username'];
$pass = md5($_POST['password']);

$result = mysqli_query($koneksi, "SELECT * FROM admin WHERE username='$user' AND password='$pass'");
if (mysqli_num_rows($result) > 0) {
    $_SESSION['admin'] = $user;
    header("Location: pesanan.php"); 
} else {
    echo "Login gagal. <a href='login.php'>Coba lagi</a>";
}
?>