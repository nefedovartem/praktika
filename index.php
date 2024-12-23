<?php
session_start(); // Инициализируем сессию
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db_index.php'; // Подключаемся к базе данных

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Инициализация массива избранного, если он еще не создан
if (!isset($_SESSION['favorites'])) {
    $_SESSION['favorites'] = [];
}

// Обработка добавления в избранное
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_favorites'])) {
    $equipmentId = $_POST['equipment_id'];
    
    // Проверяем, есть ли уже этот элемент в избранном
    if (!in_array($equipmentId, $_SESSION['favorites'])) {
        $_SESSION['favorites'][] = $equipmentId; // Добавляем в избранное
        echo "<script>alert('Техника добавлена в избранное!');</script>";
    } else {
        echo "<script>alert('Эта техника уже в избранном!');</script>";
    }
}

// Получаем данные из базы данных
$search = $_GET['search'] ?? '';

if ($search) {
    $stmt = $pdo->prepare("SELECT * FROM military_equipment WHERE name LIKE :search OR country LIKE :search OR description LIKE :search");
    $stmt->execute(['search' => "%$search%"]);
    $searchResults = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $equipment = [];
    foreach ($searchResults as $item) {
        $equipment[$item['category']][] = $item;
    }
    $categories = array_unique(array_column($searchResults, 'category'));
} else {
    $stmt = $pdo->query("SELECT DISTINCT category FROM military_equipment");
    $categories = $stmt->fetchAll(PDO::FETCH_COLUMN);

    $equipment = [];
    foreach ($categories as $category) {
        $stmt = $pdo->prepare("SELECT * FROM military_equipment WHERE category = :category");
        $stmt->execute(['category' => $category]);
        $equipment[$category] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Справочник военной техники - ИС-215 Нефёдов А.Д.</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <style>

    .search-form {
    margin-bottom: 20px;
    display: flex;
    justify-content: center;
    }

    .search-form input[type="text"] {
    width: 70%;
    padding: 10px;
    font-size: 16px;
    border: 1px solid #ddd;
    border-radius: 4px 0 0 4px;
    }

    .search-form button {
    padding: 10px 20px;
    font-size: 16px;
    background-color: #4a4a4a;
    color: white;
    border: none;
    border-radius: 0 4px 4px 0;
    cursor: pointer;
    }

    .search-form button:hover {
    background-color: #333333;
    }

    body {
        font-family: 'Roboto', sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f0f0f0;
        color: #333;
        background-image: url(fon_tanki.jpg);
        background-size: cover;
        background-attachment: fixed;
    }

    .logout-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        padding: 8px 15px;
        background-color: #e74c3c;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        font-size: 14px;
        transition: background-color 0.3s;
    }

    .logout-btn:hover {
    background-color: #c0392b;
    }

    .header {
    position: relative;
    }

    .header {
        background-color: rgba(44, 62, 80, 0.8);
        color: white;
        text-align: center;
        padding: 1rem 0;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
    }
    .category {
        background-color: rgba(255, 255, 255, 0.8);
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
        overflow: hidden;
        backdrop-filter: blur(5px);
    }
    .category-header {
        background-color: rgba(74, 74, 74, 0.8);
        color: white;
        padding: 1rem;
        font-size: 1.2rem;
        font-weight: bold;
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0,0,0,0.4);
    }

    .modal-content {
        background-color: #fefefe;
        margin: 15% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        max-width: 500px;
    }

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}

#feedbackBtn {
    position: fixed;
    bottom: 20px;
    right: 20px;
    padding: 10px 20px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

