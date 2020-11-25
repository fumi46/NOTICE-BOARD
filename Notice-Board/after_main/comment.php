<?php    //コメント画面
session_start();

require_once '../classes/UserLogic.php';
require_once '../classes/security.php';

// ini_set( 'display_errors', 1 );
// ini_set( 'error_reporting', E_ALL );

if(empty($_SESSION)){
    header('Location: ../before_main/login.php');
}

// ユーザー名の取得
$login_name = $_SESSION['login_user']['username'] ;

//var_dump($_GET);
//var_dump($_SERVER);
//var_dump($_SESSION);

//データ保持(POST前後)
if(isset($_GET['id'])){
    //POST 前
    $result = UserLogic::getById($_GET['id']);

    $val = $result['id'];
    $posted_user = $result['post_user'];
    $posted_region = $result['region'];
    $posted_time = $result['time'];
    $posted_content = $result['content'];
}else if($_SERVER['REQUEST_URI'] == $_SERVER['SCRIPT_NAME']){
    //2度目のPOST 後
    $val = $_POST['Post_id'];

    $result = UserLogic::getById($val);

    $posted_user = $result['post_user'];
    $posted_region = $result['region'];
    $posted_time = $result['time'];
    $posted_content = $result['content'];
}else{
    //最初のPOST 後
    $ref = $_SERVER['HTTP_REFERER'];
    $box = parse_url($ref, PHP_URL_QUERY);
    parse_str($box,$arr);
    $val = $arr['id'];  // HTTP_REFERER から id を取り出す。

    $result = UserLogic::getById($val);

    $posted_user = $result['post_user'];
    $posted_region = $result['region'];
    $posted_time = $result['time'];
    $posted_content = $result['content'];
}

//コメント登録
if(isset($_POST['comment_post'])){
    $commentData = $_POST;
    $comment = new UserLogic();
    $comment->createComment($commentData); 
}

//コメントデータをDBから取得
$comm = new UserLogic();
$comms = $comm->getByPostId($val); 

//var_dump($_POST);
//var_dump($comms);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" 
    href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" 
    integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" 
    crossorigin="anonymous">

    <link rel="stylesheet" href="../css/register.css">
</head>
<body>
    <h2>コメント投稿</h2>
        <div class="content" id="content">
        
            <div class="post_user_form">
            <?php  echo 'No.'.$val ?>
                <p>投稿者 : <?php echo $posted_user ?></p>
            </div>
            <div class="posted">
                <p>地域 : <?php echo $posted_region ?></p>
                <p>時期 : <?php echo $posted_time ?></p>
                <p>【内容】<br> <?php echo '&ensp;'.$posted_content ?></p>
            </div>
        </div>
    <div class="comment_form_all">
        <p><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-file-text-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M12 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zM5 4a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1H5zm-.5 2.5A.5.5 0 0 1 5 6h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5zM5 8a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1H5zm0 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1H5z"/>
            </svg> コメント (100文字以内で入力してください)
        </p>
        <form action="comment.php" method="POST">
            <div class="comment">
                <input type="text" name="Post_id" class="login_user" value="<?php echo $val ?>" hidden>
                    <input type="text" name="comment_user" class="login_user" value="<?php echo $login_name ?>" hidden>
                    <!-- 内容 -->
                    <div class="comment_form">
                        <textarea class="form-control" type="text" name="comment" rows="4" maxlength="100" value="" autofocus required></textarea>                            
                    </div>
                <p>入力文字数: <span id="count">0</span>/100</p>
            </div>
            <a href="main.php">←戻る</a>
            <input type="submit" id="post-btn"  name="comment_post" value="コメントする"></input>
        </form>
    </div>
    <div class="comment_index">
        <div class="comment_title">
            <h5>// コメント一覧 (<?php echo '&nbsp'.count($comms).'&nbsp' ?>件) //</h5>
        </div>
        <?php foreach(array_reverse($comms) as $comm): ?>  <!-- 降順表示 -->
            <div class="box thread">
                <p><?php echo '投稿者 :'.'&nbsp'.h($comm['comment_user'])?></p>
                    <div class="comment_content">
                        <p><?php echo '&nbsp'.h($comm['comment'])?></p>
                    </div>
                        <?php if($login_name == $comm['comment_user']):?>
                            <div class="delete_btn_form">
                                <a href="comment_delete.php?id=<?php echo $comm['id'] ?>">削除</a> 
                            </div>
                        <?php endif; ?>
                    <div class="comment_at_form">
                        <p><?php echo '投稿 :'.'&nbsp'.$comm['comment_at'] ?></p>
                    </div>
            </div>
        <?php endforeach; ?>
    </div>
<footer>
    <p><a href="main.php">←投稿一覧へ戻る</a></p>
</footer>
<!-- jQueryの読み込み -->
<script type="text/javascript" src="http://code.jquery.com/jquery-3.1.0.min.js"></script>
<!-- 自作ファイルの読み込み -->
<script src="../script.js"></script>
</body>
</html>