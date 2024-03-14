<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Translation service class.
 *
 * @package Dark_Mode_Toggle
 */

namespace DarkModeToggle;

defined( 'ABSPATH' ) || die();

/**
 * Load plugin translations.
 */
class Translation extends Base implements Service {
	/**
	 * Initialize service.
	 */
	public function init() {
		add_action( 'init', array( $this, 'load_textdomain' ) );
	}

	/**
	 * Load translated strings.
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'dark-mode-toggle', false, basename( $this->plugin_path ) . '/languages' );
	}
}
