<?php

namespace QuadLayers\QuadMenu\Panel;

use QuadLayers\QuadMenu\Panel;

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class Options extends Panel {

	private static $instance;

	function __construct() {
		add_action( 'redux/' . QUADMENU_DB_OPTIONS . '/panel/before', array( $this, 'header' ) );
		add_filter( 'quadmenu_redux_args', array( $this, 'args' ), -10 );
	}

	function args( $args ) {

		$args['menu_type']      = 'submenu';
		$args['page_parent']    = self::$panel_slug;
		$args['page_slug']      = QUADMENU_PANEL;
		$args['allow_sub_menu'] = true;
		$args['page']           = $args['display_name'] = $args['menu_title'] = esc_html__( 'Options', 'quadmenu' );

		if ( ! is_admin() ) {
			$args['page'] = $args['display_name'] = $args['menu_title'] = QUADMENU_PLUGIN_NAME;
		}

		return $args;
	}

	function header() {

		global $submenu;

		require_once QUADMENU_PLUGIN_DIR . 'lib/panel/pages/parts/header.php';
	}

	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

}

