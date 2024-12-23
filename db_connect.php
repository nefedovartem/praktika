<?php
$host = '134.90.167.42:10306'; // Обычно localhost, если база данных находится на том же сервере, что и ваш сайт
$db   = 'project_Nefedov'; // Имя вашей базы данных в phpMyAdmin
$user = 'Nefedov'; // Имя пользователя базы данных в phpMyAdmin
$pass = '5bqYdI'; // Пароль пользователя базы данных в phpMyAdmin
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $conn = new PDO($dsn, $user, $pass, $opt);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}