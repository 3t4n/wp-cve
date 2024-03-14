<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WooCommerce Points and Rewards By WooCommerce
 * https://woocommerce.com/products/woocommerce-points-and-rewards/
 */
#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_WC_Points_and_Reward {
	public $instance = null;
	public $message = '';

	public function __construct() {


		/* Unhook rewards and points  */
		add_action( 'wfacp_template_load', [ $this, 'actions' ] );
		add_action( 'wfacp_internal_css', [ $this, 'add_js' ] );
		add_action( 'wfacp_before_form', [ $this, 'render_message' ] );
		add_filter( 'woocommerce_update_order_review_fragments', [ $this, 'add_fragments' ], 150 );


	}

	public function actions() {

		if ( is_admin() ) {
			return;
		}


		
		if ( is_null( WC()->cart ) ) {
			return;
		}

		WFACP_Common::remove_actions( 'woocommerce_applied_coupon', 'WC_Points_Rewards_Cart_Checkout', 'discount_updated' );
		$this->instance = WFACP_Common::remove_actions( 'woocommerce_before_checkout_form', 'WC_Points_Rewards_Cart_Checkout', 'render_earn_points_message' );

		if ( ! $this->instance instanceof WC_Points_Rewards_Cart_Checkout ) {
			return;
		}

		ob_start();
		$this->instance->render_earn_points_message();
		$this->message = ob_get_clean();
	}

	public function add_fragments( $fragments ) {

		if ( ! $this->instance instanceof WC_Points_Rewards_Cart_Checkout || empty( $this->message ) ) {
			return $fragments;
		}

		$fragments['.wc_points_rewards_earn_points'] = $this->message;

		return $fragments;

	}

	public function render_message() {
		if ( empty( $this->message ) ) {
			return;
		}
		echo '<div class=wfacp_wc_rewards_checkout>';
		echo $this->message;
		echo "</div>";
	}

	public function add_js() {

		?>
        <style>
            .wfacp-hide-sec {
                display: none !important;
            }
        </style>
        <script>

            window.addEventListener('bwf_checkout_load', function () {
                (function ($) {

                    $(document.body).on('wfacp_coupon_form_removed', function () {
                        setTimeout(function () {
                            remove_reward_notices();
                        }, 500)
                    });

                    function remove_reward_notices() {
                        if ($('.woocommerce-info.wc_points_rewards_earn_points').length > 0) {
                            $('.woocommerce-info.wc_points_rewards_earn_points').addClass("wfacp-hide-sec");
                            $('.woocommerce-info.wc_points_redeem_earn_points').addClass("wfacp-hide-sec");

                            $('.wfacp_layout_9_coupon_error_msg .woocommerce-info.wc_points_rewards_earn_points').removeClass("wfacp-hide-sec");
                            $('.wfacp_layout_9_coupon_error_msg .woocommerce-info.wc_points_redeem_earn_points').removeClass("wfacp-hide-sec");
                        }
                    }
                })(jQuery);
            });
        </script>
		<?php
	}

}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_WC_Points_and_Reward(), 'wfacp-wc-pints-rewards' );
