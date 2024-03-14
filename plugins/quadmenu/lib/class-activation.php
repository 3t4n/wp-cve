<?php

namespace QuadLayers\QuadMenu;

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Activation Class ex QuadMenu_Activation
 */
class Activation {

	protected static $instance;

	function __construct() {

		add_action( 'admin_init', array( __CLASS__, 'redirect' ) );

		add_action( 'after_switch_theme', array( __CLASS__, 'do_compiler' ) );

		add_action( 'after_switch_theme', array( __CLASS__, 'do_redirect' ) );

		add_action( 'quadmenu_activation', array( __CLASS__, 'do_compiler' ) );

		add_action( 'quadmenu_activation', array( __CLASS__, 'do_redirect' ) );

		add_action( 'quadmenu_activation', array( __CLASS__, 'do_rating' ) );

		add_action( 'upgrader_process_complete', array( __CLASS__, 'update' ), 10, 2 );
	}

	static function update( $upgrader_object, $options ) {

		if ( $options['action'] == 'update' && $options['type'] == 'plugin' && isset( $options['plugins'] ) ) {

			foreach ( $options['plugins'] as $plugin ) {
				if ( $plugin == QUADMENU_PLUGIN_BASENAME ) {
					self::do_compiler();
					self::do_redirect();
				}
			}
		}
	}

	static function redirect() {

		if ( is_network_admin() ) {
			return;
		}

		if ( ! get_transient( '_quadmenu_redirect' ) ) {
			return;
		}

		delete_transient( '_quadmenu_redirect' );

		wp_redirect( admin_url( 'admin.php?page=' . QUADMENU_PANEL ) );
	}

	static function do_compiler() {
		update_option( '_quadmenu_compiler', true );
	}

	static function do_redirect() {
		set_transient( '_quadmenu_redirect', true, 30 );
	}

	static function do_rating() {
		set_transient( '_quadmenu_first_rating', true, MONTH_IN_SECONDS );
	}

	static function activation() {
		do_action( 'quadmenu_activation' );
	}

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}

