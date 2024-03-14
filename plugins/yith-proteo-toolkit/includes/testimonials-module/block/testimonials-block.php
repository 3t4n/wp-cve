<?php
/**
 * Testimonials Gutenberg Block
 *
 * @since 1.1.0
 * @package YITH_Proteo_tookit
 */

add_action( 'init', 'yith_proteo_toolkit_testimonials_register_block' );
/**
 * Register block
 *
 * @return void
 */
function yith_proteo_toolkit_testimonials_register_block() {
	// Skip block registration if Gutenberg is not enabled/merged.
	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}

	$index_js = 'index.js';
	wp_register_script(
		'yith-proteo-toolkit-testimonials-block-script',
		plugins_url( $index_js, __FILE__ ),
		array(
			'wp-blocks',
			'wp-i18n',
			'wp-element',
			'wp-components',
		),
		YITH_PROTEO_TOOLKIT_VERSION,
		true
	);

	$localize = array(
		'testimonials_list'     => yith_proteo_testimonials_get_testimonials_names_list( 'array' ),
		'testimonials_tax_list' => yith_proteo_testimonials_get_taxonomies_list(),
		'elements_to_show'      => yith_proteo_toolkit_testimonials_get_list_of_elements_to_show(),
	);

	wp_localize_script( 'yith-proteo-toolkit-testimonials-block-script', 'yith_proteo_toolkit_testimonials_block_localized_array', $localize );

	register_block_type(
		'yith-proteo-toolkit/testimonials-block',
		array(
			'editor_script'   => 'yith-proteo-toolkit-testimonials-block-script',
			'render_callback' => 'yith_proteo_toolkit_proteo_testimonials_sc',
			'attributes'      => array(
				'names'    => array(
					'type'    => 'array',
					'default' => array(),
				), // comma separated list of testimonial names.
				'count'    => array(
					'type'    => 'numeric',
					'default' => -1,
				), // number of testimonials to show.
				'layout'   => array(
					'type'    => 'string',
					'default' => 'grid',
				), // grid or list.
				'elements' => array(
					'type'    => 'array',
					'default' => array(),
				), // comma separated list of testimonial elements to show.
			),
		)
	);
}

add_action( 'enqueue_block_editor_assets', 'yith_proteo_toolkit_testimonials_enqueue_block_scripts', 99 );

/**
 * Enqueue scripts to render the block correctly
 *
 * @return void
 */
function yith_proteo_toolkit_testimonials_enqueue_block_scripts() {
	$suffix = 'true' === SCRIPT_DEBUG ? '' : '.min';
	wp_enqueue_style( 'yith_proteo_testimonials_shortcode_style', YITH_PROTEO_TOOLKIT_URL . 'includes/testimonials-module/assets/testimonials.css', array(), YITH_PROTEO_TOOLKIT_VERSION );
	wp_enqueue_script( 'yith_proteo_testimonials_shortcode_js', YITH_PROTEO_TOOLKIT_URL . 'includes/testimonials-module/assets/testimonials.js', array( 'jquery' ), YITH_PROTEO_TOOLKIT_VERSION, true );

	wp_enqueue_style( 'select2', get_template_directory_uri() . '/third-party/select2' . $suffix . '.css', array(), '4.0.13' );
	if ( function_exists( 'WC' ) ) {
		wp_enqueue_script( 'selectWoo' );
	} else {
		wp_enqueue_script( 'select2', get_template_directory_uri() . '/third-party/select2' . $suffix . '.js', array(), '4.0.13', true );
	}

	wp_enqueue_script( 'proteo_testimonials_admin_js', YITH_PROTEO_TOOLKIT_URL . 'includes/testimonials-module/assets/testimonials-admin.js', array(), YITH_PROTEO_TOOLKIT_VERSION, true );

	wp_localize_script(
		'yith_proteo_testimonials_shortcode_js',
		'yith_proteo_testimonials',
		array(
			'read_more_button_text' => esc_html_x( 'Read more', 'Testimonials shortcode expand link', 'yith-proteo-toolkit' ),
			'read_less_button_text' => esc_html_x( 'Read less', 'Testimonials shortcode expand link', 'yith-proteo-toolkit' ),
		)
	);
}
