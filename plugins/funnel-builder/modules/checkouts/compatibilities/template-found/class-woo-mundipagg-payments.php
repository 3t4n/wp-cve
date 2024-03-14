<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Woo_Mundipagg_Payments {

	public $payment_id = 'woo-mundipagg-payments';

	public function __construct() {
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'woo_mundipagg_add_action' ], 99 );
		add_action( 'wfacp_internal_css', [ $this, 'wfacp_internal_css' ] );
	}

	public function woo_mundipagg_add_action() {

		add_action( 'woocommerce_receipt_' . $this->payment_id, array( $this, 'woo_mundipagg_open_div' ), 8 );
		add_action( 'woocommerce_receipt_' . $this->payment_id, array( $this, 'woo_mundipagg_close_div' ), 9999 );

	}

	public function woo_mundipagg_open_div() {
		echo "<div id=wfacp-mundipagg-payments>";

	}

	public function woo_mundipagg_close_div() {
		echo "</div>";

	}

	public function wfacp_internal_css() {

		?>

        <style>
            #wfacp-mundipagg-payments {
                margin: 15px 0;
                clear: none;
            }

            #wfacp-mundipagg-payments .woocommerce-tabs {
                margin: 0 0 15px;
            }

            #wfacp-mundipagg-payments .woocommerce-tabs ul {
                list-style: none;
                margin-bottom: 15px;
            }

            #wfacp-mundipagg-payments .woocommerce-tabs ul li {
                display: inline-block;
                margin-right: 10px;
                position: relative;
                padding-top: 10px;
            }

            #wfacp-mundipagg-payments .woocommerce-tabs ul li:last-child {
                margin: 0;
            }

            #wfacp-mundipagg-payments .woocommerce-tabs ul li a {
                padding: 10px 0;
            }

            #wfacp-mundipagg-payments .woocommerce-tabs ul + #payment {
            }

            #wfacp-mundipagg-payments .woocommerce-tabs ul + #payment p.form-row-first {
                width: 48%;
                float: left;
            }

            #wfacp-mundipagg-payments .woocommerce-tabs ul + #payment p.form-row-last {
                width: 48%;
                margin-left: 2%;
                float: left;
            }

            #wfacp-mundipagg-payments .woocommerce-tabs ul + #payment p.form-row-wide {
                width: 100%;
                float: none;
            }

            #wfacp-mundipagg-payments .woocommerce-tabs ul + #payment p.form-row label {
                display: block;
            }

            #wfacp-mundipagg-payments div.product .woocommerce-tabs ul.tabs li.active::before {
                content: ' ';
                position: absolute;
                width: 100%;
                height: 3px;
                box-shadow: none;
                top: 0;
                left: 0;
                border-radius: 0;
            }

            #wfacp-mundipagg-payments div.product .woocommerce-tabs ul.tabs li.active:before {
                background: #e86777;
            }

            #wfacp-mundipagg-payments .woocommerce-tabs ul + #payment p.form-row input[type="text"],
            #wfacp-mundipagg-payments .woocommerce-tabs ul + #payment p.form-row input[type="number"],
            #wfacp-mundipagg-payments .woocommerce-tabs ul + #payment p.form-row input[type="email"],
            #wfacp-mundipagg-payments .woocommerce-tabs ul + #payment p.form-row input.input-text,
            #wfacp-mundipagg-payments .woocommerce-tabs ul + #payment p.form-row select,
            #wfacp-mundipagg-payments .woocommerce-tabs ul + #payment p.form-row .select2-container .select2-selection--single .select2-selection__rendered {
                font-size: 14px;
                line-height: 1.5;
                width: 100%;
                background-color: #ffffff;
                border-radius: 4px;
                position: relative;
                color: #404040;
                display: block;
                min-height: 50px;
                padding: 10px 12px;
                vertical-align: top;
                box-shadow: none;
                opacity: 1;
                text-shadow: none;
                border: 1px solid #bfbfbf;
            }

            #wfacp-mundipagg-payments .woocommerce-tabs ul + #payment p {
                margin: 0 0 15px;
            }

            #wfacp-mundipagg-payments .woocommerce-tabs ul + #payment p.form-row-first:not(:last-child) {
                margin-right: 2%;
            }

            body #wfacp-mundipagg-payments a.button,
            body #wfacp-mundipagg-payments #wcmp-submit {
                border-radius: 50px;
                padding-top: 10px;
                padding-right: 25px;
                padding-bottom: 10px;
                padding-left: 25px;
                color: #ffffff !important;
                border-color: #999999 !important;
                background-color: #999999 !important;
                display: inline-block;
            }

            body #wfacp-mundipagg-payments a.button:hover,
            body #wfacp-mundipagg-payments #wcmp-submit:hover {
                background-color: #878484;
            }

            body #wfacp-mundipagg-payments input[type="checkbox"] {
                position: relative;
                left: auto;
                right: auto;
                top: auto;
                margin: 0;
                bottom: auto;
            }

            #wfacp-mundipagg-payments .woocommerce-tabs ul + #payment p.form-row-first:last-child {
                width: 100%;
                float: none;
                clear: both;
            }
        </style>
		<?php

	}
}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Woo_Mundipagg_Payments(), 'wfacp-woo-mundipagg' );
