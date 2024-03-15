<?php

defined( 'ABSPATH' ) or exit;

/**
 * Set up WPML Compatibiblity Class.
 */
class Xpro_Theme_Builder_WPML_Compatibility {

	/**
	 * Instance of Xpro_Theme_Builder_WPML_Compatibility.
	 *
	 * @since  1.0.9
	 * @var null
	 */
	private static $_instance = null;

	/**
	 * Get instance of Xpro_Theme_Builder_WPML_Compatibility
	 *
	 * @since  1.0.9
	 * @return Xpro_Theme_Builder_WPML_Compatibility
	 */
	public static function instance() {
		if ( ! isset( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Setup actions and filters.
	 *
	 * @since  1.0.9
	 */
	private function __construct() {
		add_filter( 'xpro_theme_builder_get_settings_type_header', array( $this, 'get_wpml_object' ) );
		add_filter( 'xpro_theme_builder_get_settings_type_footer', array( $this, 'get_wpml_object' ) );
		add_filter( 'xpro_theme_builder_get_settings_type_singular', array( $this, 'get_wpml_object' ) );
		add_filter( 'xpro_theme_builder_get_settings_type_archive', array( $this, 'get_wpml_object' ) );
		add_filter( 'xpro_theme_builder_render_template_id', array( $this, 'get_wpml_object' ) );
	}

	/**
	 * Pass the final header and footer ID from the WPML's object filter to allow strings to be translated.
	 *
	 * @since  1.0.9
	 * @param  Int $id  Post ID of the template being rendered.
	 * @return Int $id  Post ID of the template being rendered, Passed through the `wpml_object_id` id.
	 */
	public function get_wpml_object( $id ) {
		$translated_id = apply_filters( 'wpml_object_id', $id );

		if ( defined( 'POLYLANG_BASENAME' ) ) {

			if ( null === $translated_id ) {

				// The current language is not defined yet or translation is not available.
				return $id;
			} else {

				// Return translated post ID.
				return $translated_id;
			}
		}

		if ( null === $translated_id ) {
			$translated_id = '';
		}

		return $translated_id;
	}
}

/**
 * Initiate the class.
 */
Xpro_Theme_Builder_WPML_Compatibility::instance();
