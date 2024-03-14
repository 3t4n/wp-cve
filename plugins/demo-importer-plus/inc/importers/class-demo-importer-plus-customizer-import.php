<?php
/**
 * Customizer Data importer class.
 *
 * @since  1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Customizer Data importer class.
 *
 * @since  1.0.0
 */
class Demo_Importer_Customizer_Import {

	/**
	 * Instance of Demo_Importer_Customizer_Import
	 *
	 * @since  1.0.0
	 * @var Demo_Importer_Customizer_Import
	 */
	private static $instance = null;

	/**
	 * Instantiate Demo_Importer_Customizer_Import
	 *
	 * @since  1.0.0
	 * @return (Object) Demo_Importer_Customizer_Import
	 */
	public static function instance() {

		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Import customizer options.
	 *
	 * @since  1.0.0
	 *
	 * @param  (Array) $options customizer options from the demo.
	 */
	public function import( $options ) {

		// Update Demo Importer Theme customizer settings.
		if ( isset( $options ) ) {
			self::import_settings( $options );
		}

		// Add Custom CSS.
		if ( isset( $options['custom-css'] ) ) {
			wp_update_custom_css_post( $options['custom-css'] );
		}

	}

	/**
	 * Import Demo Importer Plus Sites Setting's
	 *
	 * Download & Import images from  Demo Importer Plus Customizer Settings.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $options  Demo Importer Plus Customizer setting array.
	 * @return void
	 */
	public static function import_settings( $options = array() ) {

		array_walk_recursive(
			$options,
			function ( &$value ) {
				if ( ! is_array( $value ) ) {

					if ( Demo_Importer_Plus_Sites_Helper::is_image_url( $value ) ) {
						$data = Demo_Importer_Plus_Sites_Helper::sideload_image( $value );

						if ( ! is_wp_error( $data ) ) {
							$value = $data->url;
						}
					}
				}
			}
		);

		if ( isset( $options['custom_logo'] ) ) {
			if ( Demo_Importer_Plus_Sites_Helper::is_image_url( $options['custom_logo'] ) ) {
				$data = Demo_Importer_Plus_Sites_Helper::sideload_image( $options['custom_logo'] );

				if ( ! is_wp_error( $data ) ) {
					$options['custom_logo'] = $data->attachment_id;
				}
			}
		}

		// Updated settings.
		update_option( 'demo-importer-plus-settings', $options );

		$theme_name = get_option( 'stylesheet' );

		// Update theme mods.
		update_option( 'theme_mods_' . $theme_name, $options );
	}
}
