<?php
// Подключение к базе данных
$server = "134.90.167.42:10306";
$user = "Nefedov";
$pw = "5bqYdI";
$db = "project_Nefedov";

$connect = mysqli_connect($server, $user, $pw, $db);

if (!$connect) {
    die("Ошибка подключения: " . mysqli_connect_error());
}

// Проверка, была ли отправлена форма
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $repass = $_POST['repass'];

    // Проверка на пустые значения
    if (empty($username) || empty($password) || empty($repass)) {
        $message = "Пожалуйста, заполните все поля.";
        $message_type = "error";
        displayMessage($message, $message_type);
        exit;
    }

    // Проверка на совпадение паролей
    if ($password !== $repass) {
        $message = "Пароли не совпадают.";
        $message_type = "error";
        displayMessage($message, $message_type);
        exit;
    }

    // Хэширование пароля
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Установка роли по умолчанию
    $role = 'reader'; // Все новые пользователи будут читателями

    // Подготовленный запрос для вставки данных
    $query = "INSERT INTO `users`(`role`, `username`, `password`) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, "sss", $role, $username, $hashed_password);
    
    if (mysqli_stmt_execute($stmt)) {
        $message = "Регистрация успешна!";
        $message_type = "success";
        displayMessage($message, $message_type);
    } else {
        $message = "Ошибка при регистрации: " . mysqli_error($connect);
        $message_type = "error";
        displayMessage($message, $message_type);
    }

    mysqli_stmt_close($stmt);
}

mysqli_close($connect);

// Функция для отображения сообщения
function displayMessage($message, $type) {
    echo '
    <div class="alert alert-' . ($type === "success" ? 'success' : 'danger') . '" role="alert">
        ' . $message . '
    </div>
    <script>
        setTimeout(function() {
            window.location.href = "avt.php"; // Перенаправление через 3 секунды
        }, 3000);
    </script>
    ';
}
?>