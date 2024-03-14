<?php

namespace GRIM_CEW;

use GRIM_CEW\Vendor\Controller;

class Frontend extends Controller {
	public function __construct() {
		$this->enable_gutenberg_styles();
	}

	public function enable_gutenberg_styles() {
		if ( is_admin() ) {
			return;
		}

		if ( ! Settings::get_option( 'enable_frontend' ) ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'disable_block_styles' ) );
		}
	}

	public function disable_block_styles() {
		wp_dequeue_style( 'wp-block-library' );
		wp_dequeue_style( 'wp-block-library-theme' );

		// WooCommerce compatibility
		if ( class_exists( 'woocommerce' ) ) {
			wp_dequeue_style( 'wc-blocks-style' );
			wp_dequeue_style( 'wc-all-blocks-style' );
			wp_dequeue_style( 'wc-blocks-vendors-style' );
			wp_deregister_style( 'wc-blocks-style' );
			wp_deregister_style( 'wc-all-blocks-style' );
			wp_deregister_style( 'wc-blocks-vendors-style' );
		}

		// WPML compatibility
		if ( class_exists( '\WPML\BlockEditor\Loader' ) ) {
			wp_deregister_style( \WPML\BlockEditor\Loader::SCRIPT_NAME );
		}
	}
}
