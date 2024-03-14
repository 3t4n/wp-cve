<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

class VI_WPRODUCTBUILDER_F_FrontEnd_Redirect {
	/**
	 * Stores chosen attributes
	 * @var array
	 */

	public function __construct() {
		add_filter( 'template_include', array( $this, 'archive_template_function' ), 1 );
		add_filter( 'single_template', array( $this, 'single_template_function' ), 2 );

	}

	/**
	 * Redirect to archive page
	 *
	 * @param $template_path
	 *
	 * @return string
	 */
	public function archive_template_function( $template_path ) {
		if ( get_post_type() == 'woo_product_builder' ) {
			do_action( 'woocommerce_product_builder_template_load' );
			if ( is_archive() ) {
				if ( $theme_file = locate_template( array( 'archive-product-builder.php' ) ) ) {
					$template_path = $theme_file;
				} else {
					$template_path = VI_WPRODUCTBUILDER_F_TEMPLATES . 'archive-product-builder.php';
				}
			}
		}

		return $template_path;
	}

	/**
	 * Register Custom Template
	 */
	public function single_template_function( $template_path ) {
		if ( get_post_type() == 'woo_product_builder' ) {
			do_action( 'woocommerce_product_builder_template_load' );
			if ( is_single() ) {
				if ( $theme_file = locate_template( array( 'single-product-builder.php' ) ) ) {
					$template_path = $theme_file;
				} else {
					$template_path = VI_WPRODUCTBUILDER_F_TEMPLATES . 'single-product-builder.php';
				}
			}
		}

		return $template_path;
	}
}


