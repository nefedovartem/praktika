<?php
// Подключение к базе данных
$connect = mysqli_connect("localhost/127.0.0.1", "admin", "admin", "praktika");
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

// Обработка добавления ресурса
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_resource'])) {
    $name = $_POST['resource_name'];
    $type = $_POST['resource_type'];
    $quantity = $_POST['resource_quantity'];

    $query = "INSERT INTO resurs (name, type, quantity) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, "sss", $name, $type, $quantity);
    if (mysqli_stmt_execute($stmt)) {
        echo '<script>alert("Ресурс успешно добавлен");</script>';
    } else {
        echo '<script>alert("Ошибка при добавлении ресурса: ' . mysqli_error($connect) . '");</script>';
    }
    mysqli_stmt_close($stmt);
}

// Обработка добавления товара
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $name = $_POST['product_name'];
    $price = $_POST['product_price'];
    $quantity = $_POST['product_quantity'];

    $query = "INSERT INTO tovar (name, price, quantity) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, "sss", $name, $price, $quantity);
    if (mysqli_stmt_execute($stmt)) {
        echo '<script>alert("Товар успешно добавлен");</script>';
    } else {
        echo '<script>alert("Ошибка при добавлении товара: ' . mysqli_error($connect) . '");</script>';
    }
    mysqli_stmt_close($stmt);
}

// Обработка добавления сотрудника
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_employee'])) {
    $name = $_POST['employee_name'];
    $surname = $_POST['employee_surname'];
    $patronymic = $_POST['employee_patronymic'];
    $specialization = $_POST['employee_specialization'];

    $query = "INSERT INTO personal (name, surname, patronymic, specialization) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, "ssss", $name, $surname, $patronymic, $specialization);
    if (mysqli_stmt_execute($stmt)) {
        echo '<script>alert("Сотрудник успешно добавлен");</script>';
    } else {
        echo '<script>alert("Ошибка при добавлении сотрудника: ' . mysqli_error($connect) . '");</script>';
    }
    mysqli_stmt_close($stmt);
}

// Обработка добавления клиента
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_client'])) {
    $name = $_POST['client_name'];
    $surname = $_POST['client_surname'];
    $patronymic = $_POST['client_patronymic'];
    $phone_number = $_POST['client_phone'];
    $id_product = $_POST['client_product_id'];
    $quantity_product = $_POST['client_product_quantity'];

    $query = "INSERT INTO client (name, surname, patronymic, phone_number, id_product, quantity_product) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, "ssssss", $name, $surname, $patronymic, $phone_number, $id_product, $quantity_product);
    if (mysqli_stmt_execute($stmt)) {
        echo '<script>alert("Клиент успешно добавлен");</script>';
    } else {
        echo '<script>alert("Ошибка при добавлении клиента: ' . mysqli_error($connect) . '");</script>';
    }
    mysqli_stmt_close($stmt);
}

