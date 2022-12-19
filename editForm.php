<?php
session_start();
if (!$_COOKIE["user"]) {
    header("Location:/index.php");
}
$connect = mysqli_connect("localhost", "root", "", "Test");
$commReq = mysqli_query($connect, "SELECT id,`picture`,`Text`,date FROM `comments` WHERE id = {$_GET['id']} AND`picture`={$_GET['pic']}");
$commReq = mysqli_fetch_assoc($commReq);
$DateDeadLine = date('Y-m-d H:i:s', strtotime("{$commReq['date']} +5 minutes"));
$newDate = date('Y-m-d H:i:s');
if($newDate>$DateDeadLine){
    $_SESSION['msg'] = 'Время для редактирования истекло';
    header("Location:/morepage.php?id={$_GET['pic']}");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Изменить</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="style.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <form class="commentsForm" method="POST" action="script.php?page=edit&&id=<?php echo "{$commReq['id']}&&pic={$_GET['pic']}" ?>">
            <textarea required type="text" name="message" class="msg"><?php echo $commReq['Text']?></textarea>
            <input class="button" type="submit" value="Отправить">
        </form>
        <?php echo "<p>{$_SESSION['msg']}</p>";
        $_SESSION['msg'] = ''; ?>

    </div>

</body>

</html>