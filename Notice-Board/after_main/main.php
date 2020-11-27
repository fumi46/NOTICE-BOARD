<?php  //ログイン後(メイン)画面

session_start();  //session = 1ユーザーのログインからログアウトまでの管理

// ini_set( 'display_errors', 1 );
// ini_set( 'error_reporting', E_ALL );

require_once '../classes/UserLogic.php';
require_once '../classes/security.php';

//ログインフォームのチェック
if(isset($_POST['reg'])){
    $error_message = array();

    //ログイン画面で情報が入力された時
    $user = filter_input(INPUT_POST, 'user'); 
    $pass = filter_input(INPUT_POST, 'pass');

      //username の未入力チェック
      if(!$user){
          $error_message['user'] = 'Usernameを入力してください。';
      }

      //password の未入力チェック
      if(!$pass){
          $error_message['pass'] = 'Passwordを入力してください。';
      }

      //エラーメッセージが出たまま「ログイン」が押された時の処理
      if(count($error_message) > 0){
          $_SESSION = $error_message;           //それぞれのエラーメッセージを配列にする。
          header('Location: ../before_main/login.php');
          exit();
      }

      //入力後「ログイン」が押された時の処理
      $result = UserLogic::login($user, $pass); 
      
      //ログイン失敗時の処理
      if(!$result){
          header('Location: ../before_main/login.php');
          exit();  
      }
}

//セッションのチェック
if(empty($_SESSION)){
  header('Location: ../before_main/login.php');
  exit();
}

//新規投稿画面から戻ってきた時
$login_name = $_SESSION['login_user']['username'] ; 

//投稿(編集)データをDBから取得
$new = new UserLogic();
$news = $new->getPost(); 


//var_dump($_SESSION);
//var_dump($news);

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>投稿一覧</title>
    
    <link rel="stylesheet" 
    href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" 
    integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" 
    crossorigin="anonymous">
    
    <script type="text/javascript" src="http://code.jquery.com/jquery-3.1.0.min.js"></script>
    <script>
      $(function(){
        $('#top-btn').click(function(){
        $('html,body').scrollTop(0);
      })
      });
    </script>
  
    <link rel="stylesheet" href="../css/main.css">
</head>
<body>
  <header>
    <!-- ページ内移動 -->
    <div class="header-left" id="top-btn">
      <p>トップへ</p>
    </div>    
    <!-- username -->
    <div class="username">
      <p>ようこそ！<h7 class="display_name"><?php echo $login_name ?></h7> さん</p>
    </div>
    <!-- ドロップダウンメニュー -->
    <div id="navi">
      <li id="drop_down_icon">
        <img src="../image/menu_icon_white.gif" >
      </li>
      <!-- 新規投稿 -->
      <form action="post.php" method="POST" class="topost">
        <input type="submit" name="topost" id="topost" class="drop_hidden" value="新規投稿">
      </form>
      <!-- ログアウト -->
      <form action="../before_main/logout.php" method="post" class="logout">
        <input type="submit" name="logout" id="logout" class="drop_hidden" value="ログアウト">
      </form>
    </div>
</header>
<contents>
  <div class="index">
    <h2><strong>投稿一覧(<?php echo '&nbsp'.count($news).'&nbsp' ?>件)</strong></h2>
      <!-- Button trigger modal -->
      <div class="to-use">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
          使い方
        </button>
      </div>
      <!-- Modal -->
      <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">使い方</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
              <p>このサイトでは、情報の共有として、海外で起こった出来事に関する投稿、および、投稿に対するコメントができます。</p>
              <p>新規投稿では、出来事が起こった地域、時期、内容をそれぞれ記入してください。</p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
  </div>
  <?php foreach(array_reverse($news) as $new): ?>  <!-- 降順表示 -->
      <div class="box thread">
        <?php  echo 'No.'.$new['id'] ?>
          <div class="post_user_form">
            <p><?php echo '&ensp;'.'投稿者 :'.'&nbsp'.h($new['post_user'])?></p>
          </div>
          <div class="mainthread">
            <p><?php echo '&ensp;'.'地 域  :'.'&nbsp'.h($new['region'])?></p>
            <p><?php echo '&ensp;'.'時 期  :'.'&nbsp'.h($new['time'])?></p>
            <p><?php echo '【内 容】'.'<br>'.'&ensp;'.h($new['content'])?></p>
          </div>
          <?php if($login_name == $new['post_user']):?>
            <div class="edit_btn_form">
              <a href="edit.php?id=<?php echo $new['id'] ?>">編集</a>  <!-- GET送信 -->
            </div>
            <div class="delete_btn_form">
              <a href="post_delete.php?id=<?php echo $new['id'] ?>">削除</a>  
            </div>
          <?php endif; ?>
          <div class="post_at_form">
            <p><?php echo '投稿(編集) :'.'&nbsp'.$new['post_at'] ?></p>
          </div>
          <div class="tocomment">
            <a href="comment.php?id=<?php echo $new['id'] ?>">コメントする</a>
          </div>
      </div>
  <?php endforeach; ?>
</contents>
<footer>
  <h4>Thank you for reading.&#x1F600;</h4>
</footer>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" 
integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" 
crossorigin="anonymous">
</script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" 
integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" 
crossorigin="anonymous">
</script>
<!-- 自作ファイルの読み込み -->
<script src="../script.js"></script>
</body>
</html>
