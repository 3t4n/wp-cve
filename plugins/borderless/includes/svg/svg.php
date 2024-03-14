<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

//Sanitizes a comma separated CSS selectors with class and id names to ensure it only contains valid characters.
//Complex selectors (for ex. [name*="value"]) are not allowed.
//Allowed characters: A-Z, a-z, 0-9, _, -, .(dot), >,  (space), #, ,(comma)
function borderless_svg_sanitize_css_selectors( $selectors ) {
	$selectors = htmlspecialchars_decode( $selectors );

	//Strip out any % encoded octets
	$sanitized = preg_replace( '|%[a-fA-F0-9][a-fA-F0-9]|', '', $selectors );

	//Limit to A-Z, a-z, 0-9, _, -, .(dot), >,  (space), #, ,(comma)
	$sanitized = preg_replace( '/[^A-Za-z0-9 _,.#>-]/', '', $sanitized );

	//convert the ">" (greater-than) sign to &gt; for storing in a database
	$sanitized = htmlspecialchars($sanitized);

	return apply_filters( 'borderless_svg_sanitize_css_selectors', $sanitized );
}


//Allow SVG through WordPress Media Uploader
function borderless_svg_cc_mime_types($mimes) {
		$mimes['svg'] = 'image/svg+xml';
		$mimes['svgz'] = 'image/svg+xml';
		return $mimes;
}
add_filter('upload_mimes', 'borderless_svg_cc_mime_types');

/**
 * TEMP FIX FOR 4.7.1
 * Issue should be fixed in 4.7.2 in which case this will be deleted.
 */
function borderless_svgs_disable_real_mime_check( $data, $file, $filename, $mimes ) {
	$wp_filetype = wp_check_filetype( $filename, $mimes );

	$ext = $wp_filetype['ext'];
	$type = $wp_filetype['type'];
	$proper_filename = $data['proper_filename'];

	return compact( 'ext', 'type', 'proper_filename' );
}
add_filter( 'wp_check_filetype_and_ext', 'borderless_svgs_disable_real_mime_check', 10, 4 );

//Sanitize SVG code of a file during uploading into media library: remove all JavaScript tags and attributes.
function borderless_sanitize_svg( $file ) {
	if( $file['type'] == 'image/svg+xml' ) {
		
		require_once 'sanitizer.php';

		$svg = new BORDERLESS_SvgSanitizer();
		
		$svg->load_svg( $file['tmp_name'] );
		$svg->borderless_sanitize_svg();
		$sanitized_svg = $svg->save_svg();

		global $wp_filesystem;
		$creds = request_filesystem_credentials( admin_url(), '', FALSE, FALSE, array() );
		if ( ! WP_Filesystem( $creds ) ) {
			request_filesystem_credentials( admin_url(), '', TRUE, FALSE, NULL );
		}
		
		$replaced = $wp_filesystem->put_contents( $file['tmp_name'], $sanitized_svg, FS_CHMOD_FILE );
	}

	return $file;
}
add_filter( 'wp_handle_upload_prefilter', 'borderless_sanitize_svg' );


//Fixing SVG width and height attributes to show correctly in TinyMCE editor
function borderless_svg_fix_svg_size_attributes( $out, $id ) {
	$image_url  = wp_get_attachment_url( $id );
	$file_ext   = pathinfo( $image_url, PATHINFO_EXTENSION );
	if ( ! is_admin() || 'svg' !== $file_ext )
	{
		return false;
	}
	return array( $image_url, null, null, false );
}
add_filter( 'image_downsize', 'borderless_svg_fix_svg_size_attributes', 10, 2 );


//Fixing SVG width and height attributes to show correctly in Media Library in grid mode
function borderless_svg_prepare_attachment_for_js_filter($response, $attachment, $meta){
	if( $response['mime'] == 'image/svg+xml' && empty($response['sizes']) ){
		$svg_file_path = get_attached_file( $attachment->ID );

		$orig_size = borderless_svg_get_original_svg_size( $svg_file_path );

		$response['sizes'] = array(
			'full' => array(
				'url' => $response['url'],
				'width' => $orig_size[0],
				'height' => $orig_size[1]
			)
		);

		$arr = array( 'width' => $orig_size[0], 'height' => $orig_size[1] );
		wp_update_attachment_metadata( $attachment->ID, $arr );
	}
	return $response;
}
//get width and height attributes of uploded SVG
function borderless_svg_get_original_svg_size($file) {
	$arr = array();
	$xml_get = simplexml_load_file($file);
	$xml_attrs = $xml_get->attributes();
	
	$width = (string) $xml_attrs->width; 
	if ( empty($width) ) {
		$width = '100%';
	}
	
	$height = (string) $xml_attrs->height; 
	if ( empty($height) ) {
		$height = '100%';
	}

	$arr[] = $width;
	$arr[] = $height;

	return $arr;
}
add_filter('wp_prepare_attachment_for_js', 'borderless_svg_prepare_attachment_for_js_filter', 10, 3);

//Define styles and scripts for site's front-end

function borderless_svg_scripts() {
	wp_enqueue_script( 'borderless_svg_js', plugins_url( '/svg.min.js', __FILE__ ), array('jquery'), BORDERLESS__VERSION, true );
	
	wp_enqueue_script( 'borderless_svg_js' );
}
add_action('wp_enqueue_scripts', 'borderless_svg_scripts');


?>