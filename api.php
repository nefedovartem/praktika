<?php
$server = "134.90.167.42:10306";
$user = "Nefedov";
$pw = "5bqYdI";
$db = "project_Nefedov";

// Создание соединения
$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка соединения
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Получение данных
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT * FROM data";
    $result = $conn->query($sql);
    $data = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
    echo json_encode($data);
}

// Добавление данных
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $data = $conn->real_escape_string($input['data']);
    $sql = "INSERT INTO data (data) VALUES ('$data')";
    $conn->query($sql);
    echo json_encode(['status' => 'success']);
}

// Удаление данных
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = intval(basename($_SERVER['REQUEST_URI']));
    $sql = "DELETE FROM data WHERE id = $id";
    $conn->query($sql);
    echo json_encode(['status' => 'success']);
}

$conn->close();
?>