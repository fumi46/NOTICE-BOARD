<?php  //編集完了画面
session_start();

require_once '../classes/UserLogic.php';

if(empty($_SESSION)){
    header('Location: ../before_main/login.php');
    exit();
}

//編集データをDBへ保存
if(isset($_POST['edit_post'])){
    $editData = $_POST;
    $edit = new UserLogic();
    $edit->createEdit($editData);
}

//var_dump($_POST);

?>
<p><a href="main.php"><span style="font-size:xx-large"> 投稿一覧へ</span></a></p>

