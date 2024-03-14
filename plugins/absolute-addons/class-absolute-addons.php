<?php
/**
 * Main Class.
 *
 * @package AbsoluteAddons
 * @since 1.0.0
 * @version 1.0.1
 */

namespace AbsoluteAddons;

use AbsoluteAddons\Settings\Dashboard;
use Elementor\Elements_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/** @define "ABSOLUTE_ADDONS_PATH" "./" */

/**
 * Main Absolute_Addons Addons Class
 *
 * The init class that runs the Hello World plugin.
 * Intended To make sure that the plugin's minimum requirements are met.
 *
 * You should only modify the constants to match your plugin's needs.
 *
 * Any custom code should go inside Plugin Class in the plugin.php file.
 */
final class Absolute_Addons {

	/**
	 * Plugin Version
	 *
	 * @since 1.0.0
	 * @var string The plugin version.
	 */
	const VERSION = ABSOLUTE_ADDONS_VERSION;

	/**
	 * Minimum Elementor Version
	 *
	 * @since 1.0.0
	 * @var string Minimum Elementor version required to run the plugin.
	 */
	const MINIMUM_ELEMENTOR_VERSION = '3.2.5';

	/**
	 * Minimum PHP Version
	 *
	 * @since 1.0.0
	 * @var string Minimum PHP version required to run the plugin.
	 */
	const MINIMUM_PHP_VERSION = '5.6';

	/**
	 * Instance
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 * @static
	 *
	 * @var Absolute_Addons The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @return Absolute_Addons An instance of the class.
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 */
	public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;

	}

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {

		$this->include_files();

		// Installation.
		register_activation_hook( ABSOLUTE_ADDONS_FILE, [ __CLASS__, 'install' ] );

		// Check if version updates and handle post update.
		add_action( 'init', [ __CLASS__, 'update_version' ] );

		// Load translation.
		add_action( 'init', [ $this, 'i18n' ] );

		// Init Plugin.
		add_action( 'plugins_loaded', [ $this, 'init' ], ABSOLUTE_ADDONS_INT_MIN );

		add_action( 'elementor/elements/categories_registered', [ $this, 'add_widget_categories' ], ABSOLUTE_ADDONS_INT_MIN );
	}

	public static function install() {
		// @XXX Installation & update should have it's own class.

		Absp_Post_types::register_post_types();
		Absp_Post_types::register_taxonomies();

		self::fix_old_upcoming_off_widgets();

		update_option( 'absolute_addons_version', ABSOLUTE_ADDONS_VERSION, false );

		do_action( 'absp/flush_rewrite_rules' );

		do_action( 'absolute_addons_installed' );
	}

	public static function update_version() {

		if ( ! defined( 'IFRAME_REQUEST' ) && self::maybe_update_version() ) {
			self::install();
			do_action( 'absolute_addons_updated' );
		}
	}

	private static function maybe_update_version() {
		return version_compare( get_option( 'absolute_addons_version' ), ABSOLUTE_ADDONS_VERSION, '<' );
	}

	private static function fix_old_upcoming_off_widgets() {

		/**
		 * Before 1.0.3 upcoming widgets are saved as off.
		 * And, we don't have any version info saved separately in db at that point.
		 * Remove those widgets from the saved settings list.
		 * So, it can follow the “is_active” setting while registering widget.
		 */

		if ( ! get_option( 'absolute_addons_version' ) ) {
			$options = get_option( 'absolute_addons_settings', [] );
			$widgets = Plugin::get_widgets();
			$settings = isset( $options['widgets'] ) && is_array( $options['widgets'] ) ? $options['widgets'] : [];
			foreach ( $widgets as $slug => $data ) {
				if ( $data['is_upcoming'] && isset( $settings[ $slug ] ) ) {
					unset( $settings[ $slug ] );
				}
			}

			$options['widgets'] = $settings;
			update_option( 'absolute_addons_settings', $options, false );
		}
	}

	private function include_files() {

		require_once ABSOLUTE_ADDONS_PATH . 'includes/class-absolute-addons-services.php';
		require_once ABSOLUTE_ADDONS_PATH . 'includes/helper.php';
		require_once ABSOLUTE_ADDONS_PATH . 'includes/default-filters.php';
		require_once ABSOLUTE_ADDONS_PATH . 'includes/class-absp-post-types.php';
		require_once ABSOLUTE_ADDONS_PATH . 'class-plugin.php';
		require_once ABSOLUTE_ADDONS_PATH . 'includes/ajax-handler.php';
		require_once ABSOLUTE_ADDONS_PATH . 'includes/icons-manager.php';
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
		load_plugin_textdomain( 'absolute-addons', false, basename( dirname( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Initialize the plugin
	 *
	 * Validates that Elementor is already loaded.
	 * Checks for basic plugin requirements, if one check fail don't continue,
	 * if all check have passed include the plugin class.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function init() {

		// Check if Elementor installed and activated.
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_missing_main_plugin' ) );

			return;
		}

		// Check for required Elementor version.
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_elementor_version' ) );

			return;
		}

		// Check for required PHP version.
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_php_version' ) );

			return;
		}

		// Once we get here, We have passed all validation checks, so we can safely initialize things.

		if ( is_admin() ) {
			Dashboard::get_instance();
			Dashboard::render_notices();
		}

		Absp_Library::init();
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have Elementor installed or activated.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_missing_main_plugin() {
		if ( isset( $_GET['activate'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			unset( $_GET['activate'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		}

		$message = sprintf(
		/* translators: 1: Plugin name 2: Elementor 3. Anchor Tag Opening 4. Link Label (Anchor text) 5. Anchor Tag Closing */
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated. You can install and activate %2$s from %3$s%4$s%5$s.', 'absolute-addons' ),
			'<strong>' . esc_html__( 'Absolute Addons', 'absolute-addons' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'absolute-addons' ) . '</strong>',
			'<a href="' . esc_url( absp_plugin_install_url( 'elementor' ) ) . '">',
			esc_html__( 'here', 'absolute-addons' ),
			'</a>'
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', wp_kses_post( $message ) );
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required Elementor version.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_minimum_elementor_version() {
		if ( isset( $_GET['activate'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			unset( $_GET['activate'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		}

		$message = sprintf(
		/* translators: 1: Plugin name 2: Dependency Name 3: Required Dependency version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'absolute-addons' ),
			'<strong>' . esc_html__( 'Absolute Addons', 'absolute-addons' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'absolute-addons' ) . '</strong>',
			self::MINIMUM_ELEMENTOR_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', wp_kses_post( $message ) );
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
		if ( isset( $_GET['activate'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			unset( $_GET['activate'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		}

		$message = sprintf(
		/* translators: 1: Plugin name 2: Dependency Name 3: Required Dependency version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'absolute-addons' ),
			'<strong>' . esc_html__( 'Absolute Addons', 'absolute-addons' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'absolute-addons' ) . '</strong>',
			self::MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', wp_kses_post( $message ) );
	}

	/**
	 * Add Custom Elementor Categories
	 *
	 * @param Elements_Manager $elements_manager Category Names Array.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function add_widget_categories( $elements_manager ) {

		$elements_manager->add_category(
			'absp-widgets',
			[
				'title' => esc_html__( 'Absolute Addons', 'absolute-addons' ),
				'icon'  => 'fa fa-plug',
			]
		);
		if ( absp_has_pro() ) {
			$elements_manager->add_category(
				'absp-widgets-pro',
				[
					'title' => esc_html__( 'Absolute Addons Pro', 'absolute-addons' ),
					'icon'  => 'fa fa-plug',
				]
			);
		}
	}
}

// End of file class-absolute-addons.php
