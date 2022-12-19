<?php
session_start();
if($_COOKIE["user"]){
    header("Location:/mainpage.php");
}
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
        <form class = 'form' action="script.php?page=index" method="post">
            <h1>Авторизация</h1>
            <input required type="text"  name="login" placeholder="Login" class="input">
            <input required type="password" name="password" placeholder="Password" class="input">
            <?php
                echo $_SESSION['msg'];
                $_SESSION['msg'] = ''; 
            ?>
            <input type="submit" value="Войти" class="button">
            <a href="registration.php">Зарегистрироваться</a>
        </form>
    </body>
</html>