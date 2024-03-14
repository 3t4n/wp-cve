<?php
/**
 * https://docs.woocommerce.com/document/chained-products/
 * #[AllowDynamicProperties] 

  class WFACP_WooCommerce_Chained_Products
 */

#[AllowDynamicProperties] 

  class WFACP_WooCommerce_Chained_Products {

	public function __construct() {
		add_filter( 'wfacp_show_item_quantity', [ $this, 'do_not_display' ], 10, 2 );
		add_filter( 'wfacp_show_you_save_text', [ $this, 'do_not_display' ], 10, 2 );
		add_filter( 'wfacp_mini_cart_enable_delete_item', [ $this, 'do_not_display' ], 10, 2 );
		add_filter( 'wfacp_display_quantity_increment', [ $this, 'do_not_display' ], 10, 2 );

		add_filter( 'wfacp_show_undo_message_for_item', [ $this, 'do_not_undo' ], 10, 2 );
		add_filter( 'wfacp_exclude_product_cart_count', [ $this, 'do_not_undo' ], 10, 2 );
		add_filter( 'wfacp_show_item_quantity_placeholder', [ $this, 'display_item_quantity' ], 10, 3 );

	}

	public function do_not_display( $status, $cart_item ) {

		if ( isset( $cart_item['chained_item_of'] ) ) {
			$status = false;
		}

		return $status;
	}

	public function do_not_undo( $status, $cart_item ) {
		if ( isset( $cart_item['chained_item_of'] ) ) {
			$status = true;
		}

		return $status;
	}


	public function display_item_quantity( $cart_item ) {
		if ( isset( $cart_item['chained_item_of'] ) ) {
			?>
            <span><?php echo $cart_item['quantity']; ?></span>
			<?php
		}
	}


}

new WFACP_WooCommerce_Chained_Products();
