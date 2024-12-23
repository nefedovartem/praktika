<?php
header('Content-Type: text/html; charset=utf-8');
mb_internal_encoding("UTF-8");
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db_index.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    die('ID не указан');
}

$stmt = $pdo->prepare("SELECT * FROM military_equipment WHERE id = :id");
$stmt->execute(['id' => $id]);
$equipment = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$equipment) {
    die('Техника не найдена');
}

// Получаем дополнительные изображения
$stmt = $pdo->prepare("SELECT image_url FROM equipment_images WHERE equipment_id = :id");
$stmt->execute(['id' => $id]);
$images = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Получаем технические характеристики
$stmt = $pdo->prepare("SELECT * FROM equipment_specs WHERE equipment_id = :id");
$stmt->execute(['id' => $id]);
$specs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($equipment['name']) ?> - Детали</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
    body {
        font-family: 'Roboto', sans-serif;
        background-color: #2c3e50;
        margin: 0;
        padding: 20px;
        color: #ecf0f1;
        line-height: 1.6;
    }

    .container {
        max-width: 800px;
        margin: 0 auto;
        background-color: rgba(44, 62, 80, 0.7);
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
    }

    h1, h2 {
        color: #3498db;
        margin-bottom: 20px;
        border-bottom: 2px solid #3498db;
        padding-bottom: 10px;
    }

    .equipment-details, .equipment-images, .specs-table {
        margin-bottom: 30px;
    }

    .equipment-images {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
    }

    .equipment-images img {
        max-width: 48%;
        height: auto;
        margin-bottom: 20px;
        border-radius: 4px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        transition: transform 0.3s ease;
    }

    .equipment-images img:hover {
        transform: scale(1.05);
    }

    .specs-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 10px;
    }

    .specs-table th, .specs-table td {
        padding: 15px;
        background-color: rgba(52, 73, 94, 0.7);
        text-align: left;
    }

    .specs-table th {
        background-color: rgba(41, 128, 185, 0.7);
        color: #ecf0f1;
        font-weight: bold;
        text-transform: uppercase;
    }

    .specs-table tr:hover td {
        background-color: rgba(52, 73, 94, 0.9);
    }

    .back-link {
        display: inline-block;
        margin-top: 20px;
        padding: 12px 20px;
        background-color: #3498db;
        color: white;
        text-decoration: none;
        border-radius: 4px;
        transition: background-color 0.3s, transform 0.3s;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .back-link:hover {
        background-color: #2980b9;
        transform: translateY(-2px);
    }

    .equipment-details p {
        background-color: rgba(52, 73, 94, 0.7);
        padding: 15px;
        border-radius: 4px;
        margin-bottom: 10px;
    }

    .equipment-details strong {
        color: #3498db;
    }
</style>
</head>
<body>
    <h1><?= htmlspecialchars($equipment['name']) ?></h1>
    
    <div class="equipment-details">
        <p><strong>Страна:</strong> <?= htmlspecialchars($equipment['country']) ?></p>
        <p><strong>Описание:</strong> <?= nl2br(htmlspecialchars($equipment['description'])) ?></p>
    </div>

    <h2>Изображения</h2>
    <div class="equipment-images">
        <?php foreach ($images as $image): ?>
            <img src="<?= htmlspecialchars($image) ?>" alt="<?= htmlspecialchars($equipment['name']) ?>">
        <?php endforeach; ?>
    </div>

    <h2>Технические характеристики</h2>
    <table class="specs-table">
        <tr>
            <th>Характеристика</th>
            <th>Значение</th>
        </tr>
        <?php foreach ($specs as $spec): ?>
            <tr>
                <td><?= htmlspecialchars($spec['spec_name']) ?></td>
                <td><?= htmlspecialchars($spec['spec_value']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <a href="index.php" class="back-link">Вернуться к списку</a>
</body>
</html>