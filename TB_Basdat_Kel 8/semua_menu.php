<?php
include 'koneksi.php';

$query = "
SELECT id, 'makanan' AS kategori, nama_menu, harga, deskripsi FROM menu_makanan
UNION ALL
SELECT id, 'minuman' AS kategori, nama_menu, harga, deskripsi FROM menu_minuman
";

$hasil = mysqli_query($koneksi, $query);

// Pisahkan makanan dan minuman
$makanan = [];
$minuman = [];
while ($row = mysqli_fetch_assoc($hasil)) {
    if ($row['kategori'] === 'makanan') {
        $makanan[] = $row;
    } else {
        $minuman[] = $row;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Next Door Menu</title>
    <link href="https://fonts.googleapis.com/css2?family=Sour+Gummy&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Sour Gummy', cursive, sans-serif;
            background-image: url('bg1.jpeg');
            background-size: cover;
            color: #333;
            margin: 0;
            padding: 0;
            text-align: center;
        }
        .logo {
            margin-top: 20px;
        }
        .menu-container {
            display: flex;
            justify-content: center;
            gap: 40px;
            margin-top: 20px;
        }
        .menu-section {
            width: 40%;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 20px;
            padding: 20px;
        }
        h2 {
            color: #ff69b4;
            font-size: 28px;
        }
        .menu-item {
            margin-bottom: 15px;
            text-align: left;
        }
        .menu-item input[type=number] {
            width: 50px;
        }
        .pesan-btn {
            font-family: 'Sour Gummy', cursive;
            background-color: #ff69b4;
            color: white;
            border: none;
            padding: 15px 30px;
            font-size: 20px;
            border-radius: 28px;
            cursor: pointer;
            margin: 30px auto;
            display: block;
        }
    </style>
</head>
<body>
    <div class="logo">
        <img src="logo.png" alt="Logo" width="200">
    </div>

    <form action="form_pesanan.php" method="POST">
        <div class="menu-container">
            <div class="menu-section">
                <h2>MENU MAKANAN</h2>
                <?php foreach ($makanan as $row) {
                    $key = $row['kategori'] . "_" . $row['id'];
                ?>
                    <div class="menu-item">
                        <input type="checkbox" name="menu[]" value="<?= $key ?>">
                        <strong><?= $row['nama_menu'] ?></strong> - Rp <?= number_format($row['harga']) ?><br>
                        <small><?= $row['deskripsi'] ?></small><br>
                        Jumlah: <input type="number" name="jumlah[<?= $key ?>]" value="1" min="1">
                    </div>
                <?php } ?>
            </div>
            <div class="menu-section">
                <h2>MENU MINUMAN</h2>
                <?php foreach ($minuman as $row) {
                    $key = $row['kategori'] . "_" . $row['id'];
                ?>
                    <div class="menu-item">
                        <input type="checkbox" name="menu[]" value="<?= $key ?>">
                        <strong><?= $row['nama_menu'] ?></strong> - Rp <?= number_format($row['harga']) ?><br>
                        <small><?= $row['deskripsi'] ?></small><br>
                        Jumlah: <input type="number" name="jumlah[<?= $key ?>]" value="1" min="1">
                    </div>
                <?php } ?>
            </div>
        </div>
        <input type="submit" value="PESAN SEKARANG" class="pesan-btn">
    </form>
</body>
</html>