// Вывод данных из таблицы ресурсов
$query = "SELECT * FROM resurs";
$result = mysqli_query($connect, $query);
$resources = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Вывод данных из таблицы товаров
$query = "SELECT * FROM tovar";
$result = mysqli_query($connect, $query);
$products = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Вывод данных из таблицы персонала
$query = "SELECT * FROM personal";
$result = mysqli_query($connect, $query);
$employees = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Вывод данных из таблицы клиентов
$query = "SELECT * FROM client";
$result = mysqli_query($connect, $query);
$clients = mysqli_fetch_all($result, MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Панель управления КФХ</title>
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
            color: #28a745;
            margin-bottom: 20px;
        }

        h2 {
            margin-top: 20px;
            color: #495057;
            border-bottom: 2px solid #28a745;
            padding-bottom: 10px;
        }

        button {
            background-color: #28a745;
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
            background-color: #218838;
            transform: scale(1.05);
        }

        input[type="text"], input[type="password"], input[type="number"], select {
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
            background-color: #28a745;
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

        .logout-button {
            background-color: #6c757d;
            margin-top: 20px;
        }

        .logout-button:hover {
            background-color: #5a6268;
        }

        .tab {
            overflow: hidden;
            border: 1px solid #ccc;
            background-color: #f1f1f1;
        }

        .tab button {
            background-color: inherit;
            float: left;
            border: none;
            outline: none;
            cursor: pointer;
            padding: 14px 16px;
            transition: 0.3s;
            font-size: 17px;
        }

        .tab button:hover {
            background-color: #ddd;
        }

        .tab button.active {
            background-color: #28a745;
            color: white;
        }

        .tabcontent {
            display: none;
            padding: 6px 12px;
            border: 1px solid #ccc;
            border-top: none;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Панель управления КФХ</h1>

    <div id="message" class="message"></div>

    <div class="tab">
        <button class="tablinks" onclick="openTab(event, 'Resources')">Ресурсы</button>
        <button class="tablinks" onclick="openTab(event, 'Products')">Товары</button>
        <button class="tablinks" onclick="openTab(event, 'Personnel')">Персонал</button>
        <button class="tablinks" onclick="openTab(event, 'Clients')">Клиенты</button>
    </div>

    <div id="Resources" class="tabcontent">
        <h2>Управление ресурсами</h2>
        <form method="POST">
            <input type="text" name="resource_name" placeholder="Название ресурса" required>
            <input type="number" name="resource_quantity" placeholder="Количество" required>
            <input type="text" name="resource_unit" placeholder="Единица измерения" required>
            <button type="submit" name="add_resource">Добавить ресурс</button>
        </form>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Название</th>
                    <th>Количество</th>
                    <th>Единица измерения</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
            <tbody>
        <?php foreach ($resources as $resource): ?>
        <tr>
            <td><?php echo $resource['id']; ?></td>
            <td><?php echo $resource['name']; ?></td>
            <td><?php echo $resource['type']; ?></td>
            <td><?php echo $resource['quantity']; ?></td>
            <td>
                <button onclick="editResource(<?php echo $resource['id']; ?>)">Редактировать</button>
                <button onclick="deleteResource(<?php echo $resource['id']; ?>)">Удалить</button>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</div>
            </tbody>
        </table>
    </div>

    <div id="Products" class="tabcontent">
        <h2>Управление товарами</h2>
        <form method="POST">
            <input type="text" name="product_name" placeholder="Название товара" required>
            <input type="number" name="product_price" placeholder="Цена" required>
            <input type="number" name="product_quantity" placeholder="Количество" required>
            <button type="submit" name="add_product">Добавить товар</button>
        </form>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Название</th>
                    <th>Цена</th>
                    <th>Количество</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
            <tbody>
        <?php foreach ($products as $product): ?>
        <tr>
            <td><?php echo $product['id']; ?></td>
            <td><?php echo $product['name']; ?></td>
            <td><?php echo $product['price']; ?></td>
            <td><?php echo $product['quantity']; ?></td>
            <td>
                <button onclick="editProduct(<?php echo $product['id']; ?>)">Редактировать</button>
                <button onclick="deleteProduct(<?php echo $product['id']; ?>)">Удалить</button>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</div>
            </tbody>
        </table>
    </div>

    <div id="Personnel" class="tabcontent">
        <h2>Управление персоналом</h2>
        <form method="POST">
            <input type="text" name="employee_name" placeholder="ФИО сотрудника" required>
            <input type="text" name="employee_position" placeholder="Должность" required>
            <input type="text" name="employee_contact" placeholder="Контактные данные" required>
            <button type="submit" name="add_employee">Добавить сотрудника</button>
        </form>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ФИО</th>
                    <th>Должность</th>
                    <th>Контактные данные</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
            <tbody>
        <?php foreach ($employees as $employee): ?>
        <tr>
            <td><?php echo $employee['id']; ?></td>
            <td><?php echo $employee['name'] . ' ' . $employee['surname'] . ' ' . $employee['patronymic']; ?></td>
            <td><?php echo $employee['specialization']; ?></td>
            <td>
                <button onclick="editEmployee(<?php echo $employee['id']; ?>)">Редактировать</button>
                <button onclick="deleteEmployee(<?php echo $employee['id']; ?>)">Удалить</button>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</div>

            </tbody>
        </table>
    </div>

    <div id="Clients" class="tabcontent">
        <h2>Управление клиентами</h2>
        <form method="POST">
            <input type="text" name="client_name" placeholder="Название компании/ФИО" required>
            <input type="text" name="client_contact" placeholder="Контактные данные" required>
            <button type="submit" name="add_client">Добавить клиента</button>
        </form>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Название компании/ФИО</th>
                    <th>Контактные данные</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
            <tbody>
        <?php foreach ($clients as $client): ?>
        <tr>
            <td><?php echo $client['id']; ?></td>
            <td><?php echo $client['name'] . ' ' . $client['surname'] . ' ' . $client['patronymic']; ?></td>
            <td><?php echo $client['phone_number']; ?></td>
            <td>
                <button onclick="editClient(<?php echo $client['id']; ?>)">Редактировать</button>
                <button onclick="deleteClient(<?php echo $client['id']; ?>)">Удалить</button>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</div>
            </tbody>
        </table>
    </div>
</div>

<h2>Выход</h2>
<button class="logout-button" id="logout">Выйти</button>

<script>
function openTab(evt, tabName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";
}

document.getElementById('logout').addEventListener('click', function() {
    fetch('logout.php', { method: 'POST' }).then(response => response.text()).then(data => {
        window.location.href = 'avt.php';
    });
});

// Открываем первую вкладку по умолчанию
document.getElementsByClassName('tablinks')[0].click();
</script>

<?php
// Здесь должен быть PHP код для обработки форм и вывода данных
// Вам нужно будет создать соответствующие таблицы в базе данных
// и написать код для вставки, обновления и удаления данных

// Пример обработки добавления ресурса:
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_resource'])) {
    $resource_name = $_POST['resource_name'];
    $resource_quantity = $_POST['resource_quantity'];
    $resource_unit = $_POST['resource_unit'];

    // Здесь должен быть код для вставки данных в таблицу ресурсов
    // Пример:
    // $query = "INSERT INTO resources (name, quantity, unit) VALUES (?, ?, ?)";
    // $stmt = mysqli_prepare($connect, $query);
    // mysqli_stmt_bind_param($stmt, "sis", $resource_name, $resource_quantity, $resource_unit);
    // if (mysqli_stmt_execute($stmt)) {
    //     echo '<script>document.getElementById("message").innerHTML = "Ресурс успешно добавлен"; document.getElementById("message").className = "message success"; document.getElementById("message").style.display = "block";</script>';
    // } else {
    //     echo '<script>document.getElementById("message").innerHTML = "Ошибка при добавлении ресурса: ' . mysqli_error($connect) . '"; document.getElementById("message").className = "message error"; document.getElementById("message").style.display = "block";</script>';
    // }
    // mysqli_stmt_close($stmt);
}

// Аналогичные блоки нужно создать для обработки других форм (товары, персонал, клиенты)

// Закрытие соединения с базой данных
mysqli_close($connect);
?>

</body>
</html>