<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Панель разработчика</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #e9ecef;
            margin: 0;
            padding: 20px;
            color: #343a40;
        }

        h1 {
            text-align: center;
            color: #007bff;
            margin-bottom: 20px;
        }

        h2 {
            margin-top: 20px;
            color: #495057;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 15px;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s, transform 0.2s;
        }

        button:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        input[type="text"], select {
            padding: 10px;
            width: calc(100% - 22px);
            margin-bottom: 10px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            box-sizing: border-box;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #dee2e6;
            box-sizing: border-box;
        }

        th {
            background-color: #007bff;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .message {
            display: none;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .delete-button {
            background-color: #dc3545;
            margin-left: 10px;
        }
        .delete-button:hover {
            background-color: #c82333;
        }

        .edit-button {
            background-color: #28a745;
            color: white;
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 3px;
            margin-right: 5px;
        }

        .edit-button:hover {
            background-color: #218838;
        }

        .logout-button {
            background-color: #6c757d;
            margin-top: 20px;
        }
        .logout-button:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Панель разработчика</h1>

    <div id="message" class="message"></div>

    <h2>Добавить оборудование</h2>
    <form method="POST">
        <input type="text" id="name" name="name" placeholder="Введите название" required>
        <input type="text" id="description" name="description" placeholder="Введите описание" required>
        <input type="text" id="category" name="category" placeholder="Введите категорию" required>
        <input type="text" id="country" name="country" placeholder="Введите страну" required>
        <button type="submit">Добавить</button>
    </form>

    <h2>Существующие данные</h2>
    <table id="dataTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Название</th>
                <th>Описание</th>
                <th>Категория</th>
                <th>Страна</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
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

            // Запрос для получения данных
            $query = "SELECT * FROM `military_equipment`";
            $result = mysqli_query($connect, $query);

            if ($result) {
                // Вывод данных в таблицу
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['category']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['country']) . "</td>";
                    echo "<td>
                        <a href='edit_equipment.php?id=" . htmlspecialchars($row['id']) . "' class='edit-button'>Подробнее</a>
                        <form method='POST' style='display:inline;'>
                            <input type='hidden' name='delete_id' value='" . htmlspecialchars($row['id']) . "'>
                            <button type='submit' class='delete-button'>Удалить</button>
                        </form>
                    </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>Ошибка при получении данных: " . mysqli_error($connect) . "</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
<h2>Выход</h2>
<button class="logout-button" id="logout">Выйти</button>
<script>
  document.getElementById('logout').addEventListener('click', function() {
    // Отправляем POST-запрос на logout.php и перенаправляем пользователя на страницу входа
    fetch('logout.php', { method: 'POST' }).then(response => response.text()).then(data => {
      window.location.href = 'avt.php';
    });
  });
</script>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_id'])) {
        // Удаление оборудования
        $delete_id = $_POST['delete_id'];

        // Подготовленный запрос для удаления данных
        $query = "DELETE FROM `military_equipment` WHERE `id` = ?";
        $stmt = mysqli_prepare($connect, $query);
        mysqli_stmt_bind_param($stmt, "i", $delete_id);
        
        if (mysqli_stmt_execute($stmt)) {
            echo '<script>document.getElementById("message").innerHTML = "Оборудование успешно удалено"; document.getElementById("message").className = "message success"; document.getElementById("message").style.display = "block";</script>';
        } else {
            echo '<script>document.getElementById("message").innerHTML = "Ошибка при удалении: ' . mysqli_error($connect) . '"; document.getElementById("message").className = "message error"; document.getElementById("message").style.display = "block";</script>';
        }

        mysqli_stmt_close($stmt);
    } elseif (isset($_POST['edit_id'])) {
        // Редактирование оборудования
        $edit_id = $_POST['edit_id'];
        $edit_name = $_POST['edit_name'];
        $edit_description = $_POST['edit_description'];
        $edit_category = $_POST['edit_category'];
        $edit_country = $_POST['edit_country'];

        // Подготовленный запрос для обновления данных
        $query = "UPDATE `military_equipment` SET `name` = ?, `description` = ?, `category` = ?, `country` = ? WHERE `id` = ?";
        $stmt = mysqli_prepare($connect, $query);
        mysqli_stmt_bind_param($stmt, "ssssi", $edit_name, $edit_description, $edit_category, $edit_country, $edit_id);
        
        if (mysqli_stmt_execute($stmt)) {
            echo '<script>document.getElementById("message").innerHTML = "Оборудование успешно обновлено"; document.getElementById("message").className = "message success"; document.getElementById("message").style.display = "block";</script>';
        } else {
            echo '<script>document.getElementById("message").innerHTML = "Ошибка при обновлении: ' . mysqli_error($connect) . '"; document.getElementById("message").className = "message error"; document.getElementById("message").style.display = "block";</script>';
        }

        mysqli_stmt_close($stmt);
    } else {
        // Добавление оборудования
        $name = $_POST['name'];
        $description = $_POST['description'];
        $category = $_POST['category'];
        $country = $_POST['country'];

        // Проверка на пустые значения
        if (empty($name) || empty($description) || empty($category) || empty($country)) {
            echo '<script>document.getElementById("message").innerHTML = "Пожалуйста, заполните все поля."; document.getElementById("message").className = "message error"; document.getElementById("message").style.display = "block";</script>';
            exit;
        }

        // Подготовленный запрос для вставки данных
        $query = "INSERT INTO `military_equipment`(`name`, `description`, `category`, `country`) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($connect, $query);
        mysqli_stmt_bind_param($stmt, "ssss", $name, $description, $category, $country);
        
        if (mysqli_stmt_execute($stmt)) {
            echo '<script>document.getElementById("message").innerHTML = "Успешно добавлено"; document.getElementById("message").className = "message success"; document.getElementById("message").style.display = "block";</script>';
        } else {
            echo '<script>document.getElementById("message").innerHTML = "Ошибка при добавлении: ' . mysqli_error($connect) . '"; document.getElementById("message").className = "message error"; document.getElementById("message").style.display = "block";</script>';
        }

        mysqli_stmt_close($stmt);
    }
}

mysqli_close($connect);
?>

</body>
</html>