<?php


/**
 * Trait Used in a Class that extends WP_Image_Editor_GD/Imagick Class
 * 
 *  * Define some Variables
 * 
 */


// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
	exit;
}




// 変数を定義
trait StillBE_Image_Editor_Common_Variables {


	// 圧縮品質一覧用の変数
	protected $qualities     = null;
	protected $original_webp = null;


	// 圧縮中のサイズと画像形式、品質
	protected $mk_size   = 'default';
	protected $mk_mime   = '';
	protected $mk_q      = 0;


	// Vars related "cwebp"
	protected $var_cwebp = array();
	protected $is_lossless_options = null;


	// Number of Colors for PNG
	protected $color_num = null;


}




// END of the File



