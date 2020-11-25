<?php  // 投稿作成画面

session_start();  

require_once '../classes/UserLogic.php';

//セッション外からのアクセス時
if(empty($_SESSION)){
    header('Location: ../before_main/login.php');
}

//入力チェック
$err_mes = array();

$region = filter_input(INPUT_POST, 'region');
$time = filter_input(INPUT_POST, 'time');
$content = filter_input(INPUT_POST, 'content');

if(empty($region)){
    $err_mes[] = '地域 を選択してください。';
}
if(!$time){
    $err_mes[] = '時期 を入力してください。';
}
if(!$content){
    $err_mes[] = '内容 を入力してください。';
}

//ユーザー名取得
$login_name = $_SESSION['login_user']['username'];

//新規登録の処理
if(count($err_mes) === 0){
    $created = UserLogic::createPost($_POST);
}

//var_dump($created);
//var_dump($_SESSION);
//var_dump($_POST);
?>

<!DOCTYPE html>  
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新規投稿画面</title>
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
                <h2>新規投稿</h2>
            </div>
            <!-- エラーメッセージ -->
            <?php if( count($err_mes) > 0 ): ?>
                <ul class="error_list">
                    <?php foreach( $err_mes as $e ): ?>
                        <li><?php echo $e; ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php else : ?>
                <!-- 登録完了表示 -->
                <script>
                    alert('投稿完了しました。\nOKボタンを押して、投稿一覧へ移って下さい。');
                    window.location.href = 'main.php';
                </script>
            <?php endif ?>
        </header>
    </div>
    <!-- 入力フォーム -->
    <div class="container">
        <div class="mx-auto" style="...">
            <form action="post.php" method="POST" class="row">
                <div class="post_forms">
                    <div class="form">
                        <input type="text" name="post_user" class="login_user" value="<?php echo $login_name ?>" hidden>
                    </div>
                    <!-- 地域選択 -->
                    <div class="form">
                        <p><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-map" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M15.817.113A.5.5 0 0 1 16 .5v14a.5.5 0 0 1-.402.49l-5 1a.502.502 0 0 1-.196 0L5.5 15.01l-4.902.98A.5.5 0 0 1 0 15.5v-14a.5.5 0 0 1 .402-.49l5-1a.5.5 0 0 1 .196 0L10.5.99l4.902-.98a.5.5 0 0 1 .415.103zM10 1.91l-4-.8v12.98l4 .8V1.91zm1 12.98l4-.8V1.11l-4 .8v12.98zm-6-.8V1.11l-4 .8v12.98l4-.8z"/>
                            </svg> 地域
                        </p>
                            <select name="region">
                                <option value="" hidden>選択してください</option>
                                <option value="アジア">アジア</option>
                                <option value="ヨーロッパ">ヨーロッパ</option>
                                <option value="北アメリカ大陸">北アメリカ大陸</option>
                                <option value="南アメリカ大陸">南アメリカ大陸</option>
                                <option value="アフリカ大陸">アフリカ大陸</option>
                                <option value="オーストラリア大陸">オーストラリア大陸</option>
                                <option value="南極大陸">南極大陸</option>
                                <option value="複数に跨がる">複数に跨がる</option>
                            </select>
                        <br>                            
                    </div>
                    <!-- 時期 -->
                    <div class="form">
                        <p><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-calendar3" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M14 0H2a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zM1 3.857C1 3.384 1.448 3 2 3h12c.552 0 1 .384 1 .857v10.286c0 .473-.448.857-1 .857H2c-.552 0-1-.384-1-.857V3.857z"/>
                            <path fill-rule="evenodd" d="M6.5 7a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
                            </svg> 時期 (西暦年/15文字以内)
                        </p>
                            <input class="form-control" type="text"  name="time" value="<?php echo $time ?>" maxlength="15" placeholder="例）2020年夏" autofocus></input>
                    </div>
                    <!-- 内容 -->
                    <div class="form">
                        <p><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-file-text-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M12 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zM5 4a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1H5zm-.5 2.5A.5.5 0 0 1 5 6h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5zM5 8a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1H5zm0 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1H5z"/>
                            </svg> 内容 (300文字以内で入力してください)
                        </p>
                            <textarea class="form-control" type="text" name="content" rows="9" maxlength="300" value="" autofocus><?php echo $content ?></textarea>                            
                    </div>
                    <p>入力文字数: <span id="count">0</span>/300</p>
                    <!-- ボタン -->
                    <a href="./main.php" id="back-btn" name="back" >←戻る</a>
                    <!--<input type="hidden" name="csrf_token" value="<?php //echo h(setToken()); ?>">--><!--トークン埋め込み-->
                    <input type="submit" id="post-btn"  name="newpost" value="投稿する"></input>
                </div>
            </form>
        </div>
    </div>
<!-- jQueryの読み込み -->
<script type="text/javascript" src="http://code.jquery.com/jquery-3.1.0.min.js"></script>
<!-- 自作ファイルの読み込み -->
<script src="../script.js"></script>
</body>
</html>