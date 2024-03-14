<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Tipping for WooCommerce by WPSlash
 * Plugin URI: https://www.wpslash.com
 * Version:           1.0.6
 */
#[AllowDynamicProperties]

  class WFACP_Compatibility_With_WC_Tipping {

	public function __construct() {

		/* Add field in the advanced option */
		add_filter( 'wfacp_advanced_fields', [ $this, 'add_field' ], 20 );
		add_filter( 'wfacp_html_fields_wfacp_wc_tipping', '__return_false' );

		/* Display the field */
		add_action( 'process_wfacp_html', [ $this, 'process_wfacp_html' ], 10, 2 );

		/* remove tipping hook from aerocheckout page */
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'remove_tipping_hook' ] );

		/* styling for tipping field */
		add_action( 'wfacp_internal_css', [ $this, 'wfacp_internal_css' ] );
	}

	public function add_field( $fields ) {

		if ( ! $this->is_enabled() ) {

			return $fields;
		}
		$fields['wfacp_wc_tipping'] = [
			'type'       => 'wfacp_html',
			'class'      => [ 'wfacp-col-full', 'wfacp-form-control-wrapper', 'wfacp_anim_wrap', 'wfacp-wc-tipping' ],
			'id'         => 'wfacp_wc_tipping',
			'field_type' => 'wfacp_wc_tipping',
			'label'      => __( 'WooCommerce Tipping', 'woofunnels-aero-checkout' ),

		];


		return $fields;

	}

	public function process_wfacp_html( $field, $key ) {

		if ( ! $this->is_enabled() ) {
			return;
		}

		if ( 'wfacp_wc_tipping' === $key && function_exists( 'wpslash_tipping_woocommerce_checkout_order_review_form' ) ) {

			echo "<div id=wfacp_wc_tipping>";
			wpslash_tipping_woocommerce_checkout_order_review_form();
			echo "</div>";

		}

	}

	public function remove_tipping_hook() {
		if ( ! $this->is_enabled() ) {
			return;
		}

		remove_action( 'woocommerce_review_order_after_cart_contents', 'wpslash_tipping_woocommerce_checkout_order_review_form', 10, 0 );
	}

	public function is_enabled() {

		return function_exists( 'wpslash_tipping_woocommerce_checkout_order_review_form' );
	}

	public function wfacp_internal_css() {
		if ( ! $this->is_enabled() ) {
			return;
		}

		?>

        <style>
            #wfacp_wc_tipping .wpslash-tip-wrapper {
                margin-bottom: 25px;
            }

            body .wfacp_main_form.woocommerce #wfacp_wc_tipping .wpslash-tipping-form-wrapper input.wpslash-tip-input {
                width: auto;
                height: auto;
                min-height: auto;
                padding: 10px;
                margin: 0;
                margin-right: 1%;
                text-align: left;
            }

            body .wfacp_main_form.woocommerce #wfacp_wc_tipping .wpslash-tipping-form-wrapper a.wpslash-tip-submit {
                width: auto;
                padding: 10px 60px;
                display: block;
                min-width: 1px;
                min-height: 1px;
                flex: none;
                text-align: center;
                margin: 0;
                border: none;
                margin-left: 1%;
            }

            body .wpslash_tip_remove_btn {
                right: auto;
                position: relative;
                left: auto;
                top: auto;
                display: inline-block;
                margin-left: 5px;
                background: #cc0000 !important;
            }

            #wfacp_wc_tipping a.wpslash-tip-percentage-btn {
                position: relative;
                padding: 10px;
                width: auto;
                text-align: center;
                float: none;
                font-size: 13px;
                line-height: 1.5;
                background-color: #28a745 !important;
                border: 1px solid #808080;
                margin: 4px;
            }

            body .wfacp_main_form.woocommerce tr.fee {
                text-align: right;
            }


            body .wfacp_main_form.woocommerce #wfacp_wc_tipping a.wpslash-tip-submit {


                box-shadow: 0px 1px 0px 0px #000000;
                background-color: #28a745;
                border-radius: 8px;
                border: 1px solid #808080;
                cursor: pointer;
                text-shadow: 0px 1px 0px #282828;
                border-top-left-radius: 0px;
                border-bottom-left-radius: 0px;

            }
        </style>
		<?php
	}


}
WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_WC_Tipping(), 'wfacp-wc-tipping' );
