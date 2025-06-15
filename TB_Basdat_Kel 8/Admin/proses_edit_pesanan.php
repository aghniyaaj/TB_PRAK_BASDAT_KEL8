<?php
include '../koneksi.php';

$id = $_POST['id'];
$nama = $_POST['nama'];
$no_hp = $_POST['no_hp'];
$alamat = $_POST['alamat'];
$metode = $_POST['metode_pengiriman'];
$status = $_POST['status'];

// Update pelanggan
$update_pelanggan = "UPDATE pelanggan 
    JOIN pesanan ON pelanggan.id = pesanan.id_pelanggan 
    SET nama='$nama', no_hp='$no_hp', alamat='$alamat' 
    WHERE pesanan.id = $id";
mysqli_query($koneksi, $update_pelanggan);

// Update pesanan
$update_pesanan = "UPDATE pesanan SET metode_pengiriman='$metode', status='$status' WHERE id = $id";
mysqli_query($koneksi, $update_pesanan);

header("Location: pesanan.php");
