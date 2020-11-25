<?php  //各種データ登録・取得、投稿削除、ログイン、ログインチェック、ログアウト

// ini_set( 'display_errors', 1 );
// ini_set( 'error_reporting', E_ALL );

require_once '../config/dbconnect.php';

class UserLogic
{
    /**
     * ユーザー登録
     * @param array $userData  //$userData = username, email, password
     * @return bool $result    //bool = true or false
     */
    public static function createUser($userData)
        {
            $result = false;

            //SQL実行の流れ = テーブル（レコード）接続 → SQLの準備 → 実行 → 結果

            //テーブル定義
            $sql = 'INSERT INTO Users(username , email, password)
                    values(?, ?, ?)';   // ? = SQLインジェクション対策
            
            //ユーザーデータ配列を格納。
            $arr = [];
            $arr[] = $userData['user'];
            $arr[] = $userData['mail'];
            $arr[] = password_hash($userData['pass'], PASSWORD_DEFAULT);  //パスワードのハッシュ化

            //SQL実行時のエラー検証
            try {  
                $stmt = connect()->prepare($sql);   //SQL準備。
                $result = $stmt->execute($arr);     //SQL実行。$arr を VALUES配列 に入れる。
                return $result;                     //SQL結果。最初に定義した$result が上記でtrue に置き換わる。
            }catch(\Exception $e){  
                error_log($e, 3, '../error.log');   //エラー内容をファイル内に表示させる。
                return $result;
            }
        }


    /**
     * ログイン処理
     * @param string $user,$pass
     * @return bool $result    
     */
    public static function login($user, $pass)
        {
            $result = false;

            $user = self::getUserByUser($user);  
            //ユーザー名の照会
            if(!$user){
                $_SESSION['msg'] = 'Username が一致しません。';
                return $result;
            }

            //パスワードの照会
            if(password_verify($pass,$user['password'])){
                //ログイン成功
                session_regenerate_id(true);       //セッションハイジャック対策。セッション id の再作成。ログイン照会後に自動で行う。
                $_SESSION['login_user'] = $user;   
                $result = true;
                return $result;
            }

            //パスワードが違う時
            $_SESSION['msg'] = 'Password が一致しません。';
            return $result;
        }


    /**
     * username カラムからユーザー情報を取得。
     * @param string $user  
     * @return array|bool $user|false   //成功|失敗
     */
    public static function getUserByUser($user)
        {
            //SQL実行の流れ = テーブル（レコード）定義 → SQLの準備 → 実行 → 結果

            $sql = 'SELECT * FROM Users WHERE username = ?';  
            
            //username 配列を定義。
            $arr = [];
            $arr[] = $user;
            
            //エラー検証
            try {  
                $stmt = connect()->prepare($sql);   //SQLの準備。
                $stmt->execute($arr);               //SQLの実行。
                $user = $stmt->fetch();             //SQLの結果。結果からusername の値を1つ取得する。
                return $user;                       
            }catch(\Exception $e){  
                return false;
            }
        }


    /**
     * ログインチェック
     * @param void
     * @return bool $result
     */
    public static function checkLogin()
        {
            $result = false;

            if(isset($_SESSION['login_user']) && $_SESSION['login_user']['id'] > 0){
                return $result = true; 
            }
            return $result;  
        }


    /**
     * ログアウト処理 
     */
    public static function logout()
        {
            $_SESSION = array();   //セッション変数を全て解除
            session_destroy();     //セッションを終了
        }

    /**
     * 新規投稿登録 
     * @param array $postData  //$postData = region, time, content, post_user
     * @return bool $result    //bool = true or false
     */
    public static function createPost($postData)
        {
            $result = false;

            //投稿データ配列を定義。
            $arr = [];
            $arr[] = $postData['region'];
            $arr[] = $postData['time'];
            $arr[] = $postData['content'];
            $arr[] = $postData['post_user'];

            $sql = 'INSERT INTO Post(region, time, content, post_user)
                    VALUES(?, ?, ?, ?)';

            $dbh = Connect();                   //データベースに接続
            $dbh->beginTransaction();           //トランザクション開始
            try{
                $stmt = $dbh->prepare($sql);    //SQLの準備
                $result = $stmt->execute($arr); //SQLの実行
                $dbh->commit();                 //トランザクション確定
                return $result;                 //SQLの結果
            } catch(PDOException $e){
                $dbh->rollBack();               //データベースへの変更をロールバック
                return $result;
            }
        }

