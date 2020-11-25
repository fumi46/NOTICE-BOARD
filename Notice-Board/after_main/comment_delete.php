<?php  //コメント削除完了画面
session_start();  

require_once '../classes/UserLogic.php';

//セッション外からのアクセス時
if(empty($_SESSION)){
    header('Location: ../before_main/login.php');
}

// var_dump($_GET);
// var_dump($_POST);
// var_dump($_SERVER);

//データ削除
$result = UserLogic::deleteComment($_GET['id']);

//var_dump($result);

?>
<p><a href="main.php"><span style="font-size:xx-large">投稿一覧へ</span></a></p>

