<?php
/**
 * Plugin Name: Xpro Addons For Beaver Builder - Lite
 * Plugin URI: https://beaver.wpxpro.com/
 * Description: Extend your Beaver builder functionality with several creative, content, media, social, and woo-commerce modules.
 * Version: 1.5.2
 * Author: Xpro
 * Author URI: https://www.wpxpro.com/
 * License: GPL3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 */

define( 'XPRO_ADDONS_FOR_BB_BASE', plugin_basename( __FILE__ ) );
define( 'XPRO_ADDONS_FOR_BB_DIR', plugin_dir_path( __FILE__ ) );
define( 'XPRO_ADDONS_FOR_BB_URL', plugins_url( '/', __FILE__ ) );
define( 'XPRO_ADDONS_FOR_BB_VERSION', '1.5.1' );

final class Xpro_Addons_for_BB {

	/**
	 * Minimum PHP Version
	 *
	 * @since 1.0.0
	 * @var string Minimum PHP version required to run the plugin.
	 */
	const MINIMUM_PHP_VERSION = '7.0';

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {

		// Load translation
		add_action( 'init', array( $this, 'i18n' ) );

		// Init Plugin
		add_action( 'plugins_loaded', array( $this, 'init' ) );

		//Fires when Xpro Beaver Addons was fully loaded
		do_action( 'xpro_addons_for_bb_loaded' );
	}

	/**
	 * Load Textdomain
	 *
	 * Load plugin localization files.
	 * Fired by `init` action hook.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function i18n() {
		load_plugin_textdomain( 'xpro-bb-addons' );
	}

	/**
	 * Initialize the plugin
	 *
	 * Validates that Beaver is already loaded.
	 * Checks for basic plugin requirements, if one check fail don't continue,
	 * if all check have passed include the plugin class.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function init() {

		// Check if Beaver installed and activated
		if ( ! class_exists( 'FLBuilder' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_missing_main_plugin' ) );
			return;
		}

		// Check for required PHP version
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_php_version' ) );
			return;
		}

		// Once we get here, We have passed all validation checks so we can safely include our plugin
		require_once XPRO_ADDONS_FOR_BB_DIR . 'classes/class-xpro-addons-for-bb-init.php';
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have Beaver installed or activated.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_missing_main_plugin() {

		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$screen = get_current_screen();

		if ( 'plugins' === $screen->base ) {
			if ( file_exists( WP_PLUGIN_DIR . '/beaver-builder-lite-version/fl-builder.php' ) || file_exists( WP_PLUGIN_DIR . '/bb-plugin/fl-builder.php' ) ) {
				$message = sprintf(
				/* translators: 1: Plugin name 2: Beaver */
					esc_html__( '"%1$s" requires "%2$s" to be activated. %3$s', 'xpro-bb-addons' ),
					'<strong>' . esc_html__( 'Xpro Addons For Beaver Builder', 'xpro-bb-addons' ) . '</strong>',
					'<strong>' . esc_html__( 'Beaver Builder', 'xpro-bb-addons' ) . '</strong>',
					'<p><a href="' . wp_nonce_url( 'plugins.php?action=activate&plugin=beaver-builder-lite-version/fl-builder.php', 'activate-plugin_beaver-builder-lite-version/fl-builder.php' ) . '" class="button-primary">' . esc_html__( 'Activate Beaver Builder', 'xpro-bb-addons' ) . '</a></p>'
				);
			} else {
				$message = sprintf(
				/* translators: 1: Plugin name 2: Beaver */
					esc_html__( '"%1$s" requires "%2$s" to be installed. %3$s', 'xpro-bb-addons' ),
					'<strong>' . esc_html__( 'Xpro Addons For Beaver Builder', 'xpro-bb-addons' ) . '</strong>',
					'<strong>' . esc_html__( 'Beaver', 'xpro-bb-addons' ) . '</strong>',
					'<p><a href="' . wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=beaver-builder-lite-version' ), 'install-plugin_beaver-builder-lite-version' ) . '" class="button-primary">' . esc_html__( 'Install Beaver Builder', 'xpro-bb-addons' ) . '</a></p>'
				);
			}

			printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required PHP version.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_minimum_php_version() {

		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
		/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'xpro-bb-addons' ),
			'<strong>' . esc_html__( 'Xpro Beaver Addons', 'xpro-bb-addons' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'xpro-bb-addons' ) . '</strong>',
			self::MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}

// Instantiate Xpro_Beaver_Addons.
new Xpro_Addons_for_BB();
