<?php
  // Завершаем сессию
  session_start();
  session_destroy();

  // Перенаправляем пользователя на страницу входа
  header('Location: avt.php');
  exit;
?>