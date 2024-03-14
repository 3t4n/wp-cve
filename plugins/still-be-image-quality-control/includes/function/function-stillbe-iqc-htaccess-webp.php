<?php


/**
 * Set ".htaccess" to Automatically use WebP in WebP Compatible Browsers
 * 
 */


// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
	exit;
}




function _stillbe_iqc_htaccess_webp( $enabled = null ) {

	// Initialize WP_FileSystem
	require_once( ABSPATH. 'wp-admin/includes/file.php' );
	global $wp_filesystem;
	if( WP_Filesystem() ) {
		$file_system = &$wp_filesystem;
	} else {
		wp_die( __( 'WP Filesystem is not available.', 'still-be-image-quality-control' ) );
	}

	// Uploads Directory
	$uploads = wp_upload_dir();
	$up_dir  = $uploads['basedir'];

	// 
	if( null === $enabled ) {
		$enabled = apply_filters( 'stillbe_image_quality_control_enable_webp', STILLBE_IQ_ENABLE_WEBP, 'htaccess' );
	}

	if( $enabled ) {

		if( $up_dir ) {
			// File Path
			$up_file   = $up_dir. '/.htaccess';
			$base_file = STILLBE_IQ_BASE_DIR. '/asset/htaccess.tmp';
			if( file_exists( $up_file ) ) {
				$htaccess = $file_system->get_contents( $up_file );
				$base     = $file_system->get_contents( $base_file );
				if( false === $htaccess || false === $base ) {
					wp_die( __( 'Server error. Could not get &quot;.htaccess&quot;.', 'still-be-image-quality-control' ) );
				}
				$htaccess  = preg_replace( '|[\n\r]*# Replace to WebP[\s\S]*?# /Replace to WebP[\n\r]*|', '', $htaccess );
				$htaccess .= "\n\n". $base;
				// Replace
				$file_system->put_contents( $up_file, $htaccess );
			} else {
				$file_system->copy( $base_file, $up_file );
			}
		}

	} else {

		if( $up_dir ) {
			// File Path
			$up_file   = $up_dir. '/.htaccess';
			$base_file = STILLBE_IQ_BASE_DIR. '/.htaccess';
			if( file_exists( $up_file ) ) {
				$htaccess = $file_system->get_contents( $up_file );
				$base     = $file_system->get_contents( $base_file );
				if( isset( $htaccess ) && isset( $base ) ) {
					$htaccess  = preg_replace( '|[\n\r]*# Replace to WebP[\s\S]*?# /Replace to WebP[\n\r]*|', '', $htaccess );
					// Replace
					$file_system->put_contents( $up_file, $htaccess. "\n" );
				}
			}
		}

	}

}




// END of the File