    /**
     * 投稿(編集)データの取得
     * @param  void
     * @return bool $result 
     */
    public static function getPost()
        {
            $sql = 'SELECT * FROM Post'; 

            $dbh = Connect();             

            $stmt = $dbh->query($sql);                    //SQL準備
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);  //SQL実行。SQL結果の受け取り。全てのレコード(fetchAll)について、カラムを配列のキー文字列で格納した値(PDO::FETCH_ASSOC)を返す。
            return $result;
            $dbh = null;                                  //データベース接続終了
        }


    /**
     * 投稿データの保持（編集画面）
     * @param string $id  
     * @return bool $result
     */
    public static function getById($id)
        {
            //SQL実行の流れ = テーブル（レコード）定義 → SQLの準備 → 実行 → 結果
            
            $sql = 'SELECT * FROM Post WHERE id = ?';  
            
            //id 配列を定義。
            $arr = [];
            $arr[] = $id;
            
            //エラー検証
            try {  
                $stmt = connect()->prepare($sql);          //SQLの準備。
                $stmt->bindValue('?', (int)$id, PDO::PARAM_INT); //VALUESとの紐付け。(カラム, 実際の値, データ型)
                $stmt->execute($arr);                      //SQLの実行。
                $result = $stmt->fetch(PDO::FETCH_ASSOC);  //SQLの結果。
                return $result;                            
            }catch(\Exception $e){  
                return false;
            }
        }
    

    /**
     * 編集登録
     * @param array $editData 
     * @return bool $result   
     */
    public static function createEdit($editData)
        {    
            //編集データ配列を定義。
            $arr = [];
            $arr[] = $editData['region'];
            $arr[] = $editData['time'];
            $arr[] = $editData['content'];
            $arr[] = $editData['id'];
    
            $sql = 'UPDATE Post 
                    SET region = ?, time = ?, content = ?
                    WHERE id = ?';

            $dbh = Connect();                   
            $dbh->beginTransaction();           //トランザクション開始
            try{
                $stmt = $dbh->prepare($sql);    //SQLの準備
                $result = $stmt->execute($arr); //SQLの実行
                $dbh->commit();                 //トランザクション確定
                echo '投稿を更新しました。';
                return $result;                 //SQLの結果
            } catch(PDOException $e){
                $dbh->rollBack();               //データベースへの変更をロールバック
                exit($e);
            }
        }

        
    /**
     * 投稿の削除
     * @param string $id
     * @return bool
     */
    public static function deletePost($id)
        {
            $sql = 'DELETE FROM Post WHERE id = ?';
            
            //id 配列を定義。
            $arr = [];
            $arr[] = $id;
            
            //エラー検証
                $stmt = connect()->prepare($sql);            //SQLの準備。
                $stmt->bindValue('?', (int)$id, PDO::PARAM_INT); //VALUESとの紐付け。(カラム, 実際の値, データ型)
                $result = $stmt->execute($arr);              //SQLの実行。
                echo '投稿を削除しました。';
                return $result;                                     
        }


    /**
     * 投稿+コメントデータの削除
     * @param string $id
     * @return bool
     */
    public static function delete($id)
        {
            $sql = 'DELETE Post, Comments 
                    FROM Post 
                    JOIN Comments 
                    ON  Post.id = Comments.Post_id 
                    WHERE Post.id = ? OR comment_user IS NULL'; 
            
            //id 配列を定義。
            $arr = [];
            $arr[] = $id;
            
            //エラー検証
                $stmt = connect()->prepare($sql);        //SQLの準備。
                $stmt->bindValue('?', (int)$id, PDO::PARAM_INT); //VALUESとの紐付け。(カラム, 実際の値, データ型)
                $result = $stmt->execute($arr);          //SQLの実行。
                //echo '投稿を削除しました。';                
                return $result;                              
        }


    /**
     * コメントの投稿・取得
     * @param string $val
     * @return bool
     */
    public static function getByPostId($val)
    {
        //SQL実行の流れ = テーブル（レコード）接続 → SQLの準備 → 実行 → 結果
        
        $sql = 'SELECT * 
                FROM Post 
                JOIN Comments 
                ON  Post.id = Comments.Post_id 
                WHERE Post.id = ?'; 
        
        //id 配列を定義。
        $arr = [];
        $arr[] = $val;
        
        //エラー検証
        try {  
            $stmt = connect()->prepare($sql);             //SQLの準備。
            $stmt->bindValue('?', (int)$val, PDO::PARAM_INT); //値の結合(カラム, 実際の値, データ型)
            $stmt->execute($arr);                         //SQLの実行。
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);  //SQLの結果。
            return $result;                            
        }catch(\Exception $e){  
            return false;
        }
    }


    /**
     * コメント登録
     * @param string $commentData
     * @return bool
     */
    public static function createComment($commentData)
    {
        $result = false;

        //投稿データ配列を定義。
        $arr = [];
        $arr[] = $commentData['Post_id'];
        $arr[] = $commentData['comment_user'];
        $arr[] = $commentData['comment'];
        $sql = 'INSERT INTO Comments(Post_id, comment_user, comment)
                VALUES(?, ?, ?)';

        $dbh = Connect();          
        $dbh->beginTransaction();  //トランザクション開始

        try{
            $stmt = $dbh->prepare($sql);    //SQLの準備
            $result = $stmt->execute($arr); //SQLの実行
            $dbh->commit();                 //トランザクション確定
            return $result;                 //SQLの結果
        } catch(PDOException $e){
            $dbh->rollBack();               //データベースへの変更をロールバック
            return $result;
        }
    }


    /**
     * コメントデータの削除
     * @param string $id
     * @return bool
     */
    public static function deleteComment($id)
    {
        $sql = 'DELETE FROM Comments WHERE id = ?';   //レコードの定義
        
        //id 配列を定義。
        $arr = [];
        $arr[] = $id;
        
        //エラー検証
            $stmt = connect()->prepare($sql);            //SQLの準備。
            $stmt->bindValue('?', (int)$id, PDO::PARAM_INT); //VALUESとの紐付け。(カラム, 実際の値, データ型)
            $result = $stmt->execute($arr);              //SQLの実行。
            echo 'コメントを削除しました。';
            return $result;                                  
    }
}
?>