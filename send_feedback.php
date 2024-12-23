<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $feedback = $_POST['feedback'];
    
    // Подключение к базе данных
    $server = "134.90.167.42:10306";
    $user = "Nefedov";
    $pw = "5bqYdI";
    $db = "project_Nefedov";

    $connect = mysqli_connect($server, $user, $pw, $db);

    if (!$connect) {
        die("Ошибка подключения: " . mysqli_connect_error());
    }

    // Подготовленный запрос для вставки отзыва
    $query = "INSERT INTO `feedback` (text, status) VALUES (?, 'new')";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, "s", $feedback);
    
    if (mysqli_stmt_execute($stmt)) {
        echo "Отзыв успешно отправлен";
    } else {
        echo "Ошибка при отправке отзыва: " . mysqli_error($connect);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($connect);
}
?>