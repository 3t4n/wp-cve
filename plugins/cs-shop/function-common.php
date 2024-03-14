<?php
/**
 * 共通関数ライブラリ
 * User: cottonspace
 * Date: 12/04/14
 */

/**
 * 入力エスケープ処理(セキュリティ対策)
 * @param string $str 入力文字列
 * @return string 編集文字列
 */
function i_escape($str)
{
    return str_replace(array("\r", "\n", "\0"), "", $str);
}

/**
 * 出力エスケープ処理(セキュリティ対策)
 * @param string $str 入力文字列
 * @param bool $newline 改行コードを <br /> 文字に変換
 * @return string 編集文字列
 */
function o_escape($str, $newline = false)
{
    $str = htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    if ($newline) {
        $str = str_replace(array("\r", "\n"), "<br />", $str);
    }
    return $str;
}

/**
 * GET クエリ文字列要求値を連想配列に設定
 * @param array $params 値を反映する連想配列(参照用)
 */
function getQueryParams(&$params)
{
    foreach ($_GET as $k => $v) {
        $params[$k] = i_escape($v);
    }
}
