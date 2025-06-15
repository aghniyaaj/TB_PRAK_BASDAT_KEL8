<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>NextDoor Cafe</title>
    <!-- Google Font: Sour Gummy -->
    <link href="https://fonts.googleapis.com/css2?family=Sour+Gummy&display=swap" rel="stylesheet">
    <style>
        body, h1, h2, button {
    font-family: 'Sour Gummy', cursive;
    }
        body {
            margin: 0;
            padding: 0;
            background: url('bg.jpeg') no-repeat center center fixed;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            text-align: center;
            color:rgb(96, 174, 212);
        }
        .container {
            margin-top: 100px;
        }
        .logo {
            width: 250px;
            margin: 20px 0;
        }
        h1 {
            font-size: 36px;
            margin-bottom: 10px;
        }
        .menu-btn, .login-btn {
            background-color:rgb(241, 140, 182);
            color: white;
            border: none;
            padding: 15px 30px;
            font-size: 18px;
            border-radius: 30px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-family: 'Sour Gummy', cursive;
        }
        .menu-btn:hover, .login-btn:hover {
            background-color: #c07394;
        }
        .btn-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
            margin-top: 20px;
        }
        h1, h2 {
            margin: 10px;
        }
        
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome to</h1>
        <img src="logo.png" alt="NextDoor Logo" class="logo">
        <h2>CHECK OUR MENU</h2>
        <div class="btn-container">
            <a href="semua_menu.php"><button class="menu-btn">Menu</button></a>
            <a href="Admin/login.php"><button class="login-btn">Login Admin</button></a>
        </div>
    </div>
</body>
</html>