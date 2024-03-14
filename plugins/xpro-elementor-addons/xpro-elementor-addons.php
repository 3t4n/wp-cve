<?php
/**
 * Plugin Name: Xpro Elementor Addons
 * Description: A complete Elementor Addons Pack to enhance your web designing experience. Create amazing websites with 50+ FREE Widgets, Extensions & more.
 * Plugin URI:  https://elementor.wpxpro.com/
 * Version:     1.4.2
 * Author:      Xpro
 * Author URI:  https://www.wpxpro.com/
 * Developer:   Xpro Team
 * Text Domain: xpro-elementor-addons
 * Elementor tested up to: 3.18.3
 */

defined( 'ABSPATH' ) || die();

define( 'XPRO_ELEMENTOR_ADDONS_VERSION', '1.4.2' );
define( 'XPRO_ELEMENTOR_ADDONS__FILE__', __FILE__ );
define( 'XPRO_ELEMENTOR_ADDONS_BASE', plugin_basename( __FILE__ ) );
define( 'XPRO_ELEMENTOR_ADDONS_DIR_PATH', plugin_dir_path( XPRO_ELEMENTOR_ADDONS__FILE__ ) );
define( 'XPRO_ELEMENTOR_ADDONS_DIR_URL', plugin_dir_url( XPRO_ELEMENTOR_ADDONS__FILE__ ) );
define( 'XPRO_ELEMENTOR_ADDONS_ASSETS', trailingslashit( XPRO_ELEMENTOR_ADDONS_DIR_URL . 'assets' ) );
define( 'XPRO_ELEMENTOR_ADDONS_WIDGET', trailingslashit( XPRO_ELEMENTOR_ADDONS_DIR_PATH . 'widgets' ) );

$upload_dir                         = wp_upload_dir();
$xpro_elementor_addons_temp         = $upload_dir['basedir'] . '/xpro-elementor-addons-temp/';
$xpro_elementor_addons_temp_zip     = $upload_dir['basedir'] . '/xpro-elementor-addons-temp-zip/';
$xpro_elementor_addons_temp_uploads = $xpro_elementor_addons_temp . '/uploads/';

define( 'XPRO_ELEMENTOR_ADDONS_TEMP', $xpro_elementor_addons_temp );
define( 'XPRO_ELEMENTOR_ADDONS_TEMP_ZIP', $xpro_elementor_addons_temp_zip );
define( 'XPRO_ELEMENTOR_ADDONS_TEMP_UPLOADS', $xpro_elementor_addons_temp_uploads );

/**
 * Main Xpro Elementor Addons Class
 *
 * The init class that runs the Xpro Elementor Addons plugin.
 * Intended To make sure that the plugin's minimum requirements are met.
 *
 * You should only modify the constants to match your plugin's needs.
 *
 * Any custom code should go inside Plugin Class in the plugin.php file.
 * @tags
 */

final class Xpro_Elementor_Addons {


	/**
	 * Plugin Slug
	 *
	 * @var string The plugin slug.
	 */
	const PAGE_SLUG = 'xpro-elementor-addons';

	/**
	 * Plugin Licence Slug
	 *
	 * @var string The plugin license slug.
	 */
	const LICENSE_PAGE_SLUG = 'xpro-elementor-addons-license';

	/**
	 * Plugin Version
	 *
	 * @since 1.0.0
	 * @var string The plugin version.
	 */
	const VERSION = '1.4.2';

	/**
	 * Minimum Elementor Version
	 *
	 * @since 1.0.0
	 * @var string Minimum Elementor version required to run the plugin.
	 */
	const MINIMUM_ELEMENTOR_VERSION = '3.0.0';

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

		//Fires when Xpro Elementor Addons was fully loaded
		do_action( 'xpro_elementor_addons_loaded' );

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
		load_plugin_textdomain(
			'xpro-elementor-addons',
			false,
			dirname( plugin_basename( XPRO_ELEMENTOR_ADDONS__FILE__ ) ) . '/language/'
		);
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

