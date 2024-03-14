<?php
/**
 * Main Watchful class.
 *
 * @version   2016-12-20 11:41 UTC+01
 * @package   Watchful WP Client
 * @author    Watchful
 * @authorUrl https://watchful.net
 * @copyright Copyright (c) 2020 watchful.net
 * @license   GNU/GPL
 */

namespace Watchful;

/**
 * Main Watchful class.
 */
class Main {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since 0.1
	 *
	 * @var string
	 */
	public $version = '0.1';

	/**
	 * Plugin file.
	 *
	 * @since 0.1
	 *
	 * @var string
	 */
	public $file = __FILE__;

	/**
	 * Constructor
	 */
	public function __construct() {} // end constructor

	/**
	 * Initializes the plugin by setting localization, filters, and administration functions.
	 */
	public function init() {
		// Check to see if the page has been posted too.
		add_action( 'wp', array( $this, 'watchful_page_posted' ) );

		// Add Message after activation.
		add_action( 'admin_notices', array( $this, 'watchful_admin_notice' ) );
		add_action( 'network_admin_notices', array( $this, 'watchful_admin_notice' ) );

	}

	/**
	 * Checks to see if the page has been posted too.
	 *
	 * @since 0.1
	 */
	public function watchful_page_posted() {

		$request_method = ! empty( $_SERVER['REQUEST_METHOD'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_METHOD'] ) ) : '';
		$current_page   = ! empty( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : ''; // WPCS: CSRF ok.

		if ( 'POST' === strtoupper( $request_method ) && 'watchful-setting' === $current_page ) {

			$disable_timestamp = ! empty( $_POST['watchful_disable_timestamp'] ) ? 1 : 0; // WPCS: CSRF ok.
			$maintenance       = ! empty( $_POST['watchful_disable_timestamp'] ) ? 1 : 0; // WPCS: CSRF ok.

			$settings                               = get_option( 'watchfulSettings' );
			$settings['watchful_disable_timestamp'] = $disable_timestamp;
			$settings['watchful_maintenance']       = $maintenance;

			update_option( 'watchfulSettings', $settings );
		}

	}

	/**
	 * Display admin notice.
	 *
	 * @since 0.1
	 */
	public function watchful_admin_notice() {

		// Make sure the plugin is activated.
		if ( is_plugin_active( 'watchful/watchful.php' ) ) {
			global $pagenow;

			// Only need to display this on the plugin view page.
			if ( 'plugins.php' !== $pagenow || ! current_user_can( 'install_plugins' ) || ! get_option( 'watchfulMessage' ) ) {
				return;
			}
			?>
			<div id="message" class="updated notice is-dismissible">
				<?php
				$my_settings_page = new Settings();
				$my_settings_page->print_watchful_form();
				?>
				<br />
			</div>
			<?php

		}
	}

} // end Watchful_Main Class