#feedbackForm textarea {
    width: 100%;
    padding: 10px;
    margin-bottom: 10px;
}

    .equipment-list {
        list-style-type: none;
        padding: 0;
    }
    .equipment-item {
        padding: 1rem;
        border-bottom: 1px solid rgba(238, 238, 238, 0.5);
    }
    .equipment-item:last-child {
        border-bottom: none;
    }
    .equipment-name {
        font-weight: bold;
        color: #2c3e50;
        margin-bottom: 0.5rem;
    }
    .equipment-description {
        color: #333;
    }
    .equipment-country {
        font-style: italic;
        color: #34495e;
        margin-top: 0.5rem;
    }
    @media (max-width: 768px) {
        .container {
            padding: 1rem;
        }
    }

    .equipment-link {
    text-decoration: none;
    color: inherit;
    display: block;
    transition: background-color 0.3s;
    }

    .equipment-link:hover {
    background-color: rgba(52, 152, 219, 0.1);
    }
    
    button {
    background-color: #4CAF50; /* Зеленый цвет */
    color: white; /* Белый текст */
    border: none; /* Убираем границу */
    padding: 10px 15px; /* Отступы */
    text-align: center; /* Центрируем текст */
    text-decoration: none; /* Убираем подчеркивание */
    display: inline-block; /* Отображаем как блок */
    font-size: 16px; /* Размер шрифта */
    margin: 4px 2px; /* Отступы */
    cursor: pointer; /* Указатель при наведении */
    border-radius: 5px; /* Скругленные углы */
    transition: background-color 0.3s; /* Плавный переход цвета */
    }

    button:hover {
    background-color: #45a049; /* Темный зеленый при наведении */
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
    <h1>Справочник военной техники</h1>
    <p>ИС-215 Нефёдов А.Д.</p>
    <a href="logout.php" class="logout-btn">Выйти</a>
</header>
<div class="favorites-link">
    <a href="favorites.php" class="favorites-btn">Избранное</a>
</div>

    <div class="container">
    <form class="search-form" action="" method="GET">
        <input type="text" name="search" placeholder="Поиск по названию, стране или описанию..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        <button type="submit">Поиск</button>
    </form>
        <?php foreach ($categories as $category): ?>
            <div class="category">
                <div class="category-header"><?= htmlspecialchars($category) ?></div>
                <ul class="equipment-list">
                <?php foreach ($equipment[$category] as $item): ?>
    <li class="equipment-item">
        <a href="equipment_details.php?id=<?= $item['id'] ?>" class="equipment-link">
            <div class="equipment-name"><?= htmlspecialchars($item['name']) ?></div>
            <div class="equipment-description"><?= htmlspecialchars(substr($item['description'], 0, 100)) ?>...</div>
            <div class="equipment-country">Страна: <?= htmlspecialchars($item['country']) ?></div>
        </a>
        <form action="" method="POST" style="display:inline;">
        <input type="hidden" name="equipment_id" value="<?= $item['id'] ?>">
        <button type="submit" name="add_to_favorites">В избранное</button>
    </form>
    </li>
<?php endforeach; ?>
                </ul>
            </div>
        <?php endforeach; ?>
    </div>
    <div id="feedbackModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Сообщить об ошибке</h2>
        <form id="feedbackForm">
            <textarea id="feedbackText" rows="4" placeholder="Опишите ошибку..." required></textarea>
            <button type="submit">Отправить</button>
        </form>
    </div>
</div>

<button id="feedbackBtn">Нашли ошибку?</button>

<script>
    // Получаем элементы модального окна
    var modal = document.getElementById("feedbackModal");
    var btn = document.getElementById("feedbackBtn");
    var span = document.getElementsByClassName("close")[0];
    var form = document.getElementById("feedbackForm");

    // Открываем модальное окно при нажатии на кнопку
    btn.onclick = function() {
        modal.style.display = "block";
    }

    // Закрываем модальное окно при нажатии на (x)
    span.onclick = function() {
        modal.style.display = "none";
    }

    // Закрываем модальное окно при клике вне его
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    // Отправка формы
    form.onsubmit = function(e) {
        e.preventDefault();
        var feedbackText = document.getElementById("feedbackText").value;
        
        // Отправка данных на сервер
        fetch('send_feedback.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'feedback=' + encodeURIComponent(feedbackText)
        })
        .then(response => response.text())
        .then(data => {
            alert('Спасибо за ваш отзыв!');
            modal.style.display = "none";
            form.reset();
        })
        .catch((error) => {
            console.error('Error:', error);
            alert('Произошла ошибка при отправке отзыва.');
        });
    }
</script>
</body>
</html>