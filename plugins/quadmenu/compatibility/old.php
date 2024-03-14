<?php

if ( ! function_exists( '_QuadMenu' ) ) {

	function _QuadMenu() {
		if ( ! class_exists( 'QuadLayers\\QuadMenu\\Plugin', false ) ) {
			require_once QUADMENU_PLUGIN_DIR . '/lib/class-plugin.php';
		}
		return QuadLayers\QuadMenu\Plugin::instance();
	}

	_QuadMenu();
}

if ( ! function_exists( 'is_quadmenu_location' ) ) {

	function is_quadmenu_location( $location = false ) {
		if ( ! class_exists( 'QuadLayers\\QuadMenu\\Plugin', false ) ) {
			require_once QUADMENU_PLUGIN_DIR . '/lib/class-plugin.php';
		}
		return QuadLayers\QuadMenu\Plugin::instance()->is_quadmenu_location( $location );
	}
}
