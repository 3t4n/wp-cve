<?php


/**
 * その他の補助的なフィルター
 * 
 */


// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
	exit;
}




// make_subsize メソッドに渡されるサイズデータにサイズ名を追加する
function _stillbe_image_subsizes_size_name( $new_sizes ) {

	if( ! is_array( $new_sizes ) ) {
		return array();
	}

	foreach( $new_sizes as $size => $data ) {
		$new_sizes[ $size ]['size_name'] = $size;
	}

	return $new_sizes;

}
add_filter( 'intermediate_image_sizes_advanced', '_stillbe_image_subsizes_size_name', 9999 );
add_filter( 'wp_get_missing_image_subsizes',     '_stillbe_image_subsizes_size_name', 9999 );




// Mime-Type に WebP を追加する
add_filter( 'mime_types', function( $list ) {

	$new_list = $list;

	if( empty( $new_list['webp'] ) ) {
		$new_list['webp'] = 'image/webp';
	}

	return $new_list;

} );




// WordPress 5.8 以前の場合は、アップロード可能な mime-type から image/webp を除外する
add_filter( 'upload_mimes', function( $list ) {

	global $wp_version;

	if( version_compare( $wp_version, '5.8', '>=' ) ) {
		return $list;
	}

	unset( $list['webp'] );

	return $list;

} );




// 画像削除する時に対応するWebPがあれば一緒に削除する
add_filter( 'wp_delete_file', function( $filename ) {

	if( empty( $filename ) ||
	      ! preg_match( '/^(jpe?g|png|gif)$/i', pathinfo( $filename, PATHINFO_EXTENSION ) ) ) {
		return $filename;
	}

	$_webp = $filename. '.webp';

	if( file_exists( $_webp ) ) {
		@unlink( $_webp );
	}

	return $filename;

} );




// 画像サイズ (additional_image_sizes) の連想配列のキーからスペースを削除する
add_filter( 'intermediate_image_sizes_advanced', function( $new_sizes ) {

	$sizes = array();

	foreach( $new_sizes as $name => $size ) {
		$_name = trim( $name );
		$sizes[ $_name ] = array();
		foreach( $size as $property => $value ) {
			$sizes[ $_name ][ trim( $property ) ] = $value;
		}
	}

	return $sizes;

}, 9999 );





// END of the File



