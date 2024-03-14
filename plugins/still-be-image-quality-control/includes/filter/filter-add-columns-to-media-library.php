<?php


/**
 * メディアライブラリをリスト表示した時の追加列
 * 
 */


// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
	exit;
}




// Add Columns
add_filter( 'manage_media_columns', function( $columns ) {

	// Attachment ID
	$columns['attachment_id'] = __( 'Attachment ID',    'still-be-image-quality-control' );

	// Compression Info
	$columns['compression']   = __( 'Compression Info', 'still-be-image-quality-control' );

	return $columns;

} );


// Add Custom Values
add_action( 'manage_media_custom_column', function( $column_name, $attachment_id ) {

	// Attachment ID
	if( 'attachment_id' === $column_name ) {
		echo esc_html( $attachment_id );
		return;
	}

	// Compression Info
	if( 'compression' === $column_name ) {
		echo '<div class="button-wrapper"><button type="button" class="show-comp-info" data-id="'. esc_attr( $attachment_id ). '">';
		echo   esc_html__( 'Show Compression Info', 'still-be-image-quality-control' );
		echo '</button></div>';
		echo '<div class="button-wrapper"><button type="button" class="run-recomp" data-id="'. esc_attr( $attachment_id ). '">';
		echo   esc_html__( 'Run Re-Compression', 'still-be-image-quality-control' );
		echo '</button></div>';
		return;
	}

}, 10, 2 );




add_filter( 'manage_upload_sortable_columns', function( $columns ) {

	$columns['attachment_id'] = 'post_id';
	return $columns;

} );




add_action( 'admin_enqueue_scripts', function( $hook_suffix ) {

	if( 'upload.php' !== $hook_suffix ) {
		return;
	}

	// CSS
	wp_enqueue_style(
		'stillbe-iqc-admin-comp-info',
		STILLBE_IQ_BASE_URL. 'asset/comp-info.css',
		array(),
		@filemtime( STILLBE_IQ_BASE_DIR. '/asset/comp-info.css' )
	);

	// Javascript
	wp_enqueue_script(
		'stillbe-iqc-admin-comp-info',
		STILLBE_IQ_BASE_URL.'asset/comp-info.js',
		array(),
		@filemtime( STILLBE_IQ_BASE_DIR. '/asset/comp-info.js' )
	);

	$translate = $GLOBALS['sb-iqc-setting']->set_js_translate( array(
		'Size Name',
		'Size',
		'File Path',
		'Quality Level',
		'WebP File Path',
		'WebP Quality Level',
		'WebP Compression Mode',
		'WebP Lossless Level',
		'Now processing...',
	) );

	$upload = wp_upload_dir();
	wp_add_inline_script(
		'stillbe-iqc-admin-comp-info',
		'window.$stillbe = { admin: { ajaxUrl: "'. esc_url( admin_url( 'admin-ajax.php' ) ). '" } };'.
		'window.$stillbe.admin.nonce = {'.
			'show: "'. esc_html( wp_create_nonce( 'sb-iqc-get-attachment-meta' ) ). '",'.
			'comp: "'. esc_html( wp_create_nonce( 'sb-iqc-regenerate-images'   ) ). '",'.
		'};'.
		'window.$stillbe.admin.action = "sb_iqc_get_attachment_meta";'.
		'window.$stillbe.uploadBaseUrl = "'. esc_url( $upload['baseurl'] ). '";'.
		'window.$stillbe.admin.translate = '. json_encode( $translate ). ';'
	);

} );





// END of the File



