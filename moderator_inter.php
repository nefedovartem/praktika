<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Панель модератора</title>
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
        .feedback-item {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 15px;
        }
        .feedback-text {
            margin-bottom: 10px;
        }
        .feedback-status {
            font-weight: bold;
            margin-bottom: 10px;
        }
        .feedback-actions button {
            margin-right: 10px;
        }

        .search-filter, .sort {
            margin-bottom: 20px;
        }
        #pagination {
            text-align: center;
            margin-top: 20px;
        }
        #pagination button {
            margin: 0 5px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Панель модератора</h1>

    <div id="message" class="message"></div>

    <h2>Отзывы пользователей</h2>
    <div class="search-filter">
        <input type="text" id="searchInput" placeholder="Поиск по тексту отзыва">
        <select id="statusFilter">
            <option value="">Все статусы</option>
            <option value="new">Новый</option>
            <option value="resolved">Решено</option>
            <option value="rejected">Отклонено</option>
        </select>
        <button onclick="searchAndFilterFeedback()">Применить</button>
    </div>
    <div class="sort">
        <label for="sortCriteria">Сортировать по:</label>
        <select id="sortCriteria" onchange="sortFeedback()">
            <option value="date_desc">Дате (сначала новые)</option>
            <option value="date_asc">Дате (сначала старые)</option>
            <option value="status">Статусу</option>
        </select>
    </div>
    <div id="feedbackList">
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

        // Запрос для получения отзывов
        $query = "SELECT * FROM `feedback` ORDER BY id DESC";
        $result = mysqli_query($connect, $query);

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='feedback-item' data-id='" . $row['id'] . "' data-status='" . $row['status'] . "'>";
                echo "<div class='feedback-text' contenteditable='true'>" . htmlspecialchars($row['text']) . "</div>";
                echo "<div class='feedback-status'>Статус: " . htmlspecialchars($row['status']) . "</div>";
                echo "<div class='feedback-actions'>";
                echo "<button onclick='updateFeedbackStatus(" . $row['id'] . ", \"resolved\")'>Решено</button>";
                echo "<button onclick='updateFeedbackStatus(" . $row['id'] . ", \"rejected\")'>Отклонено</button>";
                echo "<button onclick='saveFeedbackEdit(" . $row['id'] . ")'>Сохранить изменения</button>";
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<p>Ошибка при получении отзывов: " . mysqli_error($connect) . "</p>";
        }

        mysqli_close($connect);
        ?>
    </div>
    <div id="pagination"></div>
</div>

<h2>Выход</h2>
<button class="logout-button" id="logout">Выйти</button>

<script>
function updateFeedbackStatus(id, status) {
    fetch('update_feedback.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'id=' + id + '&status=' + status
    })
    .then(response => response.text())
    .then(data => {
        alert(data);
        location.reload(); // Перезагрузка страницы для обновления списка отзывов
    })
    .catch((error) => {
        console.error('Error:', error);
        alert('Произошла ошибка при обновлении статуса.');
    });
}

document.getElementById('logout').addEventListener('click', function() {
    fetch('logout.php', { method: 'POST' }).then(response => response.text()).then(data => {
        window.location.href = 'avt.php';
    });
});

function searchAndFilterFeedback() {
    const searchText = document.getElementById('searchInput').value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value;
    const feedbackItems = document.querySelectorAll('.feedback-item');

    feedbackItems.forEach(item => {
        const text = item.querySelector('.feedback-text').textContent.toLowerCase();
        const status = item.dataset.status;
        const matchesSearch = text.includes(searchText);
        const matchesStatus = statusFilter === '' || status === statusFilter;

        if (matchesSearch && matchesStatus) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });

    updatePagination();
}

function sortFeedback() {
    const sortCriteria = document.getElementById('sortCriteria').value;
    const feedbackList = document.getElementById('feedbackList');
    const feedbackItems = Array.from(feedbackList.querySelectorAll('.feedback-item'));

    feedbackItems.sort((a, b) => {
        if (sortCriteria === 'date_desc') {
            return b.dataset.id - a.dataset.id;
        } else if (sortCriteria === 'date_asc') {
            return a.dataset.id - b.dataset.id;
        } else if (sortCriteria === 'status') {
            return a.dataset.status.localeCompare(b.dataset.status);
        }
    });

    feedbackItems.forEach(item => feedbackList.appendChild(item));
    updatePagination();
}

const itemsPerPage = 10;
let currentPage = 1;

function updatePagination() {
    const feedbackItems = document.querySelectorAll('.feedback-item');
    const visibleItems = Array.from(feedbackItems).filter(item => item.style.display !== 'none');
    const totalPages = Math.ceil(visibleItems.length / itemsPerPage);

    const paginationElement = document.getElementById('pagination');
    paginationElement.innerHTML = '';

    for (let i = 1; i <= totalPages; i++) {
        const button = document.createElement('button');
        button.textContent = i;
        button.onclick = () => goToPage(i);
        paginationElement.appendChild(button);
    }

    goToPage(1);
}

function goToPage(page) {
    currentPage = page;
    const feedbackItems = document.querySelectorAll('.feedback-item');
    const visibleItems = Array.from(feedbackItems).filter(item => item.style.display !== 'none');

    visibleItems.forEach((item, index) => {
        if (index >= (page - 1) * itemsPerPage && index < page * itemsPerPage) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
}

// Инициализация пагинации при загрузке страницы
document.addEventListener('DOMContentLoaded', updatePagination);
</script>
</body>
</html>