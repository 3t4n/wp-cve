<?php
/**
 * Contains code for the settings page class.
 *
 * @package     Boxtal\BoxtalConnectWoocommerce\Settings
 */

namespace Boxtal\BoxtalConnectWoocommerce\Settings;

use Boxtal\BoxtalConnectWoocommerce\Notice\Notice_Controller;
use Boxtal\BoxtalConnectWoocommerce\Util\Misc_Util;
use Boxtal\BoxtalConnectWoocommerce\Util\Shipping_Method_Util;
use Boxtal\BoxtalConnectWoocommerce\Util\Configuration_Util;
use Boxtal\BoxtalConnectWoocommerce\Branding;

/**
 * Settings page class.
 *
 * Manages settings for the Boxtal Connect plugin.
 */
class Page {

	/**
	 * Plugin url.
	 *
	 * @var string
	 */
	private $plugin_url;

	/**
	 * Plugin version.
	 *
	 * @var string
	 */
	private $plugin_version;

	/**
	 * Construct function.
	 *
	 * @param array $plugin plugin array.
	 * @void
	 */
	public function __construct( $plugin ) {
		$this->plugin_url     = $plugin['url'];
		$this->plugin_version = $plugin['version'];
	}

	/**
	 * Run class.
	 *
	 * @void
	 */
	public function run() {
		add_action( 'admin_menu', array( $this, 'add_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'settings_page_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'settings_page_styles' ) );
	}

	/**
	 * Enqueue settings page scripts
	 *
	 * @void
	 */
	public function settings_page_scripts() {
		global $plugin_page;
		if ( Branding::$branding . '-connect-settings' === $plugin_page ) {
			wp_enqueue_script( Branding::$branding_short . '_tail_select', $this->plugin_url . 'Boxtal/BoxtalConnectWoocommerce/assets/js/tail.select-full.min.js', array(), $this->plugin_version, false );
			wp_enqueue_script( Branding::$branding_short . '_settings_page', $this->plugin_url . 'Boxtal/BoxtalConnectWoocommerce/assets/js/settings-page.min.js', array( Branding::$branding_short . '_tail_select' ), $this->plugin_version, false );
			wp_add_inline_script( Branding::$branding_short . '_settings_page', 'var bwData = bwData ? bwData : {}', 'before' );
			wp_add_inline_script( Branding::$branding_short . '_settings_page', 'bwData.' . Branding::$branding_short . ' = bwData.' . Branding::$branding_short . ' ? bwData.' . Branding::$branding_short . ' : {}', 'before' );
			wp_add_inline_script( Branding::$branding_short . '_settings_page', 'bwData.' . Branding::$branding_short . '.locale = "' . substr( get_locale(), 0, 2 ) . '"', 'before' );
			wp_add_inline_script( Branding::$branding_short . '_settings_page', 'bwData.' . Branding::$branding_short . '.ajaxurl = "' . admin_url( 'admin-ajax.php' ) . '"', 'before' );
		}
	}

	/**
	 * Enqueue settings page styles
	 *
	 * @void
	 */
	public function settings_page_styles() {
		global $plugin_page;
		if ( Branding::$branding . '-connect-settings' === $plugin_page ) {
			wp_enqueue_style( Branding::$branding_short . '_tail_select', $this->plugin_url . 'Boxtal/BoxtalConnectWoocommerce/assets/css/tail.select-bootstrap3.css', array(), $this->plugin_version );
			wp_enqueue_style( Branding::$branding_short . '_parcel_point', $this->plugin_url . 'Boxtal/BoxtalConnectWoocommerce/assets/css/settings.css', array(), $this->plugin_version );
		}
	}

	/**
	 * Add settings page.
	 *
	 * @void
	 */
	public function add_menu() {
		/* translators: 1) company name */
		add_submenu_page( 'woocommerce', sprintf( __( '%s Connect', 'boxtal-connect' ), Branding::$company_name ), sprintf( __( '%s Connect', 'boxtal-connect' ), Branding::$company_name ), 'manage_woocommerce', Branding::$branding . '-connect-settings', array( $this, 'render_page' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
	}

	/**
	 * Register settings.
	 *
	 * @void
	 */
	public function register_settings() {
		register_setting(
			Branding::$branding . '-connect-settings-group',
			strtoupper( Branding::$branding_short ) . '_ORDER_SHIPPED',
			array(
				'type'              => 'string',
				'description'       => __( 'Order shipped ', 'boxtal-connect' ),
				'default'           => null,
				'sanitize_callback' => array( $this, 'sanitize_status' ),
			)
		);
		register_setting(
			Branding::$branding . '-connect-settings-group',
			strtoupper( Branding::$branding_short ) . '_ORDER_DELIVERED',
			array(
				'type'              => 'string',
				'description'       => __( 'Order delivered ', 'boxtal-connect' ),
				'default'           => null,
				'sanitize_callback' => array( $this, 'sanitize_status' ),
			)
		);
	}

	/**
	 * Render settings page.
	 *
	 * @void
	 */
	public function render_page() {
		$order_statuses  = wc_get_order_statuses();
		$help_center_url = Configuration_Util::get_help_center_link();
		$tuto_url        = Configuration_Util::get_help_center_link();
		include_once dirname( __DIR__ ) . '/assets/views/html-settings-page.php';
	}

	/**
	 * Sanitize status option.
	 *
	 * @param string $input status value.
	 *
	 * @return string
	 */
	public function sanitize_status( $input ) {
		return 'none' === $input ? null : $input;
	}
}
