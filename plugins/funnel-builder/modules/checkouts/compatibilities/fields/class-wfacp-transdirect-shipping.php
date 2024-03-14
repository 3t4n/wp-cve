<?php

/**
 * WooCommerce Transdirect Shipping By Transdirect
 * Author URI: https://www.transdirect.com.au/e-commerce/woo-commerce/
 * Version: 7.5
 */

#[AllowDynamicProperties]

  class WFACP_Compatibility_Transdirect_Shipping {


	public function __construct() {

		/* Register Add field */
		add_filter( 'wfacp_advanced_fields', [ $this, 'add_field' ], 20 );
		add_filter( 'wfacp_html_fields_wfacp_transdirect_shipping', '__return_false' );
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'actions' ] );
		add_action( 'process_wfacp_html', [ $this, 'call_fields_hook' ], 999, 3 );

	}

	public function actions() {

		if ( false === $this->is_enabled() ) {
			return '';
		}
		remove_action( 'woocommerce_after_checkout_billing_form', 'td_plugin_test' );
		add_action( 'wfacp_internal_css', [ $this, 'wfacp_internal_css' ] );
	}


	public function add_field( $fields ) {


		if ( false === $this->is_enabled() ) {
			return $fields;
		}


		$fields['wfacp_transdirect_shipping'] = [
			'type'       => 'wfacp_html',
			'class'      => [ 'wfacp-col-full', 'wfacp-form-control-wrapper', 'wfacp_anim_wrap', 'coderockz_woo_delivery' ],
			'id'         => 'wfacp_transdirect_shipping',
			'field_type' => 'wfacp_transdirect_shipping',
			'label'      => __( 'Transdirect Shipping', 'woocommerce_transdirect' ),
		];


		return $fields;
	}

	public function call_fields_hook( $field, $key, $args ) {

		if ( ! empty( $key ) && $this->is_enabled() && 'wfacp_transdirect_shipping' === $key ) {

			if ( function_exists( 'td_plugin_test' ) ) {
				echo "<div id=wfacp_transdirect_shipping_wrap class='wfacp-form-control-wrapper wfacp-col-full'>";
				td_plugin_test();
				echo "</div>";
			}

		}
	}

	public function is_enabled() {
		if ( function_exists( 'woocommerce_transdirect_init' ) ) {

			return true;
		}

		return false;

	}

	public function wfacp_internal_css() {
		?>

        <style>
            #wfacp_transdirect_shipping_wrap input[type=radio] {
                position: relative;
                left: auto;
                right: auto;
                bottom: auto;
                top: auto;
                margin: 0 5px 0 0;
            }

            #wfacp_transdirect_shipping_wrap br {
                display: none;
            }

            #wfacp_transdirect_shipping_wrap h4 {
                margin: 0 0 15px !important;
                color: #333333;
                font-size: 20px;
                font-weight: normal;
                line-height: 1.5;
            }

            #wfacp_transdirect_shipping_wrap label {
                color: #777777;
            }

            #autocomplete-div,
            #simple_autocomplete_div {
                padding: 0;
                z-index: 9999;
                border-color: #bfbfbf;
                margin-top: 6px;
                margin-left: 0;
            }

            #autocomplete-div ul li:last-child {
                margin: 0 !important;
            }

            #wfacp_transdirect_shipping_wrap p:empty {
                display: none;
            }

            #autocomplete-div ul li,
            #simple_autocomplete_div ul li {
                padding: 5px 10px !important;
            }

            div#wfacp_transdirect_shipping_wrap .tdCalc {
                margin-top: 0;
            }

            #wfacp_transdirect_shipping_wrap .td-trans-frm {
                width: 100% !important;
                padding: 15px;
                margin: 0;
                border-color: #bfbfbf;
            }

            #autocomplete-div {
                min-height: 100px;
                height: 100px;
            }

            #wfacp_transdirect_shipping_wrap button {
                border-radius: 2px;
                padding: 15px 15px;
                margin: 0;
                display: block;
                font-family: inherit;
                text-transform: capitalize;
                font-size: 14px;
                line-height: 1.5;
                font-weight: 600;
            }

            #wfacp_transdirect_shipping_wrap #btn-get-quote {
                margin-top: 15px;
            }

            #wfacp_transdirect_shipping_wrap p {
                margin: 0 0 15px;
            }

            #wfacp_transdirect_shipping_wrap input[type=text] {
                padding-top: 12px;
                padding-bottom: 12px;
            }

            #wfacp_transdirect_shipping_wrap {
                margin-bottom: 15px;
            }

            #shipping_type span {
                display: block;
                margin: 0 0 5px;
                font-weight: normal;
            }

            #shipping_type span b {
                font-weight: 400;
            }

            #shipping_type span:last-child {
                margin: 0;
            }
        </style>
		<?php

	}


}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_Transdirect_Shipping(), 'woocommerce_transdirect' );
