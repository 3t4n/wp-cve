<?php
/**
 * Admin Menu
 *
 * @package    admin-menu
 * @author     miniOrange <info@miniorange.com>
 * @license    MIT/Expat
 * @link       https://miniorange.com
 */

/**
 * Add required files.
 */
require 'partials' . DIRECTORY_SEPARATOR . 'class-mo-oauth-client-admin-menu.php';

/**
 * [Description Handle admin menu]
 */
class MO_OAuth_Client_Admin {

	/**
	 * Name of the plugin installed.
	 *
	 * @var plugin_name name of the plugin.
	 */
	private $plugin_name;
	/**
	 * Version of the plugin installed
	 *
	 * @var version version of the plugin installed.
	 */
	private $version;

	/**
	 * Initilaize plugin name and version for the class object
	 *
	 * @param mixed $plugin_name name of the plugin installed.
	 * @param mixed $version plugin version.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		add_filter( 'plugin_action_links_' . MO_OAUTH_PLUGIN_BASENAME, array( $this, 'add_action_links' ) );
	}

	// Function to add the Premium settings in Plugin's section.

	/**
	 * Handle URL actions.
	 *
	 * @param mixed $actions handle actions.
	 * @return [array]
	 */
	public function add_action_links( $actions ) {

		$url            = esc_url(
			add_query_arg(
				'page',
				'mo_oauth_settings',
				get_admin_url() . 'admin.php'
			)
		);
		$url           .= '&tab=config';
		$url2           = $url . '&tab=licensing';
		$settings_link  = "<a href='$url'>Configure</a>";
		$settings_link2 = "<a href='$url2'>Premium Plans</a>";
		array_push( $actions, $settings_link2 );
		array_push( $actions, $settings_link );
		return array_reverse( $actions );
	}

	/**
	 * Register the stylesheets for the admin area.
	 */
	public function enqueue_styles() {
		if ( isset( $_REQUEST['tab'] ) && 'licensing' === $_REQUEST['tab'] ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Ignoring nonce recommendation because we are fetching data from URL directly and not form submission.
			wp_enqueue_style( 'mo_oauth_bootstrap_css', plugins_url( 'css/bootstrap/bootstrap.min.css', __FILE__ ), array(), '5.1.3' );
			wp_enqueue_style( 'mo_oauth_license_page_style', plugins_url( 'css/mo-oauth-licensing.min.css', __FILE__ ), array(), MO_OAUTH_CSS_JS_VERSION );
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 */
	public function enqueue_scripts() {
		if ( isset( $_REQUEST['tab'] ) && 'licensing' === $_REQUEST['tab'] ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Ignoring nonce recommendation because we are fetching data from URL directly and not form submission.
			wp_enqueue_script( 'mo_oauth_modernizr_script', plugins_url( 'js/modernizr.min.js', __FILE__ ), array(), '3.6.0', false );
			wp_enqueue_script( 'mo_oauth_popover_script', plugins_url( 'js/bootstrap/popper.min.js', __FILE__ ), array(), '2.0.1', false );
			wp_enqueue_script( 'mo_oauth_bootstrap_script', plugins_url( 'js/bootstrap/bootstrap.min.js', __FILE__ ), array(), '5.1.3', false );
		}
	}

	/**
	 * Add Plugin menu in WordPress nav bar.
	 */
	public function admin_menu() {
		$slug = 'mo_oauth_settings';
		add_menu_page(
			'MO OAuth Settings  ' . esc_html__( 'Configure OAuth', 'mo_oauth_settings' ),
			MO_OAUTH_ADMIN_MENU,
			'administrator',
			$slug,
			array( $this, 'menu_options' ),
			plugin_dir_url( __FILE__ ) . 'images/miniorange.png'
		);
		add_submenu_page(
			$slug,
			MO_OAUTH_ADMIN_MENU,
			'Plugin Configuration',
			'administrator',
			'mo_oauth_settings'
		);
		add_submenu_page(
			$slug,
			'Licencnce',
			'<div style="color:orange;display: flex;align-items: center;gap: 5px"><img src="' . esc_url( dirname( plugin_dir_url( __FILE__ ) ) ) . '/admin/images/prem.png" alt="miniOrange Premium Plans Logo" style="height:16px;width:16px"> ' . __( 'Premium Plans', 'miniorange-login-with-eve-online-google-facebook' ) . '</div>',
			'administrator',
			'?page=mo_oauth_settings&tab=licensing'
		);
		add_submenu_page(
			'mo_oauth_settings',
			'Trials',
			'<div style="color:#fff;display: flex;font-size: 13px;font-weight:500"> ' . __( 'Free Trial', 'miniorange-login-with-eve-online-google-facebook' ) . '</div>',
			'administrator',
			'?page=mo_oauth_settings&tab=requestfordemo'
		);
		add_submenu_page(
			$slug,
			'Add-ons',
			'Add-ons',
			'administrator',
			'?page=mo_oauth_settings&tab=addons'
		);
	}

	/**
	 * Set host name and display the main plugin page.
	 */
	public function menu_options() {
		global $wpdb;
		update_option( 'host_name', 'https://login.xecurify.com' );
		mooauth_client_main_menu();
	}
}
