<!DOCTYPE html>
<html lang="RU">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form login and password</title>
    <link rel="stylesheet" href="style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <a href="https://kuzstu.ru/">
        <img src="https://kuzstu.ru/application/frontend/skin/default/assets/images/emblem.png" width="300" height="300">
    </a>
    <div class="wrapper">
        <form action="login.php" method="POST">
            <h1>Авторизация</h1>
            <div class="input-box">
    <input type="text" placeholder="Имя пользователя" name="login" required>
    <i class='bx bx-user'></i> <!-- Значок для имени пользователя -->
</div>
<div class="input-box">
    <input type="password" placeholder="Пароль" name="pass" required>
    <i class='bx bxs-lock-alt'></i> <!-- Значок для пароля -->
</div>
<div class="input-box">
    <label for="role">Выберите роль:</label>
    <select id="role" name="role" required>
        <option value="" disabled selected>-- Выберите роль --</option>
        <option value="admin">Администратор</option>
        <option value="developer">Разработчик</option>
        <option value="moderator">Модератор</option>
        <option value="reader">Читатель</option>
    </select>
</div>

            <button type="submit" class="btn">Войти</button>

            <div class="register-link">
                <p>Нет аккаунта? <a href="registerform.php">Создать</a></p>
            </div>
        </form>
    </div>
</body>
</html>
