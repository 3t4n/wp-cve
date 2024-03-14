<?php

/**
 * Developed by SomewhereWarm
 * #[AllowDynamicProperties] 

  class WFACP_WOOCOMMERCE_Gift_Card
 */
#[AllowDynamicProperties] 

  class WFACP_WOOCOMMERCE_Gift_Card_Compatiblity {
	protected $gift_card_matches = [];
	protected $apply_gift_card = false;

	public function __construct() {
		add_action( 'wfacp_internal_css', [ $this, 'internal_css_js' ] );
		add_filter( 'wfacp_apply_coupon_via_ajax', [ $this, 'stop_normal_coupon_apply' ], 10, 2 );
		add_action( 'wfacp_apply_coupon_via_ajax_placeholder', [ $this, 'apply_gift_card' ], 10, 2 );
		add_action( 'wfacp_after_template_found', [ $this, 'attach_hooks' ] );
	}


	public function attach_hooks() {
		add_filter( 'woocommerce_gc_disable_ui', '__return_false', 999 );
	}

	public function stop_normal_coupon_apply( $status, $post ) {
		if ( ! function_exists( 'WC_GC' ) || ! class_exists( 'WC_GC_Gift_Card' ) ) {
			return $status;
		}
		$coupon_code = $post['coupon_code'];
		$match       = preg_match( apply_filters( 'woocommerce_gc_coupon_input_pattern', '/(?>[a-zA-Z0-9]{4}\-){3}[a-zA-Z0-9]{4}/', $coupon_code ), $coupon_code, $matches );
		if ( $match && ! empty( $matches ) ) {
			$status                  = false;
			$this->apply_gift_card   = true;
			$this->gift_card_matches = $matches;
		}

		return $status;
	}

	public function apply_gift_card() {
		if ( ! function_exists( 'WC_GC' ) || ! class_exists( 'WC_GC_Gift_Card' ) ) {
			return false;
		}

		if ( $this->apply_gift_card ) {
			$giftcard_code = array_pop( $this->gift_card_matches );
			$results       = WC_GC()->db->giftcards->query( array( 'return' => 'objects', 'code' => $giftcard_code, 'limit' => 1 ) );
			$giftcard_data = count( $results ) ? array_shift( $results ) : false;

			if ( $giftcard_data ) {

				$giftcard = new WC_GC_Gift_Card( $giftcard_data );

				try {

					// If logged in check if auto-redeem is on.
					if ( get_current_user_id() && apply_filters( 'woocommerce_gc_auto_redeem', false ) ) {
						$giftcard->redeem( get_current_user_id() );
					} else {
						WC_GC()->giftcards->apply_giftcard_to_session( $giftcard );
					}

					wc_add_notice( __( 'Gift Card code applied successfully!', 'woocommerce-gift-cards' ) );

				} catch ( Exception $e ) {
					wc_add_notice( $e->getMessage(), 'error' );
				}

			} else {
				wc_add_notice( __( 'Gift Card not found.', 'woocommerce-gift-cards' ), 'error' );
			}

		}
	}


	public function internal_css_js() {

		$instance = wfacp_template();
		if ( ! function_exists( 'WC_GC' ) || ! $instance instanceof WFACP_Template_Common ) {
			return;
		}

		$bodyClass = "body";

		if ( 'pre_built' !== $instance->get_template_type() ) {

			$bodyClass = "body #wfacp-e-form";
		}


		echo "<style>";
		echo $bodyClass . " .wfacp_order_summary_container label[for='use_gift_card_balance'] {text-align: right;display:block;}";
		echo $bodyClass . " .wfacp_order_summary_container label[for='use_gift_card_balance'] input[type='checkbox']{position: relative;left: auto;right: auto;top: auto;bottom: auto;margin: 0 5px 0 0;}";
		echo "</style>";
		?>
        <script>
            window.addEventListener('load', function () {
                (function ($) {
                    if (typeof wc_gc_params != "object") {
                        return;
                    }
                    var get_url = function (endpoint) {

                        return wc_gc_params.wc_ajax_url.toString().replace(
                            '%%endpoint%%',
                            endpoint
                        );
                    };
                    $(document.body).on('click', '.wc_gc_remove_gift_card', function (e) {
                        e.preventDefault();

                        var $el = $(this), giftcard_id = $el.data('giftcard');
                        var parent = $el.parents('.shop_table');
                        parent.block({

                            message: null,
                            overlayCSS: {
                                background: '#fff',
                                opacity: 0.6
                            }
                        });
                        $.ajax({
                            type: 'post',
                            url: get_url('remove_gift_card_from_session'),
                            data: 'wc_gc_cart_id=' + giftcard_id + '&render_cart_fragments=1&security=' + wc_gc_params.security_remove_card_nonce,
                            dataType: 'html',
                            complete: function () {
                                $(document.body).trigger('update_checkout');
                            }
                        });

                        return false;
                    });
                    $(document.body).on('click', '#wc_gc_cart_redeem_send', function (e) {
                        e.preventDefault();

                        var code = $('#wc_gc_cart_code').val();

                        if (!code) {
                            return false;
                        }
                        $(document.body).trigger('update_checkout');
                        return false;
                    });
                })(jQuery)
            });

        </script>
		<?php
	}
}
new WFACP_WOOCOMMERCE_Gift_Card_Compatiblity();