<?php
/**
 * Module starter file
 *
 * @since 1.1.0
 * @package YITH_Proteo_tookit
 */

// Define module name and params.
$module_params = array(
	'module_name'     => esc_html_x( 'Testimonials', 'Toolkit module name', 'yith-proteo-toolkit' ),
	'module_slug'     => 'testimonials', // should be the same of the folder.
	'post_type'       => 'proteo_testimonials', // the post type to be registered, if any.
	'taxonomy'        => 'proteo_testimonials_tax', // the custom taxonomy to be registered, if any.
	'can_be_disabled' => true,
);

if ( array_key_exists( 'post_type', $module_params ) && '' !== $module_params['post_type'] ) {

	// Post type definition.
	require_once YITH_PROTEO_TOOLKIT_PATH . 'includes/testimonials-module/post-types/' . $module_params['module_slug'] . '.php';

	// Post type metaboxes.
	require_once YITH_PROTEO_TOOLKIT_PATH . 'includes/testimonials-module/post-types/class-proteo-testimonials-metabox.php';

}

require_once YITH_PROTEO_TOOLKIT_PATH . 'includes/testimonials-module/shortcodes/shortcodes.php';

require_once YITH_PROTEO_TOOLKIT_PATH . 'includes/testimonials-module/block/testimonials-block.php';

require_once YITH_PROTEO_TOOLKIT_PATH . 'includes/testimonials-module/utils.php';

add_action( 'wp_enqueue_scripts', 'yith_proteo_testimonials_shortcode_style' );

/**
 * Enqueue frontend style
 *
 * @return void
 */
function yith_proteo_testimonials_shortcode_style() {
	wp_enqueue_style( 'yith_proteo_testimonials_shortcode_style', YITH_PROTEO_TOOLKIT_URL . 'includes/testimonials-module/assets/testimonials.css', array(), YITH_PROTEO_TOOLKIT_VERSION );
	wp_enqueue_script( 'yith_proteo_testimonials_shortcode_js', YITH_PROTEO_TOOLKIT_URL . 'includes/testimonials-module/assets/testimonials.js', array( 'jquery', 'selectWoo' ), YITH_PROTEO_TOOLKIT_VERSION, true );

	wp_localize_script(
		'yith_proteo_testimonials_shortcode_js',
		'yith_proteo_testimonials',
		array(
			'read_more_button_text' => esc_html_x( 'Read more', 'Testimonials shortcode expand link', 'yith-proteo-toolkit' ),
			'read_less_button_text' => esc_html_x( 'Read less', 'Testimonials shortcode expand link', 'yith-proteo-toolkit' ),
		)
	);
}
