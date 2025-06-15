<?php
include '../koneksi.php';
$id = $_GET['id'];

// Hapus detail terlebih dahulu
mysqli_query($koneksi, "DELETE FROM detail_pesanan WHERE id_pesanan = $id");

// Dapatkan id pelanggan sebelum hapus pesanan
$q = mysqli_query($koneksi, "SELECT id_pelanggan FROM pesanan WHERE id = $id");
$r = mysqli_fetch_assoc($q);
$id_pelanggan = $r['id_pelanggan'];

// Hapus pesanan
mysqli_query($koneksi, "DELETE FROM pesanan WHERE id = $id");

// Hapus pelanggan (opsional, hati-hati kalau pelanggan bisa pesan lebih dari sekali)
mysqli_query($koneksi, "DELETE FROM pelanggan WHERE id = $id_pelanggan");

header("Location: pesanan.php");
