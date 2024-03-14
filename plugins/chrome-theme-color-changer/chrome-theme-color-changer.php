<?php
/**
 * Plugin Name: Chrome Theme Color Changer
 * Version: 1.1.2
 * Description: Address bar color changer for android chrome.
 * Author: Potato4d(Hanatani Takuma)
 * Author URI: http://potato4d.me/
 * Text Domain: chrome-theme-color-changer
 * Domain Path: /res/languages
 * @package Chrome-theme-color-changer
 */

class Chrome_Theme_Color_Changer{

	private $is_update  = false;
	private $is_success = false;

	public static function get_instance() {
		static $instance;

		if ( ! $instance instanceof Chrome_Theme_Color_Changer ) {
			$instance = new static;
		}
		return $instance;
	}

	public function __construct() {
		$this->add_actions();
		$this->add_filters();
		$this->load_textdomain();
	}

	private function add_actions() {
		add_action( 'admin_init'   , array( $this, 'admin_init' ) );
		add_action( 'wp_head'      , array( $this, 'echo_theme_color' ) );
		add_action( 'admin_notices', array( $this, 'set_notices' ) );
	}

	private function add_filters() {
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'add_action_links' ) );
		add_filter( 'admin_menu', array( $this, 'add_page' ) );
	}

	private function load_textdomain() {
		load_plugin_textdomain( 'chrome-theme-color-changer', null, basename( dirname( __FILE__ ) ) . '/res/languages/' );
	}

	public function admin_init() {
		$this->is_update = !!filter_input( INPUT_POST, 'update' );
		if ( ! filter_input( INPUT_POST, 'chrome-theme-color-changer' ) ) {
			return;
		}
		if ( check_admin_referer( 'chrome-theme-color-changer-key', 'chrome-theme-color-changer' ) ) {
			$this->is_success = $this->save_theme_color( filter_input( INPUT_POST, 'color' ) ?: '' );
		}
	}

	public function add_action_links( $links ) {
		$links[] = '<a href="' . menu_page_url( 'chrome-theme-color-changer', false ) . '">' . __( 'Settings', 'chrome-theme-color-changer' ) . '</a>';
		return $links;
	}

	public function add_page() {
		add_options_page(
			__( 'Theme color', 'chrome-theme-color-changer' ),
			__( 'Theme color', 'chrome-theme-color-changer' ),
			'administrator',
			'chrome-theme-color-changer',
			array( $this, 'chrome_theme_color_changer_menu' )
		);
	}

	public function set_notices() {
		if ( ! $this->is_update ) {
			return;
		}

		if( $this->is_success) {
			echo '<div class="notice notice-success is-dismissible"><p>' . __('Success updated.', 'chrome-theme-color-changer') . '</p></div>';
		} else {
			echo '<div class="error is-dismissible"><p>' . __('Invalid value.', 'chrome-theme-color-changer') . '</p></div>';
		}
	}

	public function chrome_theme_color_changer_menu() {
		require_once __DIR__ . '/res/templates/form.php';

		wp_register_script( 'chrome-theme-color-changer-lib-jscolor', plugins_url( 'res/lib/jscolor.min.js', __FILE__ ), array()          , false, true );
		wp_register_script( 'chrome-theme-color-changer-admin-js'   , plugins_url( 'res/js/main.js'    , __FILE__ ), array( 'jquery' ), false, true );

		wp_register_style( 'chrome-theme-color-changer-admin-css'   , plugins_url( 'res/css/style.css' , __FILE__ ) );

		wp_enqueue_script( 'chrome-theme-color-changer-lib-jscolor' );
		wp_enqueue_script( 'chrome-theme-color-changer-admin-js' );
		wp_enqueue_style( 'chrome-theme-color-changer-admin-css' );
	}

	private function save_theme_color( $color ) {
		if ( preg_match( "/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/", '#' . $color ) || $color === '' ) {
			update_option( 'chrome-theme-color-changer-color', $color );
			return true;
		}else{
			return false;
		}
	}

	public function echo_theme_color() {
		$color = get_option( 'chrome-theme-color-changer-color' );
		if ( in_array( $color, array( '', null ), true ) ) {
			return false;
		}
		echo '<meta name="theme-color" content="' . '#' . esc_html( $color ) . '">' . "\n";
		return true;
	}
}

add_action( 'plugins_loaded', array( 'Chrome_Theme_Color_Changer', 'get_instance' ) );
