<?php
/**
 * Load Public assets.
 *
 * @package     EverAccounting
 * @version     1.0.2
 */

namespace EverAccounting;

defined( 'ABSPATH' ) || exit();

/**
 * Class Assets
 *
 * @since 1.0.2
 */
class Assets {

	/**
	 * Assets constructor.
	 */
	public function __construct() {
		add_action( 'eaccounting_head', array( $this, 'public_styles' ) );
		add_action( 'eaccounting_footer', array( $this, 'public_scripts' ) );
	}

	/**
	 * Load public styles.
	 *
	 * @since 1.1.0
	 */
	public function public_styles() {
		$version = eaccounting()->get_version();
		wp_register_style( 'ea-public-styles', eaccounting()->plugin_url() . '/dist/css/public.min.css', array( 'common', 'buttons' ), $version );
		wp_print_styles( 'ea-public-styles' );
	}

	/**
	 * Load public scripts
	 *
	 * @since 1.1.0
	 */
	public function public_scripts() {
		$suffix  = '';
		$version = eaccounting()->get_version();
	}
}

new Assets();
