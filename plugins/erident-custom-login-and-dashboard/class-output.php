<?php
/**
 * Setup Custom Login Dashboard output.
 *
 * @package Custom_Login_Dashboard
 */

namespace CustomLoginDashboard;

use ariColor;

/**
 * Setup Better Admin Bar output.
 */
class Output {

	/**
	 * The class instance.
	 *
	 * @var object
	 */
	public static $instance;

	/**
	 * Get instance of the class.
	 */
	public static function get_instance() {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;

	}

	/**
	 * Init the class setup.
	 */
	public static function init() {
		self::$instance = new self();

		add_action( 'plugins_loaded', array( self::$instance, 'setup' ) );
	}

	/**
	 * Setup action & filter hooks.
	 */
	public function __construct() {}

	/**
	 * Setup action & filters.
	 */
	public function setup() {

		// Left side of the admin area's footer text.
		add_filter( 'admin_footer_text', [ $this, 'left_footer_text' ] );

		// Right side of the admin area's footer text.
		add_filter( 'update_footer', [ $this, 'right_footer_text' ], 11 );

		add_action( 'login_head', [ $this, 'print_login_styles' ] );

		add_filter( 'login_headerurl', [ $this, 'login_logo_url' ] );
		add_filter( 'login_headertext', [ $this, 'login_logo_title' ] );

	}

	/**
	 * Filters the “Thank you” text displayed in the admin footer.
	 *
	 * @param string $text The existing footer text.
	 * @return string The modified footer text.
	 */
	public function left_footer_text( $text ) {

		$settings = get_option( 'plugin_erident_settings', [] );
		$text     = isset( $settings['dashboard_data_left'] ) && ! empty( $settings['dashboard_data_left'] ) ? $settings['dashboard_data_left'] : $text;

		return stripslashes( $text );

	}

	/**
	 * Filters the version/update text displayed in the admin footer.
	 *
	 * @param string $content The content that will be printed.
	 */
	public function right_footer_text( $content ) {

		$settings = get_option( 'plugin_erident_settings', [] );
		$text     = isset( $settings['dashboard_data_right'] ) && ! empty( $settings['dashboard_data_right'] ) ? $settings['dashboard_data_right'] : $content;

		return stripslashes( $text );

	}

	/**
	 * Print login styles.
	 */
	public function print_login_styles() {

		$settings  = get_option( 'plugin_erident_settings', [] );
		$print_css = require __DIR__ . '/inc/login.css.php';

		echo '<style>';

		ob_start();
		$print_css( $settings );
		$output = ob_get_clean();
		echo $output;

		echo '</style>';

	}

	/**
	 * Change login logo URL.
	 */
	public function login_logo_url() {

		return get_bloginfo( 'url' );

	}

	/**
	 * Change login logo title.
	 *
	 * @param string $headertext The existing header text.
	 * @return string
	 */
	public function login_logo_title( $headertext ) {

		$settings   = get_option( 'plugin_erident_settings' );
		$logo_title = isset( $settings['dashboard_power_text'] ) && ! empty( $settings['dashboard_power_text'] ) ? $settings['dashboard_power_text'] : $headertext;

		return $logo_title;

	}

}
