<?php
/**
 * GamiPress - WooCommerce Points Gateway By GamiPress
 * Plugin URI:  https://gamipress.com/add-ons/gamipress-wc-points-gateway
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_WC_Gamipress_Payement_Gateway {

	public function __construct() {
		/* checkout page */
		add_action( 'wfacp_internal_css', [ $this, 'add_js' ] );
	}

	public function add_js() {
		?>
        <style>

            body .wfacp_main_form.woocommerce table.shop_table.woocommerce-checkout-review-order-table tfoot tr:nth-last-child(2) td, body #wfacp-e-form table.shop_table.woocommerce-checkout-review-order-table tfoot tr:nth-last-child(2) th,
            body .wfacp_main_form.woocommerce table.shop_table.woocommerce-checkout-review-order-table tfoot tr:nth-last-child(2) td,
            body .wfacp_main_form.woocommerce table.shop_table.woocommerce-checkout-review-order-table tfoot tr:nth-last-child(2) th {
                padding-bottom: 2px !important;
            }
        </style>
        <script>
            window.addEventListener('load', function () {
                (function ($) {
                    function gamipress_wc_points_gateway_update_checkout() {
                        var payment_method = $('#payment.woocommerce-checkout-payment input.input-radio:checked').val();
                        if (payment_method !== undefined && payment_method.startsWith('gamipress_')) {
                            var points_type = payment_method.replace('gamipress_', '');
                            // Hide previously active gateway
                            $('.gamipress-wc-points-gateway-active').removeClass('gamipress-wc-points-gateway-active').hide();

                            // Show current active gateway
                            $('.gamipress-wc-points-gateway-wrap').addClass('gamipress-wc-points-gateway-active').show();
                            $('.gamipress-wc-points-gateway-wrap').addClass('gamipress-wc-points-gateway-active').show();
                            $('.gamipress-wc-points-gateway-wrap').addClass('gamipress-wc-points-gateway-active').show();
                        } else {
                            // Hide previously active gateway
                            $('.gamipress-wc-points-gateway-active').removeClass('gamipress-wc-points-gateway-active').hide();
                        }
                    }

                    $('body').on('change', '#payment.woocommerce-checkout-payment input.input-radio', function () {
                        gamipress_wc_points_gateway_update_checkout();
                    });

                    // Trigger a change event on checked radio on loading the page
                    if ($('#payment.woocommerce-checkout-payment input.input-radio:checked').length > 0) {
                        gamipress_wc_points_gateway_update_checkout();
                    }

                    $('body').on('updated_checkout', function () {
                        gamipress_wc_points_gateway_update_checkout();
                    });
                })(jQuery);
            });

        </script>
		<?php
	}


}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_WC_Gamipress_Payement_Gateway(), 'wfacp-wc_gmpg' );
