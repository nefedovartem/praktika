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

$id = $_GET['id'] ?? 0;

// Получение данных об оборудовании
$query = "SELECT * FROM `military_equipment` WHERE `id` = ?";
$stmt = mysqli_prepare($connect, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$equipment = mysqli_fetch_assoc($result);

// Обработка формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

// Добавление нового изображения
if (!empty($_POST['new_image_url'])) {
    $new_image_url = $_POST['new_image_url'];
    $query = "INSERT INTO `equipment_images` (`equipment_id`, `image_url`) VALUES (?, ?)";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, "is", $id, $new_image_url);
    mysqli_stmt_execute($stmt);
}

// Удаление изображения
if (isset($_POST['delete_image_id'])) {
    $delete_image_id = $_POST['delete_image_id'];
    $query = "DELETE FROM `equipment_images` WHERE `id` = ? AND `equipment_id` = ?";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, "ii", $delete_image_id, $id);
    mysqli_stmt_execute($stmt);
}

    // Обновление основной информации
    $name = $_POST['name'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $country = $_POST['country'];

    $query = "UPDATE `military_equipment` SET `name` = ?, `description` = ?, `category` = ?, `country` = ? WHERE `id` = ?";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, "ssssi", $name, $description, $category, $country, $id);
    mysqli_stmt_execute($stmt);

    // Обработка загрузки изображения
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $query = "UPDATE `military_equipment` SET `image` = ? WHERE `id` = ?";
            $stmt = mysqli_prepare($connect, $query);
            mysqli_stmt_bind_param($stmt, "si", $target_file, $id);
            mysqli_stmt_execute($stmt);
        }
    }

    // Обновление характеристик
if (isset($_POST['specs'])) {
    foreach ($_POST['specs'] as $spec_id => $value) {
        $query = "UPDATE `equipment_specs` SET `spec_value` = ? WHERE `id` = ? AND `equipment_id` = ?";
        $stmt = mysqli_prepare($connect, $query);
        mysqli_stmt_bind_param($stmt, "sii", $value, $spec_id, $id);
        mysqli_stmt_execute($stmt);
    }
}

// Добавление новых характеристик
if (!empty($_POST['new_spec_name']) && !empty($_POST['new_spec_value'])) {
    $new_spec_names = $_POST['new_spec_name'];
    $new_spec_values = $_POST['new_spec_value'];
    
    $query = "INSERT INTO `equipment_specs` (`equipment_id`, `spec_name`, `spec_value`) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($connect, $query);
    
    for ($i = 0; $i < count($new_spec_names); $i++) {
        if (!empty($new_spec_names[$i]) && !empty($new_spec_values[$i])) {
            mysqli_stmt_bind_param($stmt, "iss", $id, $new_spec_names[$i], $new_spec_values[$i]);
            mysqli_stmt_execute($stmt);
        }
    }
}

    // Перенаправление обратно на страницу редактирования
    header("Location: edit_equipment.php?id=$id");
    exit();
}

// Получение характеристик оборудования
$query = "SELECT * FROM `equipment_specs` WHERE `equipment_id` = ?";
$stmt = mysqli_prepare($connect, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$spec_result = mysqli_stmt_get_result($stmt);
$specs = mysqli_fetch_all($spec_result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактирование оборудования</title>
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
            background-color: #ffc107;
        }
        .edit-button:hover {
            background-color: #e0a800;
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
    <h1>Редактирование оборудования</h1>
    <form method="POST" enctype="multipart/form-data">
        <label for="name">Название:</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($equipment['name']) ?>" required>

        <label for="description">Описание:</label>
        <textarea id="description" name="description" required style="width: 100%; height: 100px; margin-bottom: 10px; padding: 5px;"><?= htmlspecialchars($equipment['description']) ?></textarea>

        <label for="category">Категория:</label>
        <input type="text" id="category" name="category" value="<?= htmlspecialchars($equipment['category']) ?>" required>

        <label for="country">Страна:</label>
        <input type="text" id="country" name="country" value="<?= htmlspecialchars($equipment['country']) ?>" required>

        <label for="image_url">URL изображения:</label>
        <input type="text" id="image_url" name="image_url" value="<?= htmlspecialchars($equipment['image']) ?>" style="width: 100%; margin-bottom: 10px;">

<?php
// Получение существующих изображений
$query = "SELECT * FROM `equipment_images` WHERE `equipment_id` = ?";
$stmt = mysqli_prepare($connect, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$images_result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($images_result) > 0) {
    echo "<h3>Существующие изображения:</h3>";
    while ($image = mysqli_fetch_assoc($images_result)) {
        echo "<div>";
        echo "<img src='" . htmlspecialchars($image['image_url']) . "' alt='Изображение' style='max-width: 200px; margin-right: 10px;'>";
        echo "<form method='POST' style='display: inline;'>";
        echo "<input type='hidden' name='delete_image_id' value='" . $image['id'] . "'>";
        echo "<button type='submit' class='delete-button'>Удалить</button>";
        echo "</form>";
        echo "</div>";
    }
}
?>

<h3>Добавить новое изображение</h3>
<input type="text" name="new_image_url" placeholder="Введите URL нового изображения" style="width: 100%; margin-bottom: 10px;">

        <h2>Характеристики</h2>
<?php foreach ($specs as $spec): ?>
    <label for="spec_<?= $spec['id'] ?>"><?= htmlspecialchars($spec['spec_name']) ?>:</label>
    <input type="text" id="spec_<?= $spec['id'] ?>" name="specs[<?= $spec['id'] ?>]" value="<?= htmlspecialchars($spec['spec_value']) ?>">
<?php endforeach; ?>

<h3>Добавить новые характеристики</h3>
<div id="new-specs-container">
    <div class="new-spec">
        <input type="text" name="new_spec_name[]" placeholder="Название характеристики">
        <input type="text" name="new_spec_value[]" placeholder="Значение">
    </div>
</div>
<button type="button" id="add-spec">Добавить еще характеристику</button>

<script>
document.getElementById('add-spec').addEventListener('click', function() {
    var container = document.getElementById('new-specs-container');
    var newSpec = document.createElement('div');
    newSpec.className = 'new-spec';
    newSpec.innerHTML = `
        <input type="text" name="new_spec_name[]" placeholder="Название характеристики">
        <input type="text" name="new_spec_value[]" placeholder="Значение">
    `;
    container.appendChild(newSpec);
});
</script>

        <button type="submit">Сохранить изменения</button>
    </form>
    <a href="developer_inter.php" class="button">Вернуться к списку</a>
</div>
</body>
</html>