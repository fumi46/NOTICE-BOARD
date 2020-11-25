<?php  //XSS対策（セッションハイジャック対策はUserLogic.php）

/**
 * 
 * XSS(Cross site scripting)対策:エスケープ処理(悪意あるコードの埋め込みを防ぐ)
 * 
 * @param string $str : 対象の文字列
 * @return string : 処理された文字列
 */
function h($str){
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');  //特殊文字をHTMLエンティティへ変換して表示。htmlspecialchars( 変換対象文字, 変換内容(ENT_QUOTES = シングル・ダブルクウォートも含める), 文字コード(変換後の文字列) ) 
}

?>