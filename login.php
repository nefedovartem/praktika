<?php
require_once 'bd.php'; // Подключение к базе данных

// Получаем данные из формы
$login = $_POST['login'] ?? false; // Имя пользователя
$pass = $_POST['pass'] ?? false; // Пароль
$role = $_POST['role'] ?? false; // Роль

// Проверка на пустые поля
if (empty($login) || empty($pass) || empty($role)) {
    echo "Заполните все поля";
} else {
    // Подготовленный запрос для защиты от SQL-инъекций
    $stmt = $connect->prepare("SELECT * FROM `users` WHERE username = ? AND role = ?");
    $stmt->bind_param("ss", $login, $role); // Привязываем параметры
    $stmt->execute();
    $result = $stmt->get_result();

    // Проверка на наличие пользователя
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc(); // Получаем данные пользователя

        // Проверка пароля
        if (password_verify($pass, $row['password'])) {
            // Проверка роли и перенаправление
            if ($row["role"] == 'admin') {
                header('Location: admin_inter.php'); // Перенаправление на панель администратора
                exit(); // Завершаем выполнение скрипта
            } else if ($row['role'] == 'developer') {
                header('Location: developer_inter.php'); // Перенаправление на панель разработчика
                exit();
            } else if ($row['role'] == 'moderator') {
                header('Location: moderator_inter.php'); // Перенаправление на панель модератора
                exit();
            } else if ($row['role'] == 'reader') {
                header('Location: index.php'); // Перенаправление на панель читателя
                exit();
            }
        } else {
            echo "<h1>Неверный пароль</h1>";
        }
    } else {
        echo "<h1>Такого пользователя не существует</h1>";
    }
}
