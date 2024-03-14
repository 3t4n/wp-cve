<?php


/**
 * 各種フィルター処理
 * 
 */


// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
	exit;
}




// 画像エディタに追加したClassを加える
require_once( __DIR__. '/filter/filter-add-extends-editor-classes.php' );


// Image Editor の make_subsize メソッド以外の時に WebP を追加する
require_once( __DIR__. '/filter/filter-make-webp-without-subsize-method.php' );


// メディアライブラリをリスト表示した時の追加列
require_once( __DIR__. '/filter/filter-add-columns-to-media-library.php' );


// 画像キャッシュクリア用のクエリ文字列を追加する
require_once( __DIR__. '/filter/filter-add-image-cache-clear-query.php' );


// その他の補助的な処理
require_once( __DIR__. '/filter/filter-other-supplementals.php' );




// END of the File



