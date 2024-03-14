<?php
/**
 * Localization loader.
 *
 */


defined( 'ABSPATH' ) || exit;

/**
 * Localizationclass.
 */
class REVIVESO_Localization extends REVIVESO_BaseController
{
	use REVIVESO_Hooker;

	/**
	 * Register functions.
	 */
	public function register() {
		$this->action( 'plugins_loaded', 'load_textdomain' );
	}

	/**
     * Initialize plugin for localization.
     *
     * Note: the first-loaded translation file overrides any following ones if the same translation is present.
     */
	public function load_textdomain() {
		load_plugin_textdomain( 'revive-so', false, dirname( $this->plugin ) . '/languages/' ); 
	}
}