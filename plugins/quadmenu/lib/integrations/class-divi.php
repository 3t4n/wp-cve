<?php

namespace QuadLayers\QuadMenu\Integrations;

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

use QuadLayers\QuadMenu\Integrations\Divi\Module;

/**
 * Divi ex QuadMenu_Divi_Module Class
 */
class Divi {

	private static $instance;

	public function __construct() {
		add_action( 'divi_extensions_init', array( $this, 'includes' ) );
	}

	function includes() {
		Module::instance();
	}

	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

}

