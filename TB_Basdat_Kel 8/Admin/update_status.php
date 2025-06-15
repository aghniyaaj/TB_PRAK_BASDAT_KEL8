<?php
include '../koneksi.php';

$id = $_POST['id'];
$status = $_POST['status'];

mysqli_query($koneksi, "UPDATE pesanan SET status='$status' WHERE id=$id");

header("Location: pesanan.php");
?>
