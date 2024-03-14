<?php
$wordpress_version = get_bloginfo('version');

function directorypress_allow_svg_uploads( $existing_mime_types = array() ) {
	return $existing_mime_types + array( 'svg' => 'image/svg+xml' );
}

function directorypress_get_dimensions( $svg ) {
	$fail = (object) array( 'width' => 0, 'height' => 0 );

	if ( ! function_exists( 'simplexml_load_file' ) ) {
		return $fail;
	}

	$svg = simplexml_load_file( $svg );
	$attributes = $svg ? $svg->attributes() : false;

	// Probably an invalid XML file?
	if( ! $attributes ) {
		return $fail;
	}

	$width = (string) $attributes->width;
	$height = (string) $attributes->height;

	return (object) array( 'width' => $width, 'height' => $height );
}

function directorypress_set_dimensions( $response, $attachment, $meta ) {
	if( $response['mime'] == 'image/svg+xml' && empty( $response['sizes'] ) ) {
		$svg_file_path = get_attached_file( $attachment->ID );
		$dimensions = directorypress_get_dimensions( $svg_file_path );

		$response[ 'sizes' ] = array(
				'full' => array(
					'url' => $response[ 'url' ],
					'width' => $dimensions->width,
					'height' => $dimensions->height,
					'orientation' => $dimensions->width > $dimensions->height ? 'landscape' : 'portrait'
			)
		);
	}

	return $response;
}


function directorypress_administration_styles() {
	// Media Listing Fix
	wp_add_inline_style( 'wp-admin', ".media .media-icon img[src$='.svg'] { width: auto; height: auto; }" );
	// Featured Image Fix
	wp_add_inline_style( 'wp-admin', "#postimagediv .inside img[src$='.svg'] { width: 100%; height: auto; }" );
}

function directorypress_public_styles() {
	// Featured Image Fix
	echo "<style>.post-thumbnail img[src$='.svg'] { width: 100%; height: auto; }</style>";
}


function directorypress_disable_real_mime_check( $data, $file, $filename, $mimes ) {
	$wp_filetype = wp_check_filetype( $filename, $mimes );

	$ext = $wp_filetype['ext'];
	$type = $wp_filetype['type'];
	$proper_filename = $data['proper_filename'];

	return compact( 'ext', 'type', 'proper_filename' );
}

if($wordpress_version < "4.7.3") {
	add_filter( 'wp_check_filetype_and_ext', 'directorypress_disable_real_mime_check', 10, 4 );
}
add_filter( 'upload_mimes', 'directorypress_allow_svg_uploads' );
add_filter( 'wp_prepare_attachment_for_js', 'directorypress_set_dimensions', 10, 3 );
add_action( 'admin_enqueue_scripts', 'directorypress_administration_styles' );
add_action( 'wp_head', 'directorypress_public_styles' );

?>
