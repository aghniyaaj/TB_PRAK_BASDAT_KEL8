<!DOCTYPE html>
<html>
<head>
    <title>Login Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Sour+Gummy&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Sour Gummy', cursive;
            background-image: url('../bg1.jpeg');
            background-size: cover;
            background-position: center;
            margin: 0;
            padding: 0;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 30px;
            max-width: 400px;
            margin: 80px auto;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #f06292;
        }

        label, input {
            display: block;
            width: 100%;
            margin-bottom: 10px;
        }

        input[type="text"],
        input[type="password"] {
            padding: 15px;
            border: 2px solid #f8bbd0;
            border-radius: 10px;
            font-size: 14px;
        }

        input[type="submit"] {
            background-color: #f06292;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
        }

        .success-message {
            color: green;
            text-align: center;
            margin-bottom: 15px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if(isset($_GET['pesan']) && $_GET['pesan'] == 'order_berhasil'): ?>
            <p class="success-message">Pesanan berhasil dibuat! Silakan login sebagai admin.</p>
        <?php endif; ?>

        <form action="cek_login.php" method="POST">
            <h2>Login Admin</h2>
            <label>Username</label>
            <input type="text" name="username" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <input type="submit" value="Login">
        </form>
    </div>
</body>
</html>
