<?php

namespace QuadLayers\QLWAPP;

use QuadLayers\QLWAPP\Api\Admin_Menu_Routes_Library;

final class Plugin {

	protected static $instance;

	private function __construct() {
		load_plugin_textdomain( 'wp-whatsapp-chat', false, QLWAPP_PLUGIN_DIR . '/languages/' );
		Admin_Menu_Routes_Library::instance();
		Controllers\Helpers::instance();
		Controllers\Components::instance();
		Controllers\Admin_Menu::instance();
		Controllers\Admin_Menu_WooCommerce::instance();
		Frontend::instance();
		WooCommerce::instance();
		add_action( 'admin_footer', array( __CLASS__, 'add_premium_css' ) );
		do_action( 'qlwapp_init' );
	}

	public static function add_premium_css() {
		?>
		<style>
			.qlwapp-premium-field {
				opacity: 0.5; 
				pointer-events: none;
			}
			.qlwapp-premium-field .description {
				display: block!important;
			}
		</style>
		<?php
	}

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}

Plugin::instance();
