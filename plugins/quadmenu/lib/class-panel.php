<?php

namespace QuadLayers\QuadMenu;

if ( ! defined( 'ABSPATH' ) ) {
  die( '-1' );
}
/**
 * Panel class ex QuadMenu_Panel
 */
class Panel {

	private static $instance;

	static $panel_slug = 'quadmenu_welcome';

	function __construct() {

		add_filter( 'quadmenu_global_js_data', array( $this, 'js_data' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ), 5 );

		add_filter( 'admin_body_class', array( $this, 'body' ), 99 );

		add_action( 'admin_menu', array( $this, 'menus' ), 9 );
	}

	function enqueue() {

		$screen = get_current_screen();

		if ( strpos( $screen->base, sanitize_title( QUADMENU_PLUGIN_NAME ) ) === false && $screen->base != 'toplevel_page_quadmenu_welcome' ) {
			return;
		}

		wp_enqueue_style( 'quadmenu-admin' );

		wp_enqueue_script( 'quadmenu-admin' );

		wp_localize_script( 'quadmenu-admin', 'quadmenu', apply_filters( 'quadmenu_global_js_data', array() ) );
	}

	function js_data( $data ) {

		$data['nonce'] = wp_create_nonce( 'quadmenu' );

		return $data;
	}

	// function pro() {
	// add_submenu_page(self::$panel_slug, __('Premium', 'quadmenu'), sprintf('<i class="dashicons dashicons-awards"></i> %s', __('Premium', 'quadmenu')), 'edit_posts', 'quadmenu_pro', array($this, 'purchase'));
	// }

	function menus() {
		add_submenu_page( self::$panel_slug, esc_html__( 'Menus', 'quadmenu' ), esc_html__( 'Menus', 'quadmenu' ), 'manage_options', 'nav-menus.php' );
	}

	static function body( $classes ) {

		$screen = get_current_screen();

		// if (strpos($screen->base, sanitize_title(QUADMENU_PLUGIN_NAME)) === false && $screen->base != 'toplevel_page_quadmenu_welcome')
		// return $classes;

		$classes .= ' admin-color-quadmenu';

		return $classes;
	}

	function header() {

		global $submenu;

		require_once QUADMENU_PLUGIN_DIR . 'lib/panel/header.php';
	}

	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

}

