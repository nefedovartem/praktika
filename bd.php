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
