<?php
/**
 * Theme Name:  Goya
 * Theme URL:  http://goya.everthemes.com/
 * Version: 1.0.3
 * Author: Everthemes
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Goya {

	public function __construct() {
		add_action( 'wfacp_checkout_page_found', [ $this, 'unhook_theme_actions' ] );
		add_action( 'wfacp_internal_css', [ $this, 'wfacp_internal_css' ] );

	}

	public function unhook_theme_actions() {
		add_filter( 'body_class', [ $this, 'body_class' ], 99 );
		if ( function_exists( 'goya_scripts' ) ) {
			add_filter( 'wfacp_css_js_deque', [ $this, 'wfacp_css_js_deque' ], 10, 3 );
		}

	}

	public function wfacp_css_js_deque( $bool, $path, $url ) {

		if ( false !== strpos( $url, '/vendor/lazysizes.min.js' ) ) {
			return false;
		}

		return $bool;
	}


	public function body_class( $body_class ) {

		if ( ! function_exists( 'goya_body_classes' ) ) {
			return $body_class;
		}

		if ( is_array( $body_class ) && count( $body_class ) > 0 ) {
			foreach ( $body_class as $key => $clasName ) {
				if ( false !== strpos( $clasName, 'el-' ) || $clasName == 'floating-labels' ) {
					$searchKey = array_search( $clasName, $body_class );
					if ( isset( $body_class[ $searchKey ] ) ) {
						unset( $body_class[ $searchKey ] );
					}


				}
			}
		}

		return $body_class;
	}

	public function wfacp_internal_css() {

		?>

        <style>
            body .wfacp_main_form.woocommerce .woocommerce-account-fields {
                margin-bottom: initial;
                padding: 0;
                background: transparent;
                border-radius: 0;
            }

            body .wfacp_main_form.woocommerce .mc4wp-checkbox.mc4wp-checkbox-woocommerce span:after,
            body .wfacp_main_form.woocommerce #ship-to-different-address label:after,
            body .wfacp_main_form.woocommerce .woocommerce-account-fields label span:after {
                display: none;
            }

            body .wfacp_main_form.woocommerce input[type=checkbox]:checked:before {
                transform: none;
            }

        </style>
		<?php
	}

}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Goya(), 'wfacp-goya' );
