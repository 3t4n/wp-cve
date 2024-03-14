<?php
/* これは文字化け防止のための日本語文字列です。
   このソースファイルは UTF-8 で保存されています。
   Above is a Japanese strings to avoid charset mis-understanding.
   This source file is saved with UTF-8. */
/*
Plugin Name: PC Ktai content selecter
Plugin URI: http://musilog.net/
Description: 指定した範囲をPCにだけ、携帯にだけ出力するように制御するショートコードを生成するプラグイン。
Author: wackey
Version: 0.1
Author URI: http://musilog.net/
*/

// is_ktai()

function pccontent_func( $atts, $content = null ) {
if ((! function_exists('is_mobile') || ! is_mobile()) && (! function_exists('is_ktai')   || ! is_ktai())) {
	return $content;
} else {
	return "";
}
}

function ktaicontent_func( $atts, $content = null ) {
if ((! function_exists('is_mobile') ||  is_mobile()) && (! function_exists('is_ktai')   ||  is_ktai())) {
	return $content;
} else {
	return "";
}
}


add_shortcode( 'pccontent', 'pccontent_func' );
add_shortcode( 'ktaicontent', 'ktaicontent_func' );




?>