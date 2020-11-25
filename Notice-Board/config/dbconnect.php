<?php //データベース接続

require_once 'env.php';  

//ini_set('display_errors', true);  //エラー表示

function connect()
{
    //データベース情報
    $host = DB_HOST;
    $db   = DB_NAME;
    $user = DB_USER;
    $pass = DB_PASS;
    
    $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";    //dsn(data source name) = データベースの情報取得

    //データベース接続時のエラー検証
    try{                    //エラー無し（成功なら）true
        $pdo = new PDO($dsn, $user, $pass, [                 //データベース接続。POD = 全てのデータベースに共通の接続クラス
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,     //エラーモードを例外で出力
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC //配列をキー、バリューで返す。
        ]);
        return $pdo;   //接続結果
        $pdo = null;   //接続終了
    }catch(PDOExeption $e){             //エラー有りの処理。 $e の中にエラーをいれる。
        echo '接続失敗'.$e->getMessage();  //エラーメッセージの表示
        exit();                         //処理の終了
    }
}


?>