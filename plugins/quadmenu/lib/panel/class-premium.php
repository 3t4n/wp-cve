<?php

namespace QuadLayers\QuadMenu\Panel;

use QuadLayers\QuadMenu\Panel;

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Premium Class ex QuadMenu_Premium
 */
class Premium extends Panel {

	private static $instance;

	static $status = array();

	function __construct() {
		add_action( 'admin_menu', array( $this, 'add_menu' ) );
	}

	function add_menu() {
		add_submenu_page( self::$panel_slug, esc_html__( 'Premium', 'quadmenu' ), sprintf( '%s <i class="dashicons dashicons-awards"></i>', esc_html__( 'Premium', 'quadmenu' ) ), 'edit_posts', self::$panel_slug . '_premium', array( $this, 'add_panel' ) );
	}

	function add_panel() {
		global $submenu;
		include QUADMENU_PLUGIN_DIR . '/lib/panel/pages/parts/header.php';
		include QUADMENU_PLUGIN_DIR . '/lib/panel/pages/premium.php';
	}

	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

}

