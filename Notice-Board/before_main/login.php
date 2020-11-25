<?php  // ログイン画面

session_start();  

//var_dump($_SESSION);

$err = $_SESSION;  //セッションにエラー(ログイン情報の入力漏れ)があれば代入される。

$_SESSION = array();  //ログイン情報入力前(HOME画面から来た時やリロード時など)なら、空の配列をセッションに代入し、
session_destroy();    //セッションを行わない。

?>

<!DOCTYPE html>  
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン画面</title>
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
                <h2>ログイン</h2>
            </div>
            <!-- エラーメッセージ ログイン失敗時-->
            <?php if( isset($err['msg'])): ?>
                <ul class="loginfail"><?php echo $err['msg']; ?></ul>
            <?php endif ?>
        </header>
    </div>
    <!-- 入力フォーム -->
    <div class="container">
        <div class="mx-auto" style="...">
            <form action="../after_main/main.php" method="POST" class="row">
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
                            <input class="form-control" type="text" name="user" value="<?php echo $user ?>" placeholder="Username" autofocus><!-- autofocus = テキストフィールド自動選択,required = 必須入力表示-->
                            <!-- エラーメッセージ 未入力-->
                            <?php if( isset($err['user'])): ?>
                                <ul id="logincheck"><?php echo $err['user']; ?></ul>
                            <?php endif ?>
                    </div>
                    <div class="form" id="pass-form">
                        <label for="password">
                            <span class="label label-danger"></span>
                            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-lock" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M11.5 8h-7a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1h7a1 1 0 0 0 1-1V9a1 1 0 0 0-1-1zm-7-1a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h7a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-7zm0-3a3.5 3.5 0 1 1 7 0v3h-1V4a2.5 2.5 0 0 0-5 0v3h-1V4z"/>
                            </svg>Password
                        </label>
                        <br>
                            <input class="form-control" type="password" name="pass" value="" placeholder="Password" autofocus>
                            <!-- エラーメッセージ -->
                            <?php if( isset($err['pass'])): ?>
                                <ul id="logincheck"><?php echo $err['pass']; ?></ul>
                            <?php endif ?>
                    </div>
                        <a href="./index.php" id="back-btn" >←戻る</a>

                        <!--<input type="hidden" name="csrf_token" value="<?php //echo h(setToken()); ?>">--><!--トークン埋め込み-->
                        <input type="submit" id="login-btn"  name="reg" value="ログイン"></input>
                </div>
            </form>
        </div>
    </div>
    <div class="container" id="fooer">
        <p>初めての方は<a href="signup.php" class="to_signup">新規登録</a>をしてからログインしてください。</p>
    </div>
</body>
</html>