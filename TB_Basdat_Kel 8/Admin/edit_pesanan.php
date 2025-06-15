<?php
include '../koneksi.php';
$id = $_GET['id'];

$query = "SELECT p.*, pl.nama, pl.no_hp, pl.alamat 
          FROM pesanan p
          JOIN pelanggan pl ON p.id_pelanggan = pl.id
          WHERE p.id = $id";
$result = mysqli_query($koneksi, $query);
$data = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Pesanan</title>
    <link href="https://fonts.googleapis.com/css2?family=Sour+Gummy&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Sour Gummy', cursive;
            background-color: #fce4ec;
            padding: 40px;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 20px;
            max-width: 500px;
            margin: auto;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #e91e63;
        }
        label {
            display: block;
            margin-top: 15px;
            color: #880e4f;
        }
        input[type="text"],
        textarea,
        select {
            width: 100%;
            padding: 12px;
            border-radius: 10px;
            border: 2px solid #f8bbd0;
            margin-top: 5px;
            font-family: 'Sour Gummy', cursive;
        }
        .btn-submit {
            background-color: #ec407a;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 25px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
            display: block;
            width: 100%;
        }
        .btn-submit:hover {
            background-color: #d81b60;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Edit Pesanan</h2>
    <form method="POST" action="proses_edit_pesanan.php">
        <input type="hidden" name="id" value="<?= $id ?>">
        
        <label>Nama</label>
        <input type="text" name="nama" value="<?= $data['nama'] ?>" required>

        <label>No HP</label>
        <input type="text" name="no_hp" value="<?= $data['no_hp'] ?>" required>

        <label>Alamat</label>
        <textarea name="alamat"><?= $data['alamat'] ?></textarea>

        <label>Metode Pengiriman</label>
        <select name="metode_pengiriman">
            <option value="dine in" <?= $data['metode_pengiriman']=='dine in'?'selected':'' ?>>Dine In</option>
            <option value="take away" <?= $data['metode_pengiriman']=='take away'?'selected':'' ?>>Take Away</option>
            <option value="delivery" <?= $data['metode_pengiriman']=='delivery'?'selected':'' ?>>Delivery</option>
        </select>

        <label>Status</label>
        <select name="status">
            <option value="Diproses" <?= $data['status']=='Diproses'?'selected':'' ?>>Diproses</option>
            <option value="Dikirim" <?= $data['status']=='Dikirim'?'selected':'' ?>>Dikirim</option>
            <option value="Selesai" <?= $data['status']=='Selesai'?'selected':'' ?>>Selesai</option>
        </select>

        <button type="submit" class="btn-submit">Simpan Perubahan</button>
    </form>
</div>
</body>
</html>
