<?php  //ユーザー登録画面

//ini_set( 'display_errors', 1 );
//ini_set( 'error_reporting', E_ALL );

require_once '../classes/UserLogic.php';

$error_message = array();

//バリデーション
//新規登録画面で情報が入力された時
$user = filter_input(INPUT_POST, 'user'); //usernameがフォームからpostで送られてきたら、usernameを文字列で返す。
$mail = filter_input(INPUT_POST, 'mail');
$pass = filter_input(INPUT_POST, 'pass');
$passconf = filter_input(INPUT_POST, 'pass-conf');

//username の未入力チェック
if(!$user){
    $error_message[] = 'Usernameを入力してください。';
}

//e-mail の未入力チェック + 形式チェック((数字か英字と./_?)@(数字と英字と-).(数字と英字)が可能)
if(!$mail){
    $error_message[] = 'E-mailを入力してください。';
}

//password のチェック(半角英数字5文字以上20文字以内)
if(!preg_match("/^(?=.*?[a-z])(?=.*?\d)[a-z\d]{5,20}$/", $pass)){    //preg_match = 正規表現にマッチしたらtrue
    $error_message[] = 'Passwordは半角英数字5文字以上20文字以内で入力してください。';
}
//password確認のチェック
if($pass !== $passconf){
    $error_message[] = 'Password確認がPasswordと一致しません。';
}

//ユーザ登録の処理
if(count($error_message) === 0){
    //ユーザー登録処理
    $hasCreated = UserLogic::createUser($_POST);  //登録フォーム(form)からPOST で受け取った配列($_POST)を持って、UserLogic クラスのcreateUser という静的メソッドにアクセスする。

    //不備があれば、メッセージが出る。
    if(!$hasCreated){
        $error_message[] = 'Usernameを変更して下さい。このUsernameは既に使用されています。';
    }
}

?>


<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新規登録画面</title>
    <link rel="stylesheet" 
    href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" 
    integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" 
    crossorigin="anonymous">
    <link rel="stylesheet" href="../css/register.css">
    
</head>
<body>
    <div class="container">
        <header>
            <div class="row">
                <h3>新規登録</h3><h4>（ユーザー登録）</h4>
            </div>
        </header>
    </div>
    <!-- 入力フォーム -->
    <div class="container">
        <div class="mx-auto" style="...">
            <!-- エラーメッセージ -->
            <?php if( count($error_message) > 0 ): ?>
                <ul class="error_list">
                    <?php foreach( $error_message as $e ): ?>
                        <li><?php echo $e; ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php else : ?>
            <!-- 登録完了表示 -->
            <script>
                alert('登録完了しました。\n投稿一覧へ入る場合は、OKボタンを押して、ログイン画面へ移って下さい。\n\n戻る場合は、ブラウザの戻るボタンを押してください。');
                window.location.href = './login.php';
            </script>
            <?php endif ?>
            <!-- 入力欄 -->
            <form action="signup.php" method="post" class="row">
                <div class="forms">
                    <div class="form">
                        <label for="username">
                            <span class="label label-danger"></span>
                            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-person-square" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M14 1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
                            <path fill-rule="evenodd" d="M2 15v-1c0-1 1-4 6-4s6 3 6 4v1H2zm6-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                            </svg>Username
                        </label>
                        <br>
                            <input class="form-control" type="text"  id="reg-name" name="user" value="<?php echo $user ?>" placeholder="Username" autofocus required><!-- autofocus = テキストフィールド自動選択,required = 必須入力表示-->
                    </div>
                    <div class="form">
                        <label for="e-mail">
                            <span class="label label-danger"></span>
                            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-envelope" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2zm13 2.383l-4.758 2.855L15 11.114v-5.73zm-.034 6.878L9.271 8.82 8 9.583 6.728 8.82l-5.694 3.44A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.739zM1 11.114l4.758-2.876L1 5.383v5.73z"/>
                            </svg>E-mail
                        </label>
                        <br>
                            <input class="form-control" type="email" name="mail" value="<?php echo $mail ?>" placeholder="E-mail" autofocus required><!-- メールアドレスの書式チェック -->
                    </div>
                    <div class="form">
                        <label for="password">
                            <span class="label label-danger"></span>
                            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-lock" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M11.5 8h-7a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1h7a1 1 0 0 0 1-1V9a1 1 0 0 0-1-1zm-7-1a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h7a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-7zm0-3a3.5 3.5 0 1 1 7 0v3h-1V4a2.5 2.5 0 0 0-5 0v3h-1V4z"/>
                            </svg>Password
                        </label>
                        <br>
                            <input class="form-control" type="password" name="pass" value="" placeholder="Password" autofocus required>
                    </div>
                    <div class="form">
                        <label for="password-conf">
                            <span class="label label-danger"></span>
                            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-lock-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path d="M2.5 9a2 2 0 0 1 2-2h7a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-7a2 2 0 0 1-2-2V9z"/>
                            <path fill-rule="evenodd" d="M4.5 4a3.5 3.5 0 1 1 7 0v3h-1V4a2.5 2.5 0 0 0-5 0v3h-1V4z"/>
                            </svg>Password確認
                        </label>
                        <br>
                            <input class="form-control" type="password" name="pass-conf" value="" placeholder="Password確認" autofocus required>
                    </div>
                        <a href="./index.php" id="back-btn" >←戻る</a>
                        <input type="submit" id="submit-btn"  name="reg" value="登録"></input>
                </div>
            </form>
        </div>
    </div>
<!-- jQueryの読み込み -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js" 
integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" 
crossorigin="anonymous">
</script>
</body>
</html>