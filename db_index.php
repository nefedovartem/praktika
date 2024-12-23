<?php
$host = "134.90.167.42:10306";
$username = "Nefedov";
$password = "5bqYdI";
$dbname = "project_Nefedov";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Ошибка подключения: " . $e->getMessage();
}
?>