<?php

/**
 * YITH WooCommerce Dynamic Pricing and Discounts Premium
 * https://yithemes.com/themes/plugins/yith-woocommerce-dynamic-pricing-and-discounts/
 * #[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Yith_Discount
 */
class  WFACP_Compatibility_With_Yith_Discount {
	private $process = false;

	public function __construct() {
		add_action( 'wfacp_internal_css', [ $this, 'js' ] );
		add_action( 'woocommerce_checkout_update_order_review', [ $this, 'get_data' ], 5 );
		add_filter( 'woocommerce_update_order_review_fragments', [ $this, 'unset_fragments' ], 900 );
	}

	public function get_data( $data ) {

		if ( empty( $data ) ) {
			return;
		}
		parse_str( $data, $post_data );
		if ( empty( $post_data ) || ! isset( $post_data['wfacp_input_hidden_data'] ) || empty( $post_data['wfacp_input_hidden_data'] ) ) {
			return;
		}

		$bump_action_data = json_decode( $post_data['wfacp_input_hidden_data'], true );

		if ( empty( $bump_action_data ) ) {
			return;
		}
		if ( isset( $bump_action_data['unset_fragments'] ) ) {
			$this->process = true;
		}
	}

	public function unset_fragments( $fragments ) {

		if ( false == $this->process ) {
			return $fragments;
		}
		foreach ( $fragments as $k => $fragment ) {

			if ( false !== strpos( $k, 'wfacp' ) && true == apply_filters( 'wfacp_unset_our_fragments_by_yith_discount', true, $k ) ) {
				unset( $fragments[ $k ] );
			}
		}
		unset( $fragments['cart_total'] );

		return $fragments;
	}

	public function js() {
		?>
        <script>
            window.addEventListener('load', function () {
                (function ($) {
                    wfacp_frontend.hooks.addFilter('wfacp_before_ajax_data_update_product_qty', set_custom_data);
                    wfacp_frontend.hooks.addFilter('wfacp_before_ajax_data_update_cart_item_quantity', set_custom_data);
                    wfacp_frontend.hooks.addFilter('wfacp_before_ajax_data_wfacp_restore_cart_item', set_custom_data);
                    wfacp_frontend.hooks.addAction('wfacp_ajax_response', trigger_checkout);

                    function set_custom_data(data) {
                        data['unset_fragments'] = 'yes';
                        return data;
                    }

                    function trigger_checkout() {
                        $(document.body).trigger('update_checkout');
                    }

                })(jQuery);
            });
        </script>
		<?php
	}
}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Yith_Discount(), 'yith-discount' );