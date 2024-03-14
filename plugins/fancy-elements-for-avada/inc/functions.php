<?php
/**
 * File contains the general functions of the plugin.
 *
 * @author    WP Square
 * @package   fancy-elements-avada
 */

/**
 * Initiate the plugin code.
 *
 * @since 1.0
 */
function fea_shortcode_init_options() {

	// Early exit if the Fusion_Element class does not exist.
	if ( ! class_exists( 'Fusion_Element' ) ) {
		return;
	}

	// Instantiate the object.
	new FEA_Element_Options();
}
add_action( 'fusion_builder_shortcodes_init', 'fea_shortcode_init_options', 1 );
