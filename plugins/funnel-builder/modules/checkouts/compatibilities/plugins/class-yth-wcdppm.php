<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Name: YITH Dynamic Pricing per Payment Method for WooCommerce Premium by YITH
 * https://yithemes.com/themes/plugins/yith-dynamic-pricing-per-payment-method-for-woocommerce/
  class WFACP_Compatibility_With_Yith_Discount
 */
#[AllowDynamicProperties]
class WFACP_YITH_WCDPPM_Dynamic_Payment_Methods {
	public function __construct() {
		add_action( 'wfacp_template_load', [ $this, 'actions' ] );
		add_filter( 'wfacp_show_shipping_options', [ $this, 'show_shipping_on_load' ] );
		add_action( 'wfacp_internal_css', [ $this, 'add_css' ] );
	}

	public function enable() {
		return class_exists( 'YITH_WCDPPM_Dynamic_Payment_Methods_Frontend' );
	}

	public function actions() {
		if ( ! $this->enable() ) {
			return;
		}
		$this->instance = WFACP_Common::remove_actions( 'woocommerce_review_order_before_order_total', 'YITH_WCDPPM_Dynamic_Payment_Methods_Frontend', 'add_row_payment_method' );
	}

	public function show_shipping_on_load() {
		if ( ! $this->enable() ) {
			return false;
		}

		return true;
	}

	public function add_css() {
		if ( ! $this->enable() ) {
			return;
		}

		?>

        <script>
            window.addEventListener('bwf_checkout_load', function () {
                (function ($) {
                    function remove_multilink() {
                        let winW = window.innerWidth;

                        if (winW > 767) {
                            $('.wfacp_mb_mini_cart_sec_accordion_content .wfacp_mini_cart_reviews').find('#yith-wcdppm-amount').remove();
                        } else {

                            $('.wfacp_mini_cart_start_h').find('#yith-wcdppm-amount').remove();
                        }

                    }

                    $(document.body).on('updated_checkout', function () {
                        remove_multilink();
                    });
                })(jQuery);
            });

        </script>

		<?php


	}
}
WFACP_Plugin_Compatibilities::register( new WFACP_YITH_WCDPPM_Dynamic_Payment_Methods(), 'yith-dynamic-pricing-per-payment-method-for-woocommerce' );