		// Check if Elementor installed and activated
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_missing_main_plugin' ) );
		}

		// Check for required Elementor version
		if ( did_action( 'elementor/loaded' ) && ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_elementor_version' ) );
			return;
		}

		// Check for required PHP version
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_php_version' ) );
			return;
		}

		//Render Theme Builder Notice
		if ( is_admin() && ! did_action( 'xpro_theme_builder_loaded' ) && ! file_exists( WP_PLUGIN_DIR . '/xpro-theme-builder/xpro-theme-builder.php' ) && ! get_option( 'xpro_theme_builder_dismiss_notice' ) ) {
			add_action( 'admin_notices', array( $this, 'latest_feature_notices' ) );
			add_action( 'admin_head', array( $this, 'notices_enqueue_scripts' ) );
			add_action( 'wp_ajax_xpro_theme_builder_dismiss_notice', array( $this, 'xpro_theme_builder_dismiss_notice' ) );
		}

		// Once we get here, We have passed all validation checks so we can safely include our plugin
		require_once plugin_dir_path( __FILE__ ) . 'plugin.php';
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

		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$screen = get_current_screen();

		if ( 'plugins' === $screen->base ) {
			if ( file_exists( WP_PLUGIN_DIR . '/elementor/elementor.php' ) ) {
				$message = sprintf(
				/* translators: 1: Plugin name 2: Elementor */
					esc_html__( '"%1$s" requires "%2$s" to be activated. %3$s', 'xpro-elementor-addons' ),
					'<strong>' . esc_html__( 'Xpro Elementor Addons', 'xpro-elementor-addons' ) . '</strong>',
					'<strong>' . esc_html__( 'Elementor', 'xpro-elementor-addons' ) . '</strong>',
					'<p><a href="' . wp_nonce_url( 'plugins.php?action=activate&plugin=elementor/elementor.php', 'activate-plugin_elementor/elementor.php' ) . '" class="button-primary">' . esc_html__( 'Activate Elementor', 'xpro-elementor-addons' ) . '</a></p>'
				);
			} else {
				$message = sprintf(
				/* translators: 1: Plugin name 2: Elementor */
					esc_html__( '"%1$s" requires "%2$s" to be installed. %3$s', 'xpro-elementor-addons' ),
					'<strong>' . esc_html__( 'Xpro Elementor Addons', 'xpro-elementor-addons' ) . '</strong>',
					'<strong>' . esc_html__( 'Elementor', 'xpro-elementor-addons' ) . '</strong>',
					'<p><a href="' . wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' ) . '" class="button-primary">' . esc_html__( 'Install Elementor', 'xpro-elementor-addons' ) . '</a></p>'
				);
			}

			printf( '<div class="notice notice-warning"><p>%1$s</p></div>', $message ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
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

		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
		/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'xpro-elementor-addons' ),
			'<strong>' . esc_html__( 'Xpro Elementor Addons', 'xpro-elementor-addons' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'xpro-elementor-addons' ) . '</strong>',
			self::MINIMUM_ELEMENTOR_VERSION
		);

		printf( '<div class="notice notice-warning"><p>%1$s</p></div>', $message ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
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
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'xpro-elementor-addons' ),
			'<strong>' . esc_html__( 'Xpro Elementor Addons', 'xpro-elementor-addons' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'xpro-elementor-addons' ) . '</strong>',
			self::MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice notice-warning"><p>%1$s</p></div>', $message ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}


	/**
	 * Theme Builder notices
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function latest_feature_notices() {
		$screen = get_current_screen();
		if ( 'plugins' === $screen->base ) { ?>
			<div class="notice xpro-theme-builder-notice is-dismissible xpro-features-wrapper">
				<div class="xpro-features-inner">
					<div class="xpro-features-col-left">
						<h3 class="xpro-features-heading"><?php echo esc_html__( 'Discover Our FREE Theme Builder', 'xpro-elementor-addons' ); ?></h3>
						<p class="xpro-features-desc"><?php echo esc_html__( 'Creating amazing layouts for post types, blog archives, WooCommerce products & categories, search pages, 404 pages in minutes by using our free theme builder for elementor.', 'xpro-elementor-addons' ); ?></p>
						<ul class="xpro-features-list">
							<li><?php echo esc_html__( 'Header', 'xpro-elementor-addons' ); ?></li>
							<li><?php echo esc_html__( 'Footer', 'xpro-elementor-addons' ); ?></li>
							<li><?php echo esc_html__( 'Singular', 'xpro-elementor-addons' ); ?></li>
							<li><?php echo esc_html__( 'Archives', 'xpro-elementor-addons' ); ?></li>
							<li><?php echo esc_html__( 'Search', 'xpro-elementor-addons' ); ?></li>
							<li><?php echo esc_html__( 'Many More...', 'xpro-elementor-addons' ); ?></li>
						</ul>
						<div class="xpro-features-action">
							<a href="<?php echo esc_attr( wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=xpro-theme-builder' ), 'install-plugin_xpro-theme-builder' ) ); ?>" class="xpro-feature-install-button"><?php echo esc_html__( 'Install Now', 'xpro-elementor-addons' ); ?></a>
							<a href="https://elementor.wpxpro.com/theme-builder/" target="_blank" class="xpro-feature-learn-more-button">Learn More</a>
						</div>
					</div>
					<div class="xpro-features-col-right">
						<img src="<?php echo esc_url( XPRO_ELEMENTOR_ADDONS_ASSETS . 'admin/images/theme-builder.png' ); ?>" alt="theme-builder"/>
					</div>
				</div>
			</div>
			<?php
		}
	}

	public static function notices_enqueue_scripts() {
		wp_enqueue_style( 'xpro-admin-notices', XPRO_ELEMENTOR_ADDONS_ASSETS . 'admin/css/admin-notices.css', '', XPRO_ELEMENTOR_ADDONS_VERSION );
		wp_enqueue_script( 'xpro-admin-notices', XPRO_ELEMENTOR_ADDONS_ASSETS . 'admin/js/admin-notices.js', array( 'jquery' ), XPRO_ELEMENTOR_ADDONS_VERSION, true );
	}

	public function xpro_theme_builder_dismiss_notice() {
		add_option( 'xpro_theme_builder_dismiss_notice', true );
	}

}

// Instantiate Xpro_Elementor_Addons.
new Xpro_Elementor_Addons();
