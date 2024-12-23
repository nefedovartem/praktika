<?php
    $role = $_POST['role'];
    $login = $_POST['username'];
    $pass = $_POST['password'];
    $server = "134.90.167.42:10306";
    $user = "Nefedov";
    $pw = "5bqYdI";
    $db = "project_Nefedov";

    $connect = mysqli_connect($server, $user, $pw, $db);

    $query = "INSERT INTO `users`('role', 'username', 'password') VALUES ('$role','$login','$pass')";
    $result = mysqli_query($connect, $query);

    if ($conn -> query($sql) === TRUE){ 
        echo "Регистрация прошла успешно";
       }
       else {
        echo "Ошибка регистрации:" .$conn->error; 
       }
?>