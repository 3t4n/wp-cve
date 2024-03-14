<?php

#[AllowDynamicProperties] 

  class WFACP_WooCommerce_Product_bundles {

	private $process = false;

	public function __construct() {
		add_filter( 'wfacp_show_item_quantity', [ $this, 'do_not_display' ], 10, 2 );
		add_filter( 'wfacp_show_you_save_text', [ $this, 'do_not_display' ], 10, 2 );
		add_filter( 'wfacp_enable_delete_item', [ $this, 'do_not_display' ], 10, 2 );
		add_filter( 'wfacp_mini_cart_enable_delete_item', [ $this, 'do_not_display' ], 10, 2 );
		add_filter( 'wfacp_display_quantity_increment', [ $this, 'do_not_display' ], 10, 2 );
		add_filter( 'wfacp_show_item_price', [ $this, 'do_not_display_main_product_price' ], 10, 2 );
		add_filter( 'wfacp_show_undo_message_for_item', [ $this, 'do_not_undo' ], 10, 2 );
		add_filter( 'wfacp_exclude_product_cart_count', [ $this, 'do_not_undo' ], 10, 2 );
		add_filter( 'wfacp_show_item_price_placeholder', [ $this, 'display_cart_item_price' ], 10, 3 );
		add_filter( 'wfacp_show_item_quantity_placeholder', [ $this, 'display_item_quantity' ], 10, 3 );

		add_action( 'wfacp_internal_css', [ $this, 'js' ] );
		add_action( 'woocommerce_checkout_update_order_review', [ $this, 'get_data' ], 5 );
		add_filter( 'woocommerce_update_order_review_fragments', [ $this, 'unset_fragments' ], 900 );

		add_action( 'wfacp_template_load', [ $this, 'action' ] );

	}

	public function action() {
		if ( ! class_exists( 'WC_PB_Display' ) ) {
			return;
		}
		WFACP_Common::remove_actions( 'woocommerce_cart_item_subtotal', 'WC_PB_Display', 'cart_item_subtotal' );
	}

	public function do_not_display( $status, $cart_item ) {

		if ( isset( $cart_item['bundled_by'] ) ) {
			$status = false;
		}

		return $status;
	}

	public function do_not_undo( $status, $cart_item ) {
		if ( isset( $cart_item['bundled_by'] ) ) {
			$status = true;
		}

		return $status;
	}


	public function do_not_display_main_product_price( $status, $cart_item ) {
		if ( is_array( $cart_item ) && isset( $cart_item['data'] ) && $cart_item['data'] instanceof WC_Product ) {
			if ( 'bundle' == $cart_item['data']->get_type() ) {

				$status = false;
			}
		}

		return $status;
	}

	public function display_cart_item_price( $_product, $cart_item, $cart_item_key ) {
		echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.

	}

	public function display_item_quantity( $cart_item ) {

		if ( isset( $cart_item['bundled_by'] ) ) {
			?>
            <span><?php echo $cart_item['quantity']; ?></span>
			<?php
		}
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

			if ( false !== strpos( $k, 'wfacp' ) && true == apply_filters( 'wfacp_unset_our_fragments_by_' . __CLASS__, true, $k ) ) {
				unset( $fragments[ $k ] );
			}
		}
		unset( $fragments['cart_total'] );

		return $fragments;
	}

	public function js() {
		?>
        <style>
            #wfacp-e-form .shop_table.wfacp-product-switch-panel .woocommerce-cart-form__cart-item.cart_item.wfacp_product_row.wfacp-selected-product.bundled_table_item {
                margin-left: 20px;
                width: auto;
            }

            tr.cart_item.bundled_table_item td.product-name-area {
                padding-left: 20px;
            }
        </style>
        <script>
            window.addEventListener('load', function () {
                (function ($) {
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

new WFACP_WooCommerce_Product_bundles();
