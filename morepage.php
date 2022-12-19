<?php
session_start();
if (!$_COOKIE["user"]) {
    header("Location:/index.php");
}
$connect = mysqli_connect("localhost", "root", "", "Test");
$reqImgMore = mysqli_query($connect, "SELECT Picture.`id`,`picture`,`name`,`time`,`comments` FROM `Picture` JOIN user WHERE Picture.`id` = '{$_GET['id']}' AND `user` = user.id");
$reqImgMore = mysqli_fetch_assoc($reqImgMore);
$reqComm = mysqli_query($connect,"SELECT comments.id,`picture`,`user`,name,`Text`,date,edited FROM `comments` JOIN user WHERE `user` = user.id AND `picture` ={$reqImgMore['id']} ORDER BY `date`");
$reqComm = mysqli_fetch_all($reqComm);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Подробнее</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="style.css" rel="stylesheet">
</head>

<body>
    <a href="mainpage.php">Назад</a>
    <div class="container">
        <div class="imgblock">
            <?php
            echo "<p class = 'name'>{$reqImgMore['name']}</p>
                  <p class = 'time'>{$reqImgMore['time']}</p>
                  <img src = 'img/{$reqImgMore['picture']}'>";
            ?>
        </div>
        <div class="comments">
            <?php
                foreach($reqComm as $el){
                    echo "<p><span class = 'name'>$el[3]</span> : $el[4]";
                    if($el[3]== $_COOKIE['user']['name']){
                        echo "<span class = 'link-block'>
                        <a href = editForm.php?page=edit&&id=$el[0]&&pic={$reqImgMore['id']}>&#9998</a>
                        <a href = script.php?page=del&&id=$el[0]&&pic={$reqImgMore['id']}>&#9746</a></span>";
                    }
                    if($el[6]>0){
                        echo "<a href = script.php?page=history&&id=$el[0]&&pic={$reqImgMore['id']}&&user=$el[2] class = 'edit'>(Edited)</a>";
                    }
                          echo "</p>";
                }
            ?>
        </div>
        <form class="commentsForm" method="POST" action="script.php?page=addComments&&id=<?php echo "{$reqImgMore['id']}"?>">
            <textarea required type="text" name="message" class="msg"></textarea>
            <input class="button" type="submit" value="Отправить">
        </form>
        <?php echo "<p>{$_SESSION['msg']}</p>";
                    $_SESSION['msg'] = ''?>
    </div>
    <div class="history">
        <?php
        if($_SESSION['history']!=''){
            foreach($_SESSION['history'] as $value){
                echo "<p><span>$value[0]<span> : $value[1] | $value[2]</p>";
            }
        }
        else{
            echo "<p>Здесь история комментариев</p>";
        }
        ?>
    </div>
</body>

</html>