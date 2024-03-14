<?php

namespace QuadLayers\QuadMenu;


if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

use QuadLayers\QuadMenu\Redux;

/**
 * Icons Class ex QuadMenu_Icons
 */
class Icons extends Redux {

	private static $instance;

	public $args     = array();
	public $sections = array();
	public $theme;
	public $ReduxFramework;

	public function __construct() {

		add_action( 'redux/options/' . QUADMENU_DB_OPTIONS . '/settings/change', array( $this, 'icons' ), 10, 2 );
	}

	static function icons( $options = false, $changed = false ) {

		// if settings change, take new values from redux update
		if ( empty( $changed['styles_icons'] ) ) {
			return;
		}

		Redux::do_reload( true );

		Redux::add_notification( 'blue', sprintf( esc_html__( 'Theme icons have been changed. Your options panel will be reloaded. %s.', 'quadmenu' ), esc_html__( 'Please wait', 'quadmenu' ) ) );
	}

	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
