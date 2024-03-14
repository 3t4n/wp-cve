<?php


/**
 * Image Editor の make_subsize メソッド以外の時に WebP を追加するフィルター
 * 
 */


// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
	exit;
}




// アップロード時、オリジナルサイズの画像のWebPを生成する
add_filter( 'wp_handle_upload', function( $upload ) {

	// 画像 (image/*) 以外は何もしない
	// Mime Type は wp_get_image_editor 関数で行われる

	// Image Editor
	$editor = wp_get_image_editor( $upload['file'] );
	if( is_wp_error( $editor ) ) {
		// This image cannot be edited.
		return $upload;
	}

	// Deny a Multibyte Filename
	$new_filename = $editor->generate_safe_filename( $upload['file'], '', true );
	if( $upload['file'] !== $new_filename ) {
		require_once( ABSPATH. 'wp-admin/includes/file.php' );
		global $wp_filesystem;
		if( WP_Filesystem() ) {
			$file_system = &$wp_filesystem;
		} else {
		//	wp_die( __( 'WP Filesystem is not available.', 'still-be-image-quality-control' ) );
		}
		$copied = $file_system->copy( $upload['file'], $new_filename, false );
		if( $copied ) {
			unlink( $upload['file'] );
			// Replace URL
			$new_name = basename( $new_filename );
			$upload['url']  = path_join( dirname( $upload['url'] ), $new_name );
			$upload['file'] = $new_filename;
			$editor = wp_get_image_editor( $upload['file'] );
		}
	}

	// WebP ではスキップ
	$_mime = wp_get_image_mime( $upload['file'] );
	if( 'image/webp' === $_mime ) {
		return $upload;
	}

	// 画像をロード
	$editor->load();

	// WebP 画像を生成
	$editor->_set_mk_size( 'original' );
	add_filter( 'wp_editor_set_quality', array( $editor, '_set_quality_hook' ), 1, 2 );
	add_filter( 'wp_image_resize_identical_dimensions', '__return_true' );
	$editor->make_webp( "{$upload['file']}.webp", array( 'size_name' => 'original' ) );
	remove_filter( 'wp_editor_set_quality', array( $editor, '_set_quality_hook' ), 1 );
	remove_filter( 'wp_image_resize_identical_dimensions', '__return_true' );

	// $editor を削除
	$editor = null;
	unset( $editor );

	// フィルターの値は変更せず返す
	return $upload;

} );




// 大きな画像の自動リサイズ時に -scaled ファイルの WebP を生成する
add_filter( 'update_attached_file', function( $scaled_file ) {

	// Image Editor
	$editor = wp_get_image_editor( $scaled_file );
	if( is_wp_error( $editor ) ) {
		// This image cannot be edited.
		return $scaled_file;
	}

	// WebP ではスキップ
	$_mime = wp_get_image_mime( $scaled_file );
	if( 'image/webp' === $_mime ) {
		return $scaled_file;
	}

	// 画像をロード
	$editor->load();

	// WebP 画像を生成
	$editor->_set_mk_size( 'original' );
	add_filter( 'wp_editor_set_quality', array( $editor, '_set_quality_hook' ), 1, 2 );
	add_filter( 'wp_image_resize_identical_dimensions', '__return_true' );
	$editor->make_webp( "{$scaled_file}.webp", array( 'size_name' => 'original' ) );
	remove_filter( 'wp_editor_set_quality', array( $editor, '_set_quality_hook' ), 1 );
	remove_filter( 'wp_image_resize_identical_dimensions', '__return_true' );

	// $editor を削除
	$editor = null;
	unset( $editor );

	// フィルターの値は変更せず返す
	return $scaled_file;

} );





// END of the File



