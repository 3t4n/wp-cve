<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'cmb2_XL_TABS_Assets' ) ) {
	class cmb2_XL_TABS_Assets {

		public function __construct() {
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_assets' ) );
		}


		public function admin_assets() {
			// css
			wp_enqueue_style( 'dtheme-cmb2-tabs', plugin_dir_url( __DIR__ ) . 'assets/css/cmb2-tabs.css', array(), '1.0.1' );

			// js
			wp_enqueue_script( 'jquery-ui' );
			wp_enqueue_script( 'dtheme-cmb2-tabs', plugin_dir_url( __DIR__ ) . 'assets/js/cmb2-tabs.js', array( 'jquery-ui-tabs' ) );
		}

	}
}