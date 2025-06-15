<?php
include '../koneksi.php';

// Fungsi untuk debugging
function debug_log($message) {
    error_log("[STRUK DEBUG] " . date('Y-m-d H:i:s') . " - " . $message);
}

if (isset($_GET['id'])) {
    $id_pesanan = $_GET['id'];
    debug_log("Mencari pesanan dengan ID: $id_pesanan");

    // Ambil data pesanan dan pelanggan
    $query = "SELECT pesanan.id, pelanggan.nama, pelanggan.no_hp, pelanggan.alamat, pesanan.metode_pengiriman, pesanan.tanggal
              FROM pesanan 
              JOIN pelanggan ON pesanan.id_pelanggan = pelanggan.id
              WHERE pesanan.id = ?";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("i", $id_pesanan);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $nama = $row['nama'];
        $no_hp = $row['no_hp'];
        $alamat = $row['alamat'];
        $metode = $row['metode_pengiriman'];
        $tanggal = $row['tanggal'];
        debug_log("Data pesanan ditemukan untuk: $nama");
    } else {
        debug_log("ERROR: Pesanan dengan ID $id_pesanan tidak ditemukan!");
        echo "Pesanan tidak ditemukan!";
        exit;
    }

    // Ambil detail pesanan dengan kategori yang disimpan di tabel
    $detail_query = "SELECT id_menu, jumlah, subtotal, kategori FROM detail_pesanan WHERE id_pesanan = ?";
    debug_log("Query detail pesanan: $detail_query dengan ID: $id_pesanan");
    
    $stmt_detail = $koneksi->prepare($detail_query);
    $stmt_detail->bind_param("i", $id_pesanan);
    $stmt_detail->execute();
    $result_detail = $stmt_detail->get_result();
    
    debug_log("Jumlah detail pesanan ditemukan: " . $result_detail->num_rows);
    
    // Debug: Tampilkan semua detail pesanan
    if ($result_detail->num_rows == 0) {
        debug_log("WARNING: Tidak ada detail pesanan ditemukan!");
        
        // Cek apakah ada data di tabel detail_pesanan
        $check_query = "SELECT COUNT(*) as total FROM detail_pesanan";
        $check_result = mysqli_query($koneksi, $check_query);
        if ($check_result) {
            $check_row = mysqli_fetch_assoc($check_result);
            debug_log("Total record di tabel detail_pesanan: " . $check_row['total']);
        }
        
        // Cek semua detail pesanan untuk debugging
        $all_detail_query = "SELECT * FROM detail_pesanan ORDER BY id_pesanan DESC LIMIT 10";
        $all_detail_result = mysqli_query($koneksi, $all_detail_query);
        if ($all_detail_result) {
            debug_log("10 Detail pesanan terakhir:");
            while ($detail_row = mysqli_fetch_assoc($all_detail_result)) {
                debug_log("ID Pesanan: " . $detail_row['id_pesanan'] . ", ID Menu: " . $detail_row['id_menu'] . ", Kategori: " . $detail_row['kategori'] . ", Jumlah: " . $detail_row['jumlah']);
            }
        }
    }
} else {
    debug_log("ERROR: ID pesanan tidak ditemukan di parameter GET!");
    echo "ID pesanan tidak ditemukan!";
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Struk Pesanan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }
        .container {
            width: 500px;
            margin: auto;
            padding: 20px;
            border: 2px dashed #000;
        }
        h2 {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table, th, td {
            border: 1px solid #333;
        }
        th, td {
            padding: 8px;
        }
        .debug {
            background-color: #f0f0f0;
            padding: 10px;
            margin: 10px 0;
            text-align: left;
            font-size: 12px;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Struk Pesanan</h2>
    <p><strong>ID Pesanan:</strong> <?php echo $id_pesanan; ?></p>
    <p><strong>Nama:</strong> <?php echo $nama; ?></p>
    <p><strong>No HP:</strong> <?php echo $no_hp; ?></p>
    <p><strong>Alamat:</strong> <?php echo $alamat; ?></p>
    <p><strong>Metode Pengiriman:</strong> <?php echo $metode; ?></p>
    <p><strong>Tanggal:</strong> <?php echo $tanggal; ?></p>

    <!-- Debug Info -->
    <div class="debug">
        <strong>Debug Info:</strong><br>
        Jumlah detail pesanan: <?php echo $result_detail->num_rows; ?><br>
        ID Pesanan: <?php echo $id_pesanan; ?>
    </div>

    <table>
        <tr>
            <th>Kategori</th>
            <th>Nama Menu</th>
            <th>Jumlah</th>
            <th>Subtotal</th>
        </tr>
        <?php
        $grandtotal = 0;
        $item_count = 0;
        
        while ($detail = $result_detail->fetch_assoc()) {
            $kategori = $detail['kategori'];
            $id_menu = $detail['id_menu'];
            $jumlah = $detail['jumlah'];
            $subtotal = $detail['subtotal'];
            $grandtotal += $subtotal;
            $item_count++;

            debug_log("Processing detail - Kategori: $kategori, ID Menu: $id_menu, Jumlah: $jumlah, Subtotal: $subtotal");

            // Ambil nama menu dari tabel menu_makanan atau menu_minuman sesuai kategori
            $menu_table = $kategori === 'makanan' ? 'menu_makanan' : 'menu_minuman';
            $menu_query = "SELECT nama_menu FROM $menu_table WHERE id = ?";
            
            debug_log("Query nama menu: $menu_query dengan ID: $id_menu");
            
            $stmt_menu = $koneksi->prepare($menu_query);
            $stmt_menu->bind_param("i", $id_menu);
            $stmt_menu->execute();
            $result_menu = $stmt_menu->get_result();
            
            if ($result_menu->num_rows > 0) {
                $menu_row = $result_menu->fetch_assoc();
                $nama_menu = $menu_row['nama_menu'];
                debug_log("Nama menu ditemukan: $nama_menu");
            } else {
                $nama_menu = "Menu tidak ditemukan (ID: $id_menu)";
                debug_log("WARNING: Nama menu tidak ditemukan untuk ID: $id_menu di tabel: $menu_table");
            }
            $stmt_menu->close();

            echo "<tr>";
            echo "<td>".ucfirst($kategori)."</td>";
            echo "<td>$nama_menu</td>";
            echo "<td>$jumlah</td>";
            echo "<td>Rp ".number_format($subtotal, 0, ',', '.')."</td>";
            echo "</tr>";
        }
        
        debug_log("Total item yang ditampilkan: $item_count, Grand Total: $grandtotal");
        
        if ($item_count == 0) {
            echo "<tr><td colspan='4'>Tidak ada detail pesanan ditemukan</td></tr>";
        }
        ?>
        <tr>
            <td colspan="3"><strong>Total</strong></td>
            <td><strong>Rp <?php echo number_format($grandtotal, 0, ',', '.'); ?></strong></td>
        </tr>
    </table>

    <!-- Debug Info untuk Grand Total -->
    <div class="debug">
        <strong>Debug Total:</strong><br>
        Jumlah item: <?php echo $item_count; ?><br>
        Grand Total: Rp <?php echo number_format($grandtotal, 0, ',', '.'); ?>
    </div>

    <p>Terima kasih atas pesanan Anda!</p>
</div>
</body>
</html>