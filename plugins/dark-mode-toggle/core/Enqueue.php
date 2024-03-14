<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Enqueue service class.
 *
 * @package Dark_Mode_Toggle
 */

namespace DarkModeToggle;

defined( 'ABSPATH' ) || die();

/**
 * Register and enqueue front styles and scripts.
 */
class Enqueue extends Base implements Service {
	/**
	 * Initialize service.
	 */
	public function init() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );
	}

	/**
	 * Enqueue styles and scripts.
	 */
	public function enqueue() {
		$front = darkmodetg( 'options' )->get_main( 'front' );

		if ( $front['enable'] && ! is_customize_preview() ) {
			darkmodetg( 'utility' )->enqueue( $front );
		}
	}
}
