<?php
/**
 * The Admin class handle administrative informations about the plugin.
 *
 * @package    Rock_Convert\Inc\Admin
 * @link       https://rockcontent.com
 * @since      1.0.0
 *
 * @author     Rock Content
 */

namespace Rock_Convert\Inc\Admin;

use Rock_Convert\Inc\Admin\CTA\Custom_Post_Type as Custom_Post_Type;
use Rock_Convert\Inc\Admin\Page_Settings as Page_Settings;

/**
 * Admin Class.
 *
 * @link       https://rockcontent.com
 * @since      1.0.0
 *
 * @author     Rock Content
 */
class Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * The text domain of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_text_domain The text domain of this plugin.
	 */
	private $plugin_text_domain;

	/**
	 * The admin css from assets
	 *
	 * @since 2.11.0
	 * @access protected
	 * @var string $plugin_admin_css_bundle_url The string used to enqueue admin css.
	 */
	private $plugin_admin_css_bundle_url;

	/**
	 * The admin js from assets
	 *
	 * @since 2.11.0
	 * @access protected
	 * @var string $plugin_admin_js_bundle_url The string used to enqueue admin js.
	 */
	private $plugin_admin_js_bundle_url;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param    string $plugin_name The name of this plugin.
	 * @param    string $version The version of this plugin.
	 * @param    string $plugin_text_domain The text domain of this plugin.
	 * @param    string $plugin_admin_css_bundle_url The url from css bundle of this plugin.
	 * @param    string $plugin_admin_js_bundle_url The url from js bundle of this plugin.
	 */
	public function __construct( $plugin_name, $version, $plugin_text_domain,
								$plugin_admin_css_bundle_url, $plugin_admin_js_bundle_url ) {
		$this->plugin_name                 = $plugin_name;
		$this->version                     = $version;
		$this->plugin_text_domain          = $plugin_text_domain;
		$this->plugin_admin_css_bundle_url = $plugin_admin_css_bundle_url;
		$this->plugin_admin_js_bundle_url  = $plugin_admin_js_bundle_url;
	}

	/**
	 * Check if analytics data exists.
	 *
	 * @return bool
	 */
	public static function analytics_enabled() {
		return intval( get_option( '_rock_convert_enable_analytics' ) ) === 1;
	}

	/**
	 * Check if custom field is enabled.
	 *
	 * @return bool
	 */
	public static function name_field_is_enabled() {
		return intval( get_option( '_rock_convert_name_field' ) ) === 1;
	}

	/**
	 * Check if custom field is enabled.
	 *
	 * @return bool
	 */
	public static function custom_field_is_enabled() {
		return intval( get_option( '_rock_convert_custom_field' ) ) === 1;
	}

	/**
	 * Get value to custom label.
	 *
	 * @return false|mixed|void
	 */
	public static function custom_field_label_value() {
		return get_option( '_rock_convert_custom_field_label' );
	}

	/**
	 * Check if title powered by is enabled
	 *
	 * @return bool
	 */
	public static function hide_referral() {
		return intval( get_option( '_rock_convert_powered_by_hidden' ) ) === 1;
	}

	/**
	 * Shows "analytics not connected" flash message
	 *
	 * @since 2.1.1
	 */
	public function analytics_activation_notice() {
		$class = 'notice notice-error';
		$url   = admin_url( 'edit.php?post_type=cta&page=rock-convert-settings' );
		$link  = "<a href='$url' style='font-weight: bold;'>" . __(
			'Clique aqui',
			'rock-convert'
		) . '</a> para acessar a página de configurações e <strong>ativar</strong>.';
		wp_kses(
		printf(
			'<div class="%1$s"><p>%2$s %3$s</p></div>',
			$class,
			__( 'Ops! Parece que você ainda não ativou a funcionalidade de analytics do Rock Convert!', 'rock-convert' ),
			$link
		),
		array('div' => array('p' => array()), 'strong' => array(), 'a' => array('href' => array(), 'style' => array()))
	);

	}


	/**
	 * Returns the version from Bennington Theme
	 *
	 * @since 2.6.0
	 */
	public static function rc_template4_version() {
		$rc_template4 = wp_get_theme( 'bennington' );
		if ( ! $rc_template4->exists() ) {
			$rc_template4 = wp_get_theme( 'rc-template4' );
		}
		return $rc_template4->get( 'Version' );

	}

	/**
	 * Returns the current active theme
	 *
	 * @since 2.6.0
	 */
	public static function rc_active_theme() {
		$get_theme = wp_get_theme();
		return $get_theme->get( 'Name' );
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since 1.0.0
	 * @since 2.2.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, $this->plugin_admin_css_bundle_url, array(), $this->version, 'all' );
		wp_enqueue_style( 'wp-color-picker' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since 1.0.0
	 * @since 2.2.0
	 */
	public function enqueue_scripts() {
		$params = array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) );
		wp_enqueue_script(
			'rock_convert_ajax_handle',
			$this->plugin_admin_js_bundle_url,
			array( 'jquery', 'wp-color-picker' ),
			$this->version,
			false
		);
		wp_localize_script( 'rock_convert_ajax_handle', 'params', $params );
		wp_enqueue_media();
	}

	/**
	 * Register CTA custom post type.
	 *
	 * @return void
	 */
	public function register_cta_post_type() {
		new Custom_Post_Type();
	}

	/**
	 * Register page settings.
	 *
	 * @return void
	 */
	public function register_settings_page() {
		$settings = new Page_Settings();
		$settings->register();
	}

	/**
	 * Delete rock_convert_getting_started data if false.
	 *
	 * @return void
	 */
	public function getting_started_page() {
		if ( get_option( 'rock_convert_getting_started', false ) ) {
			delete_option( 'rock_convert_getting_started' );
			wp_safe_redirect( 'edit.php?post_type=cta&page=rock-convert-settings&tab=general' );
			exit;
		}
	}

	/**
	 * Add support to submenu link
	 *
	 * @var array $submenu Submenu value.
	 * @return void
	 */
	public function add_support_submenu_link() {
		global $submenu;
		$submenu['edit.php?post_type=cta'][] = array(
			 'Ajuda', 'manage_options', ROCK_CONVERT_HELP_CENTER_URL ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride
	}
}
