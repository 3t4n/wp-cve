<?php

if ( ! function_exists( 'qi_addons_for_elementor_include_image_sizes' ) ) {
	/**
	 * Function that includes icons
	 */
	function qi_addons_for_elementor_include_image_sizes() {
		foreach ( glob( QI_ADDONS_FOR_ELEMENTOR_INC_PATH . '/media/*/include.php' ) as $image_size ) {
			include_once $image_size;
		}
	}

	add_action( 'qi_addons_for_elementor_action_framework_before_images_register', 'qi_addons_for_elementor_include_image_sizes' );
}
