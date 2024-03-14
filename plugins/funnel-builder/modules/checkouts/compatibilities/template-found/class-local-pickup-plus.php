<?php

/**
 *
 * Comaptibility with local pickup plus by sky verge
 * location dropdown not creating
 *
 * #[AllowDynamicProperties] 

  class WFACP_Local_Pickup_Plus_SkyVerge
 */
#[AllowDynamicProperties] 

  class WFACP_Local_Pickup_Plus_SkyVerge {
	public function __construct() {
		add_filter( 'wfacp_after_discount_added_to_item', [ $this, 'update_item' ] );
		add_filter( 'woocommerce_is_checkout', [ $this, 'make_checkout' ] );
		add_action( 'wfacp_internal_css', [ $this, 'restrict_our_fragments' ] );
		add_filter( 'wc_get_template', [ $this, 'remove_review_order_summary' ], 10, 2 );
		add_action( 'wfacp_after_template_found', [ $this, 'remove_xt_floating_cart_fragments' ] );
	}

	public function update_item( $item ) {
		if ( $this->is_enabled() ) {
			$item['cart_item_key'] = $item['key'];
		}

		return $item;
	}

	// Xt Floating Plugin create issue with Local Pickup Dropdown Menu
	public function remove_xt_floating_cart_fragments() {
		if ( ! class_exists( 'XT_Woo_Floating_Cart_Ajax' ) || ! wp_doing_ajax() ) {
			return;
		}
		WFACP_Common::remove_actions( 'woocommerce_update_order_review_fragments', 'XT_Woo_Floating_Cart_Ajax', 'cart_fragments' );
	}

	private function is_enabled() {
		if ( class_exists( 'WC_Shipping_Local_Pickup_Plus' ) ) {
			return true;
		}

		return false;
	}

	public function make_checkout( $status ) {
		if ( $this->is_enabled() ) {
			if ( wp_doing_ajax() && isset( $_REQUEST['wc-ajax'] ) && false !== strpos( $_REQUEST['wc-ajax'], 'wfacp_' ) ) {
				$status = true;
			}
		}

		return $status;
	}

	public function remove_review_order_summary( $template, $template_name ) {
		if ( $this->is_enabled() ) {
			if ( wp_doing_ajax() && ( isset( $_REQUEST['wc-ajax'] ) && false !== strpos( $_REQUEST['wc-ajax'], 'wfacp_' ) || isset( $_REQUEST['wfacp_is_checkout_override'] ) ) ) {
				if ( $template_name == 'checkout/review-order.php' ) {
					$template = null;
				}
			}
		}

		return $template;
	}


	public function restrict_our_fragments() {
		if ( ! $this->is_enabled() ) {
			return;
		}
		?>
        <script>
            window.addEventListener('load', function () {
                (function ($) {
                    wfacp_frontend.hooks.addFilter('wfacp_stop_updating_fragments', function (rsp, send_data) {
                        if (send_data.hasOwnProperty('message') && send_data.message.hasOwnProperty('error')) {
                            return rsp;
                        }
                        return true;
                    });

                    wfacp_frontend.hooks.addAction('wfacp_stop_updating_fragments', function (rsp, send_data) {
                        jQuery('body').trigger('update_checkout');
                    });
                    $('body').on('updated_checkout', function () {
                        var row = $('.wfacp_coupon_row');
                        if (row.length > 0) {
                            row.unblock();
                            row.parents('form').removeClass('processing');
                        }
                    });
                })(jQuery);
            });
        </script>
		<?php
	}
}

new WFACP_Local_Pickup_Plus_SkyVerge();
