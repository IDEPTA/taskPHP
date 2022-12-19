<?php
session_Start();
$connect = mysqli_connect("localhost", "root", "", "Test");
$page = $_GET['page'];
$dop = '';
switch ($page) {
    case "index":
        $login = $_POST['login'];
        $password = $_POST['password'];
        $req = mysqli_query($connect, "SELECT * FROM `user` WHERE `Login` = '$login'");
        $req = mysqli_fetch_assoc($req);
        if (count($req) != 0 && password_verify($password, $req['Password'])) {
            $_SESSION['user'] = $req;
            $_SESSION['msg'] = 'Добро пожаловать';
            $page = 'mainpage';
            setcookie('user[id]', $req['id'], time() + 3600);
            setcookie('user[login]', $login, time() + 3600);
            setcookie('user[password]', $req['Password'], time() + 3600);
            setcookie('user[name]', $req['name'], time() + 3600);
        } else {
            $_SESSION['user'] = '';
            $_SESSION['msg'] = 'Ошибка';
        }
        break;
    case "registration":
        $login = $_POST['login'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $name = $_POST['name'];
        $sql = "INSERT INTO `user`(`Login`, `Password`, `name`) VALUES ('$login','$password','$name')";
        mysqli_query($connect, $sql);
        $_SESSION['msg'] = 'Регистрация прошла успешно';
        $page = 'index';
        break;
    case "upload":
        $lastUploadPic = mysqli_query($connect, "SELECT MAX(`time`) as `time` FROM `Picture` WHERE `user` = '{$_COOKIE['user']['id']}'");
        $lastUploadPic = mysqli_fetch_assoc($lastUploadPic);
        if(!isset($lastUploadPic['time'])){
            $lastUploadPic = 0;
        }
        else{
            $lastUploadPic = date('Y-m-d H:i:s', strtotime("{$lastUploadPic['time']} +3 minutes"));
        }
        $name = $_FILES['file']['name'];
        $copyPic = mysqli_query($connect, "SELECT MAX(`time`) as `time` FROM `Picture` WHERE `picture` = '$name'");
        $copyPic = mysqli_fetch_all($copyPic)[0][0];
        if(!isset($copyPic)){
            $copyPic = 0;
        }
        else{
            $copyPic = date('Y-m-d H:i:s', strtotime("$copyPic +15 minutes"));
        }
        $realTime = date("Y-m-d H:i:s");
        echo $realTime."<br>".$lastUploadPic."<br>".$copyPic;
        if ($realTime > $lastUploadPic && $realTime>$copyPic) {
            $size = getimagesize($_FILES['file']['tmp_name']);
            if (($_FILES['file']['size'] < 5242880) && ($_FILES['file']['size'] > 200000) && ($size[0] < 1500) && ($size[1] < 1500)) {
                $tmp_old = $_FILES['file']['tmp_name'];
                $date = date("Y-m-d H:i:s");
                $tmp_new = 'img/' . $name;
                $user = $_COOKIE['user']['id'];
                move_uploaded_file($tmp_old, $tmp_new);
                $sql = "INSERT INTO `Picture` (`id`, `picture`, `user`, `time`,`comments`) VALUES (NULL, '$name', '$user', '$date',0);";
                mysqli_query($connect, $sql);
                $_SESSION['timeUpload'] = date("Y-m-d H:i:s");
            } else {
                $_SESSION['msg'] = 'Изображение не подходит';
            }
        }
        elseif($realTime<$lastUploadPic){
            $_SESSION['msg'] = 'Картинки можно добовлять раз в 3 минуты!';
        }
        elseif($realTime<$copyPic){
            $_SESSION['msg'] = 'Такую же картинку можно будет загрузить через 15 минут';
        }
        $page = 'mainpage';
        break;
    case "logout":
        setcookie('user[id]', $req['id'], time() - 3600);
        setcookie('user[login]', $login, time() - 3600);
        setcookie('user[password]', $req['Password'], time() - 3600);
        setcookie('user[name]', $req['name'], time() - 3600);
        setcookie('user', $req['name'], time() - 3600);
        $_SESSION['user'] = '';
        $page = "index";
        break;
    case "addComments":
        $msg = $_POST['message'];
        $chek = mb_strtoupper($msg);
        $chek = explode(" ",$chek);
        $picture = $_GET['id'];
        if (in_array('ЛЕС',$chek) == false && in_array('ОЗЕРО',$chek) == false && in_array('ПОЛЯНА',$chek) == false) {
            $user = $_COOKIE['user']['id'];
            $date = date("Y-m-d H:i:s");
            $sql = "INSERT INTO `comments`(`id`, `picture`, `user`, `Text`,`date`,`edited`) VALUES (null,'$picture','$user','$msg','$date',0)";
            mysqli_query($connect, $sql);
        } else {
            $_SESSION['msg'] = 'Такое писать нельзя!';
        }
        $page = 'morepage';
        $dop = "?id=$picture";
        break;
    case "del":
        $id = $_GET['id'];
        $picture = $_GET['pic'];
        $sql = "DELETE FROM `comments` WHERE `id` = $id";
        mysqli_query($connect,$sql);
        $_SESSION['history'] = '';
        $page = 'morepage';
        $dop = "?id=$picture";
        break;
    case "edit":
        $id = $_GET['id'];
        $picture = $_GET['pic'];
        $message = $_POST['message'];
        $sql = "UPDATE `comments` SET `Text`='$message',`edited`=1 WHERE `id` = $id and `picture` =$picture";
        mysqli_query($connect,$sql);
        $page = 'morepage';
        $dop = "?id=$picture";
        break;
    case "history":
        $_SESSION['history'] = '';
        $picture = $_GET['pic'];
        $sql = "SELECT `name`,`Text`,`date` FROM `historyEdit` JOIN user WHERE `comment` = {$_GET['id']} and `picture` = {$_GET['pic']} and `user` = {$_GET['user']} AND `user`= user.id";
        $historyReq = mysqli_query($connect,$sql);
        $historyReq = mysqli_fetch_all($historyReq);
        $_SESSION['history'] =  $historyReq;
        $page = 'morepage';
        $dop = "?id=$picture";
        break;
        
}
header("Location:/$page.php$dop");