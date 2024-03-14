<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Mercado Pago payments for WooCommerce
 * URI: https://github.com/mercadopago/cart-woocommerce *
 * PropulsePay payment gateway for WooCommerce
 * https://skillsup.in/
 */
class WFACP_Compatibility_With_Brazillian_Gateway {


	public function __construct() {
		add_action( 'wfacp_internal_css', [ $this, 'internal_css_js' ] );
	}

	public function internal_css_js() {

		?>
        <style>
            body #wfacp-sec-wrapper .mp-input-document .mp-input .mp-document-select {
                height: auto !important;
            }

            body #wfacp-sec-wrapper #mp-custom-checkout-form-container select {
                height: auto !important;
            }

            body #wfacp-sec-wrapper #checkout {
                display: block;
                position: relative;
            }

            body #wfacp-sec-wrapper .wfacp_main_form #checkout .wfacp_coupon_field_msg > .wfacp_single_coupon_msg,
            body #wfacp-sec-wrapper .wfacp_main_form #checkout .wfacp_coupon_error_msg > .woocommerce_single_error_message,
            body #wfacp-sec-wrapper .wfacp_main_form #checkout .wfacp_coupon_remove_msg {
                margin-top: 8px !important;
                margin-bottom: 0 !important;
            }

            body #wfacp-sec-wrapper .wfacp_main_form #checkout .wfacp_coupon_remove_msg {
                margin-left: 8px;
                margin-right: 8px;
            }

            body #wfacp-sec-wrapper #checkout .wfacp-next-btn-wrap button:after,
            body #wfacp-sec-wrapper #checkout .wfacp-next-btn-wrap #place_order:after {
                display: block;
                line-height: 1.3;
                margin-top: 2px;
            }

            body #wfacp-sec-wrapper .wfacp_main_form.woocommerce #checkout #payment .wc-stripe-elements-field,
            body #wfacp-sec-wrapper .wfacp_main_form.woocommerce #checkout #payment .wc-stripe-iban-element-field {
                padding: 10px;
                margin: 0 0 10px;
                height: auto;
            }

            body #wfacp-sec-wrapper #checkout #payment ul li ul li,
            body #wfacp-sec-wrapper #checkout #payment ul li ul li {
                width: auto;
                padding: 0;
                border: none;
            }

            body #wfacp-sec-wrapper .wfacp_main_form.woocommerce #checkout .wfacp_product_switcher:not(.wfacp-section) .shop_table.wfacp-product-switch-panel {
                margin-bottom: 0;
            }

            body #wfacp-sec-wrapper .wfacp_main_form.woocommerce #checkout .shop_table.wfacp-product-switch-panel {
                margin-bottom: 16px;
            }

            body #wfacp-sec-wrapper #wfacp-e-form .wfacp_main_form.woocommerce #checkout input[type=radio]:checked {
                -webkit-transition: all 0.2s ease-in-out;
                transition: all 0.2s ease-in-out;
                background: #fff;
            }

            body #wfacp-sec-wrapper #checkout .wfacp-coupon-field-btn.wfacp_btn_clicked {
                position: relative;
                color: transparent !important;
                transition: none;
            }

            body #wfacp-sec-wrapper #checkout .wfacp-coupon-field-btn.wfacp_btn_clicked:before {
                position: absolute;
                left: 0;
                right: 0;
                top: 50%;
                content: '';
                width: 16px;
                margin: -8px auto auto;
                height: 16px;
                border: 2px solid #fff;
                border-bottom-color: transparent;
                border-radius: 50%;
                display: block !important;
                box-sizing: border-box;
                animation: rotation 1s linear infinite;
            }

            /*---------------------------------------new multi Select field --------------------------------------- */
            body #wfacp-sec-wrapper #checkout .wfacp_custom_field_multiselect label {
                position: relative;
                top: auto !important;
                left: 0;
                bottom: auto;
                margin: 0 0 4px;
            }
            body #wfacp-sec-wrapper #checkout .wfacp_custom_field_multiselect span.select2-selection.select2-selection--multiple {
                padding: 0;
                min-height: 48px;
            }

            body #wfacp-sec-wrapper #checkout .wfacp_custom_field_multiselect .select2-container--default .select2-search--inline .select2-search__field {
                min-height: 1px;
                border: none;
            }

            body #wfacp-sec-wrapper .wfacp-modern-label #checkout .wfacp_custom_field_multiselect .select2-container--default .select2-search--inline .select2-search__field {
                padding: 12px 12px !important;
            }

            body #wfacp-sec-wrapper #checkout .form-row:not(.wfacp_custom_field_multiselect) .select2-container--default.select2-container--focus .select2-selection--multiple,
            body #wfacp-sec-wrapper #checkout .form-row:not(.wfacp_custom_field_multiselect) .select2-container--default .select2-selection--multiple {
                border-color: transparent;
            }

            body #wfacp-sec-wrapper #checkout .select2-selection__rendered {
                padding-right: 30px;
            }

            /* -------------------------------------------Live Validation---------------------------------------------*/

            body #wfacp-sec-wrapper .wfacp_main_form.woocommerce #checkout .wfacp_divider_field.wfacp_divider_billing,
            body #wfacp-sec-wrapper .wfacp_main_form.woocommerce #checkout .wfacp_divider_field.wfacp_divider_shipping,
            body #wfacp-sec-wrapper .wfacp_main_form.woocommerce #checkout .wfacp_advanced_field_wrap {
                --bs-gutter-x: 10px;
                --bs-gutter-y: 0;
                display: flex;
                flex-wrap: wrap;
                clear: both;
                width: 100%;
            }

            body #wfacp-sec-wrapper .wfacp_main_form.woocommerce #checkout .wfacp_divider_field .wfacp-col-full,
            body #wfacp-sec-wrapper .wfacp_main_form.woocommerce #checkout .wfacp_advanced_field_wrap > .wfacp-col-full {
                flex: 0 0 auto;
                width: 100%;
            }

            body #wfacp-sec-wrapper .wfacp_main_form.woocommerce #checkout .wfacp_divider_field .wfacp-col-left-third,
            body #wfacp-sec-wrapper .wfacp_main_form.woocommerce #checkout .wfacp_advanced_field_wrap > .wfacp-col-left-third {
                flex: 0 0 auto;
                width: 33.33%;
                float: none;
            }

            body #wfacp-sec-wrapper .wfacp_main_form.woocommerce #checkout .wfacp_divider_field .wfacp-col-left-half,
            body #wfacp-sec-wrapper .wfacp_main_form.woocommerce #checkout .wfacp_advanced_field_wrap > .wfacp-col-left-half {
                float: none;
                flex: 0 0 auto;
                width: 50%;
            }

            body #wfacp-sec-wrapper .wfacp_main_form.woocommerce #checkout .wfacp_divider_field .wfacp-col-two-third,
            body #wfacp-sec-wrapper .wfacp_main_form.woocommerce #checkout .wfacp_advanced_field_wrap > .wfacp-col-two-third {
                float: none;
                flex: 0 0 auto;
                width: 66.66%;
            }


            body #wfacp-sec-wrapper .wfacp_main_form.woocommerce #checkout #billing_first_name_field + .wfacp_advanced_field_wrap,
            body #wfacp-sec-wrapper .wfacp_main_form.woocommerce #checkout #billing_last_name_field + .wfacp_advanced_field_wrap,
            body #wfacp-sec-wrapper .wfacp_main_form.woocommerce #checkout #billing_email_name_field + .wfacp_advanced_field_wrap {
                display: block;
                clear: unset;
            }
            /* -------------------------------------------Auto Complete Address ------------------------------------------*/

            body #wfacp-sec-wrapper #checkout .wfacp_autocomplete_active .woocommerce-input-wrapper {
                position: relative;
            }

            /* --------------------------------- Collapsible Optional field ------------------------*/
            body #wfacp-sec-wrapper .wfacp_main_form.woocommerce #checkout p.form-row.wfacp_collapsible_field_wrap *,
            body #wfacp-sec-wrapper .wfacp_main_form.woocommerce #checkout p.form-row.wfacp_collapsible_field_wrap {
                line-height: 1 !important;
            }

            body #wfacp-sec-wrapper #checkout .wfacp_collapsible_enable .select2-container {
                width: 100% !important;
            }

            @media (min-width: 768px)and (max-width: 1024px) {
                body #wfacp-sec-wrapper .wfacp_main_form.woocommerce #checkout .wfacp-helping-text:before {
                    top: 30px;
                    text-align: left;
                    width: 160px;
                    left: -60px;
                }
            }

            @media (max-width: 767px) {
                body #wfacp-sec-wrapper .wfacp_main_form.woocommerce #checkout .wfacp-helping-text:before {
                    top: 30px;
                    text-align: left;
                    width: 160px;
                    left: -60px;
                }
            }

            @media (max-width: 480px) {
                /*----------------------------------------Preview Style-----------------------------------------*/
                body #wfacp-sec-wrapper .wfacp_main_form.woocommerce #checkout .wfacp-helping-text:before {
                    top: 32px;
                    text-align: left;
                    width: 200px;
                    left: -90px;
                }

                body #wfacp-sec-wrapper .wfacp_main_form.woocommerce #checkout .wfacp-helping-text {
                    width: 44px;
                    height: 44px;
                    right: 0;
                    margin-top: -22px;
                    background-size: 16px;
                }
            }

        </style>


        <script>
            window.addEventListener('bwf_checkout_load', function () {
                (function ($) {
                    let gateways = ["woo-mercado-pago-custom", "propulsepay-credit-card"];
                    // WooCommerce Marcado Emi Gateway
                    let mercado_gateway = $('#payment_method_woo-mercado-pago-custom');
                    if (mercado_gateway.length > 0) {
                        let form_attributes = $("input[form='wfacp_checkout_form']");
                        // change attribute to checkout from wfacp_checkout_form because of mercado pago change checkout form id to checkout
                        //
                        form_attributes.each(function () {
                            $(this).attr('form', 'checkout');
                        });
                    }


                    function global_ajax_response() {
                        let selected_method = $('input[name="payment_method"]:checked');
                        if (selected_method.length === 0) {
                            return;
                        }
                        let gateway_id = selected_method.val();
                        if (gateways.indexOf(gateway_id) > -1) {
                            $('body').trigger('update_checkout');
                        }
                    }

                    wfacp_frontend.hooks.addAction('wfacp_ajax_response', global_ajax_response);// Run When Our Action is running
                })(jQuery);
            })

        </script>
		<?php
	}

}


	WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Brazillian_Gateway(), 'brazilian_gateway' );


