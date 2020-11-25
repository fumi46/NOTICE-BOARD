<?php  //投稿削除完了画面
session_start();  

require_once '../classes/UserLogic.php';

// var_dump($_GET);
// var_dump($_POST);
// var_dump($_SERVER);

//セッション外からのアクセス時
if(empty($_SESSION)){
    header('Location: ../before_main/login.php');
}

//データ削除
if(empty($_POST)){
    $delete = new UserLogic();
    $delete->delete($_GET['id']);
    $delete->deletePost($_GET['id']);
}
?>
<p><a href="main.php"><span style="font-size:xx-large">投稿一覧へ</span></a></p>