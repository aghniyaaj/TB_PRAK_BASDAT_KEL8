<?php
include 'koneksi.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $menu_terpilih = $_POST['menu'] ?? [];
    $jumlah = $_POST['jumlah'] ?? [];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Form Pemesanan</title>
    <link href="https://fonts.googleapis.com/css2?family=Sour+Gummy&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Sour Gummy', cursive;
            background-image: url('bg1.jpeg');
            background-size: cover;
            background-position: center;
            color: #333;
            padding: 20px;
        }
        .container {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 30px;
            max-width: 700px;
            margin: auto;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        }
        h2 {
            text-align: center;
            color: #f2689b;
            margin-bottom: 30px;
        }
        label {
            display: block;
            margin-bottom: 6px;
            margin-top: 15px;
            color: #444;
        }
        input[type="text"], textarea, select {
            width: 100%;
            padding: 10px;
            border: 2px solid #f6b4c2;
            border-radius: 10px;
            box-sizing: border-box;
            font-family: 'Sour Gummy', cursive;
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            font-size: 16px;
        }
        table th, table td {
            border: 1px solid #f6b4c2;
            padding: 10px;
            text-align: center;
        }
        .btn-pesan {
            font-family: 'Sour Gummy', cursive;
            background-color: #f2689b;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 25px;
            font-size: 18px;
            cursor: pointer;
            display: block;
            margin: 30px auto 0;
        }
        .btn:hover {
            background-color: #e2578a;
        }
        .warning {
            color: red;
            text-align: center;
        }
        .logo {
            display: block;
            margin: 0 auto 20px;
            width: 150px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Form Pemesanan</h2>
    <form action="proses_pesanan.php" method="POST">
        Nama:<br>
        <input type="text" name="nama" required><br>
        No HP:<br>
        <input type="text" name="no_hp" required><br>
        Alamat:<br>
        <textarea name="alamat" ></textarea><br>
        Metode Pengiriman:<br>
        <select name="metode">
            <option value="Dine In">Dine In</option>
            <option value="Take Away">Take Away</option>
            <option value="Delivery">Delivery</option>
        </select><br>

        <h3>Rincian Pesanan</h3>

        <?php
        if (!empty($_POST['menu'])) {
            $menu_terpilih = $_POST['menu'];
            $jumlah = $_POST['jumlah'];
            $total = 0;
            ?>
            <table>
                <thead>
                <tr>
                    <th>Menu</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($menu_terpilih as $key) {
                    list($kategori, $id) = explode("_", $key);

                    // Query untuk ambil detail menu dari DB
                    $query = "SELECT nama_menu, harga FROM menu_" . $kategori . " WHERE id = ?";
                    $stmt = mysqli_prepare($koneksi, $query);
                    mysqli_stmt_bind_param($stmt, "i", $id);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_bind_result($stmt, $nama_menu, $harga);
                    mysqli_stmt_fetch($stmt);
                    mysqli_stmt_close($stmt);

                    $jml = isset($jumlah[$key]) ? intval($jumlah[$key]) : 1;
                    $subtotal = $harga * $jml;
                    $total += $subtotal;
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($nama_menu) ?></td>
                        <td><?= htmlspecialchars(ucfirst($kategori)) ?></td>
                        <td>Rp <?= number_format($harga) ?></td>
                        <td><?= $jml ?></td>
                        <td>Rp <?= number_format($subtotal) ?></td>
                    </tr>
                    <input type="hidden" name="menu[]" value="<?= $kategori . '|' . $nama_menu . '|' . $harga ?>">
                    <input type="hidden" name="jumlah[<?= $kategori . '_' . $nama_menu ?>]" value="<?= $jml ?>">

                <?php } ?>
                <tr>
                    <th colspan="4">Total</th>
                    <th>Rp <?= number_format($total) ?></th>
                </tr>
                </tbody>
            </table>
            <input type="hidden" name="total" value="<?= $total ?>">
            <input type="submit" value="KONFIRMASI PESANAN" class="btn-pesan">
            <?php
        } else {
            echo "<p style='color:red;'>Tidak ada menu yang dipilih.</p>";
        }
        ?>
    </form>
</div>
</body>
</html>
