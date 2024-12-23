<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $status = $_POST['status'];
    
    // Подключение к базе данных
    $server = "134.90.167.42:10306";
    $user = "Nefedov";
    $pw = "5bqYdI";
    $db = "project_Nefedov";

    $connect = mysqli_connect($server, $user, $pw, $db);

    if (!$connect) {
        die("Ошибка подключения: " . mysqli_connect_error());
    }

    // Подготовленный запрос для обновления статуса отзыва
    $query = "UPDATE `feedback` SET status = ? WHERE id = ?";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, "si", $status, $id);
    
    if (mysqli_stmt_execute($stmt)) {
        echo "Статус отзыва успешно обновлен";
    } else {
        echo "Ошибка при обновлении статуса отзыва: " . mysqli_error($connect);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($connect);
}
?>
