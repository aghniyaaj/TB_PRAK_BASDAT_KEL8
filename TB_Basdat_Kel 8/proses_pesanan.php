<?php
include 'koneksi.php';

// Validasi input POST
if (!isset($_POST['nama']) || !isset($_POST['no_hp']) || !isset($_POST['alamat']) || 
    !isset($_POST['menu']) || !isset($_POST['jumlah']) || !isset($_POST['metode'])) {
    header("Location: admin/login.php?pesan=data_tidak_lengkap");
    exit;
}

$nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
$no_hp = mysqli_real_escape_string($koneksi, $_POST['no_hp']);
$alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
$menu = $_POST['menu'];
$jumlah = $_POST['jumlah'];
$pengiriman = mysqli_real_escape_string($koneksi, $_POST['metode']);

try {
    // Mulai transaction untuk memastikan konsistensi data
    mysqli_autocommit($koneksi, false);
    
    // Insert data pelanggan
    $query_pelanggan = "INSERT INTO pelanggan (nama, no_hp, alamat) VALUES ('$nama', '$no_hp', '$alamat')";
    if (!mysqli_query($koneksi, $query_pelanggan)) {
        throw new Exception("Error inserting pelanggan: " . mysqli_error($koneksi));
    }
    
    $id_pelanggan = mysqli_insert_id($koneksi);
    
    // Insert data pesanan
    $query_pesanan = "INSERT INTO pesanan (id_pelanggan, metode_pengiriman) VALUES ($id_pelanggan, '$pengiriman')";
    if (!mysqli_query($koneksi, $query_pesanan)) {
        throw new Exception("Error inserting pesanan: " . mysqli_error($koneksi));
    }
    
    $id_pesanan = mysqli_insert_id($koneksi);
    
    // Proses detail pesanan
    foreach ($menu as $index => $data) {
        // Pastikan data menu tidak kosong
        if (empty($data)) continue;
        
        // Parse data menu
        $menu_parts = explode('|', $data);
        if (count($menu_parts) < 3) continue; // Skip jika format tidak lengkap
        
        list($kategori, $nama_menu, $harga) = $menu_parts;
        
        // Escape string untuk keamanan
        $kategori = mysqli_real_escape_string($koneksi, $kategori);
        $nama_menu = mysqli_real_escape_string($koneksi, $nama_menu);
        $harga = floatval($harga);
        
        // Dapatkan jumlah berdasarkan index atau nama menu
        $jml = 1; // default value
        
        // Coba beberapa kemungkinan key untuk jumlah
        $possible_keys = [
            $index,
            $kategori . "_" . $index,
            $nama_menu,
            $kategori . "_" . $nama_menu
        ];
        
        foreach ($possible_keys as $key) {
            if (isset($jumlah[$key]) && intval($jumlah[$key]) > 0) {
                $jml = intval($jumlah[$key]);
                break;
            }
        }
        
        $subtotal = $harga * $jml;
        
        // Dapatkan id_menu dari database berdasarkan nama_menu dan kategori
        $tabel_menu = ($kategori === 'makanan') ? 'menu_makanan' : 'menu_minuman';
        
        $query_get_menu = "SELECT id FROM $tabel_menu WHERE nama_menu = '$nama_menu' LIMIT 1";

        $result_menu = mysqli_query($koneksi, $query_get_menu);
        
        if (!$result_menu) {
            throw new Exception("Error querying menu: " . mysqli_error($koneksi));
        }
        
        $row_menu = mysqli_fetch_assoc($result_menu);
        $id_menu = $row_menu ? $row_menu['id'] : 0;
        
        // Jika tidak ditemukan id_menu, skip item ini atau buat error handling
        if ($id_menu == 0) {
            // Option 1: Skip item ini
            continue;
            
            // Option 2: Atau throw error (uncomment baris di bawah jika ingin error)
            // throw new Exception("Menu '$nama_menu' tidak ditemukan dalam kategori '$kategori'");
        }
        
        // Insert detail pesanan
        $query_detail = "INSERT INTO detail_pesanan (id_pesanan, id_menu, kategori, jumlah, subtotal) 
                        VALUES ($id_pesanan, $id_menu, '$kategori', $jml, $subtotal)";
        
        if (!mysqli_query($koneksi, $query_detail)) {
            throw new Exception("Error inserting detail pesanan: " . mysqli_error($koneksi));
        }
    }
    
    // Commit transaction jika semua berhasil
    mysqli_commit($koneksi);
    mysqli_autocommit($koneksi, true);
    
    header("Location: pesanan_berhasil.php");
    exit;
    
} catch (Exception $e) {
    // Rollback jika ada error
    mysqli_rollback($koneksi);
    mysqli_autocommit($koneksi, true);
    
    // Log error atau tampilkan pesan error
    error_log("Order processing error: " . $e->getMessage());
    header("Location: admin/login.php?pesan=order_gagal&error=" . urlencode($e->getMessage()));
    exit;
}
?>