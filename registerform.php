<!DOCTYPE html>
<html lang="RU">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form login and password</title>
    <link rel="stylesheet" href="style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 5px;
        }

        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }

        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }
    </style>
</head>
<body>
    <a href="https://kuzstu.ru/">
        <img src="https://kuzstu.ru/application/frontend/skin/default/assets/images/emblem.png" width="300" height="300" width="600">
    </a>
    <div class="wrapper">
        <form action="for_register.php" method="post">
            <h1>Регистрация</h1>
            <div id="message" style="display:none;"></div>
            <div class="input-box">
                <input type="text" placeholder="Имя пользователя" name="username" required>
                <i class='bx bx-user'></i>
            </div>
            <div class="input-box">
                <input type="password" placeholder="Пароль" name="password" required>
                <i class='bx bxs-lock-alt'></i>
            </div>
            <div class="input-box">
                <input type="password" placeholder="Повторите пароль" name="repass" required>
                <i class='bx bxs-lock-alt'></i>
            </div>
            <button type="submit" class="btn">Зарегистрироваться</button>
        </form>
    </div>
</body>
</html>