<?php  //ログアウト画面

session_start();  //session = 1ユーザーのログインからログアウトまでの管理

// ini_set( 'display_errors', 1 );
// ini_set( 'error_reporting', E_ALL );
require_once '../classes/UserLogic.php';

//ログアウトが押されていなければ
$logout = filter_input(INPUT_POST, 'logout');

if(!$logout){
    exit('不正なリクエストです。');
}

//セッションが切れていたら
$result = UserLogic::checkLogin();
if(!$result){
    exit('セッションが切れましたので、ログインし直してください。');
}

//ログアウトする
UserLogic::logout();

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログアウト画面</title>
</head>
<body>
    <p>ログアウトしました。</p>
    <a href="./index.php">ホームへ</a>
</body>
</html>
