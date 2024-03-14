<?php


/**
 * デフォルトの品質設定を返す関数
 * 
 */


// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
	exit;
}




function _stillbe_get_quality_level_array() {

	return array(
		'thumbnail_jpeg'    => 65,
		'thumbnail_png'     => 9,
		'thumbnail_webp'    => 65,
		'medium_jpeg'       => 75,
		'medium_png'        => 9,
		'medium_webp'       => 72,
		'medium_large_jpeg' => 80,
		'medium_large_png'  => 9,
		'medium_large_webp' => 80,
		'large_jpeg'        => 80,
		'large_png'         => 9,
		'large_webp'        => 82,
		'1536x1536_jpeg'    => 85,
		'1536x1536_png'     => 9,
		'1536x1536_webp'    => 86,
		'2048x2048_jpeg'    => 90,
		'2048x2048_png'     => 9,
		'2048x2048_webp'    => 92,
		'default_jpeg'      => 82,
		'default_png'       => 9,
		'default_webp'      => 86,
		'original_webp'     => 92,
	);

}




// END of the File



