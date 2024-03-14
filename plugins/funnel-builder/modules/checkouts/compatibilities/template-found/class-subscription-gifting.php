<?php

/**
 * #[AllowDynamicProperties] 

  class WFACP_Subscription_gift
 * WooCommerce Subscriptions Gifting
 * By WooCommerce
 */
#[AllowDynamicProperties] 

  class WFACP_Subscription_gift {
	public function __construct() {
		add_action( 'woocommerce_checkout_after_customer_details', [ $this, 'print_fields' ] );
		add_action( 'wfacp_internal_css', [ $this, 'handle_js' ] );
		add_action( 'woocommerce_checkout_update_order_review', [ $this, 'store_recipients_in_session' ], 50, 1 );
	}


	public function print_fields() {
		echo "<div id='wfacp-woocommerce-subscriptions-gifting' style='display:none !important;'><input type='hidden' name='wfacp_subscription_gifting' id='wfacp_subscription_gifting'> </div>";
	}

	public function store_recipients_in_session( $checkout_data ) {
		parse_str( $checkout_data, $checkout_data );
		$gifting_data = $checkout_data['wfacp_subscription_gifting'];
		$gifting_data = json_decode( $gifting_data, true );
		if ( is_array( $gifting_data ) && count( $gifting_data ) > 0 ) {

			foreach ( WC()->cart->cart_contents as $key => $item ) {
				if ( isset( $gifting_data[ $key ] ) ) {
					WCS_Gifting::update_cart_item_key( $item, $key, $gifting_data[ $key ]['input'] );
				}
			}
		}
	}

	public function handle_js() {

		?>
        <style>
            body .wfacp-form table.shop_table.woocommerce-checkout-review-order-table .product-name fieldset {
                position: relative;
            }

            p.form-row.form-row.woocommerce_subscriptions_gifting_recipient_email.woocommerce-invalid input {
                border: 1px solid red;
            }

            p.form-row.form-row.woocommerce_subscriptions_gifting_recipient_email input {
                padding: 5px;
                border: 1px solid #a7a7a7;
                background: #fff;
            }
        </style>

        <script>
            window.addEventListener('DOMContentLoaded', function () {
                (function ($) {
                    function copy_html() {
                        var mini_cart = $('.wfacp_mini_cart_items fieldset');
                        if (mini_cart.length == 0) {
                            return;
                        }
                        var object = {};
                        mini_cart.each(function () {
                            var parent = $(this).closest('.wfacp_product_row');
                            if (parent.length > 0) {
                                var cart_key = parent.attr('cart_key');
                                if (cart_key == '') {
                                    return;
                                }
                                var checkbox = parent.find('.woocommerce_subscription_gifting_checkbox');
                                object[cart_key] = {input: '', 'checked': false};
                                if (checkbox.is(":checked")) {
                                    object[cart_key].checked = true;
                                    var input_val = parent.find('.recipient_email');
                                    if (input_val.val() != '') {
                                        object[cart_key].input = input_val.val();
                                    }
                                } else {
                                    object[cart_key].input = '';
                                }
                            }
                        });
                        $('#wfacp_subscription_gifting').val(JSON.stringify(object));
                    }

                    $(document.body).on('updated_checkout', function () {
                        setTimeout(copy_html, 400);
                    });
                    $(document.body).on('keyup change focusout', '.wfacp_mini_cart_items fieldset', function () {
                        copy_html();
                    });
                })(jQuery)

            });

        </script>

		<?php
	}


}
new WFACP_Subscription_gift();
