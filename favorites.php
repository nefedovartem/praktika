<?php
session_start(); // Инициализируем сессию
include 'db_index.php'; // Подключаемся к базе данных

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Получаем избранные элементы
$favorites = $_SESSION['favorites'] ?? [];

$equipment = [];
if (!empty($favorites)) {
    // Получаем информацию о технике из базы данных по ID
    $placeholders = implode(',', array_fill(0, count($favorites), '?'));
    $stmt = $pdo->prepare("SELECT * FROM military_equipment WHERE id IN ($placeholders)");
    $stmt->execute($favorites);
    $equipment = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Избранное - ИС-215 Нефёдов А.Д.</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        /* Ваши стили */
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            background-image: url(fon_tanki.jpg);
            background-size: cover;
            background-attachment: fixed;
        }

        .header {
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
            text-align: center;
        }

        .container {
            margin: 20px auto;
            max-width: 800px;
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .equipment-list {
            list-style: none;
            padding: 0;
        }

        .equipment-item {
            border-bottom: 1px solid #ddd;
            padding: 10px 0;
        }

        .equipment-link {
            text-decoration: none;
            color: #333;
        }

        .equipment-name {
            font-weight: bold;
        }

        .equipment-description {
            color: #666;
        }

        .equipment-country {
            font-style: italic;
        }

        .favorites-link {
            text-align: center;
            margin: 20px 0;
        }

        .favorites-btn {
            background-color: #2196F3; /* Синий цвет */
            color: white; /* Белый текст */
            padding: 10px 15px; /* Отступы */
            text-decoration: none; /* Убираем подчеркивание */
            border-radius: 5px; /* Скругленные углы */
            transition: background-color 0.3s; /* Плавный переход цвета */
        }

        .favorites-btn:hover {
            background-color: #0b7dda; /* Темный синий при наведении */
        }
    </style>
</head>
<body>
<header class="header">
    <h1>Избранное</h1>
    <p>ИС-215 Нефёдов А.Д.</p>
    <a href="logout.php" class="logout-btn">Выйти</a>
</header>

<div class="favorites-link">
    <a href="favorites.php" class="favorites-btn">Избранное</a>
</div>

<div class="container">
    <?php if (empty($equipment)): ?>
        <p>Ваши избранные записи пусты.</p>
    <?php else: ?>
        <ul class="equipment-list">
            <?php foreach ($equipment as $item): ?>
                <li class="equipment-item">
                    <a href="equipment_details.php?id=<?= $item['id'] ?>" class="equipment-link">
                        <div class="equipment-name"><?= htmlspecialchars($item['name']) ?></div>
                        <div class="equipment-description"><?= htmlspecialchars(substr($item['description'], 0, 100)) ?>...</div>
                        <div class="equipment-country">Страна: <?= htmlspecialchars($item['country']) ?></div>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>
</body>
</html>