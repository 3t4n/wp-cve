<?php
/**
 * Enable json upload
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'wpkoi_templates_for_elementor_enable_json' ) ) {
	function wpkoi_templates_for_elementor_enable_json() {
		
		if ( !class_exists('Elementor\Core\Files\Uploads_Manager')) {
			return;
		}
		
		$fileuploadon = get_option( Elementor\Core\Files\Uploads_Manager::UNFILTERED_FILE_UPLOADS_KEY );
		if ( $fileuploadon == 1 ) {
			return;
		}
		
		update_option( Elementor\Core\Files\Uploads_Manager::UNFILTERED_FILE_UPLOADS_KEY, 1 );
	}
}

wpkoi_templates_for_elementor_enable_json();
