<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Авторизация</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="style.css" rel="stylesheet">
    </head>
    <body>
        <form class = 'form' action="script.php?page=registration" method="post">
            <h1>Регистрация</h1>
            <input required type="text" name="name" placeholder="Имя" class="input">
            <input required type="text" name="login" placeholder="Login" class="input">
            <input required type="password" name="password" placeholder="Password" class="input">
            <input type="submit" value="Войти" class="button">
            <a href="index.php">Назад</a>
        </form>
    </body>
</html>