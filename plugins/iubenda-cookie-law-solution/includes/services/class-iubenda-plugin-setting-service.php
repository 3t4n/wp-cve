<?php
/**
 * Iubenda plugin setting service.
 *
 * @package  Iubenda
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class plugin setting service.
 */
class Iubenda_Plugin_Setting_Service extends Iubenda_Abstract_Product_Service {

	/**
	 * Accepted plugin setting Options.
	 *
	 * @var array
	 */
	private $default_options = array(
		'ctype'                      => true,
		'output_feed'                => true,
		'output_post'                => true,
		'menu_position'              => 'topmenu',
		'deactivation'               => false,
		'stop_showing_cs_for_admins' => false,
	);

	/**
	 * Accepted plugin setting Options.
	 *
	 * @var array
	 */
	private $accepted_options = array(
		'menu_position' => array( 'topmenu', 'submenu' ),
	);

	/**
	 * Saving iubenda plugin settings
	 *
	 * @param   bool $default_options  If true insert the default options.
	 */
	public function plugin_settings_save_options( $default_options = true ) {
		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		$new_options                  = (array) iub_array_get( $_POST, 'iubenda_plugin_settings', array() );
		$new_options                  = array_map( 'sanitize_key', $new_options );
		$plugin_settings_default_keys = array_keys( $this->default_options );
		$new_options                  = iub_array_only( $new_options, $plugin_settings_default_keys );

		if ( $default_options ) {
			$new_options = wp_parse_args( $new_options, $this->default_options );
		} else {
			$new_options['ctype']         = isset( $new_options['ctype'] );
			$new_options['output_feed']   = isset( $new_options['output_feed'] );
			$new_options['output_post']   = isset( $new_options['output_post'] );
			$new_options['deactivation']  = isset( $new_options['deactivation'] );
			$new_options['menu_position'] = $this->get_only_valid_values( iub_array_get( $new_options, 'menu_position' ), $this->accepted_options['menu_position'], $this->default_options['menu_position'] );
		}

		$old_cs_options = $this->iub_strip_slashes_deep( iubenda()->options['cs'] );
		$new_cs_option  = array_merge( $old_cs_options, $new_options );

		iubenda()->options['cs'] = $new_cs_option;
		iubenda()->iub_update_options( 'iubenda_cookie_law_solution', $new_cs_option );
	}
}
