<?php
session_start();
if (!$_COOKIE["user"]) {
    header("Location:/index.php");
}
$_SESSION['history'] ='';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Главная страница</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="style.css" rel="stylesheet">
</head>

<body>
    <?php
    echo "<h1>Добро пожаловать, {$_COOKIE['user']['name']}</h1>"
    ?>
    <a href="script.php?page=logout">Выйти</a>
    <div class="picture">
        <?php
        $connect = mysqli_connect("localhost", "root", "", "Test");
        $reqImg = mysqli_query($connect, 'SELECT * FROM `Picture`');
        $reqImg = mysqli_fetch_all($reqImg);
        foreach ($reqImg as $arr) {
            echo "<div class = 'img-card'>
                <img src='img/$arr[1]'>
                <p>Комментарии: $arr[4]</p>
                <a href = morepage.php?id=$arr[0]>Подробнее</a>
                    </div>";
        }
        ?>
    </div>
    <form class='form' action="script.php?page=upload" method="POST" enctype="multipart/form-data">
        <input required type="file" name="file" placeholder="file" class="input">
        <?php echo $_SESSION['msg'];
            $_SESSION['msg'] = ''?>
        <input type="submit" value="Добавить" class="button">
    </form>
</body>

</html>