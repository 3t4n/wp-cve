<?php
defined( 'ABSPATH' ) || exit;

add_action( 'nextmove_template_redirect_single_thankyou_page', 'xlwcty_modify_theme_settings', 100 );

/**
 * Here we have popular themes fallback functions to support our plugin
 * @global type $post
 */
if ( ! function_exists( 'xlwcty_modify_theme_settings' ) ) {

	function xlwcty_modify_theme_settings() {
		global $post;
		if ( $post instanceof WP_Post ) {

			// HCode
			if ( defined( 'HCODE_THEME' ) ) {
				add_action( 'wp_head', 'xlwcty_hcode_css' );
				function xlwcty_hcode_css() {
					ob_start();
					?>
                    <style>
                        .xlwcty_wrap .xlwcty_product .xlwcty_products li .xlwcty_pro_inner .onsale {
                            top: 0;
                            left: 0;
                            text-align: left;
                        }
                    </style>
					<?php
					echo ob_get_clean();
				}
			}

			// if divi theme
			if ( function_exists( 'et_setup_theme' ) ) {
				$et_page_layout = get_post_meta( $post->ID, '_et_pb_page_layout', true );
				if ( 'et_full_width_page' != $et_page_layout ) {
					update_post_meta( $post->ID, '_et_pb_page_layout', 'et_full_width_page' );
				}
				$et_use_builder = get_post_meta( $post->ID, '_et_pb_use_builder', true );
				if ( 'on' != $et_use_builder ) {
					update_post_meta( $post->ID, '_et_pb_use_builder', 'on' );
				}
			}

			// if the7 theme
			if ( defined( 'PRESSCORE_THEME_NAME' ) && PRESSCORE_THEME_NAME == 'the7' ) {
				$_dt_sidebar_position = get_post_meta( $post->ID, '_dt_sidebar_position', true );
				if ( 'disabled' != $_dt_sidebar_position ) {
					update_post_meta( $post->ID, '_dt_sidebar_position', 'disabled' );
				}
			}

			// if Infinite - Responsive Multi-Purpose WordPress Theme
			if ( function_exists( 'infinite_init_goodlayers_core_elements' ) ) {
				$options = get_post_meta( $post->ID, 'gdlr-core-page-option', true );
				if ( empty( $options ) ) {
					$core_options = array(
						'enable-header-area' => 'enable',
						'enable-page-title'  => 'disable',
						'show-content'       => 'enable',
						'sidebar'            => 'none',
						'header-slider'      => 'none',
					);
					update_post_meta( $post->ID, 'gdlr-core-page-option', $core_options );
				}
			}

			// if storefront theme
			if ( function_exists( 'storefront_page_header' ) ) {
				remove_action( 'storefront_page', 'storefront_page_header', 10 );
			}
		}
	}
}
