<?php

/**
 * https://woocommerce.com/products/force-sells/
 * #[AllowDynamicProperties] 

  class WFACP_Compatibility_WC_Force_Sell
 */
#[AllowDynamicProperties] 

  class WFACP_Compatibility_WC_Force_Sells {
	public function __construct() {
		add_filter( 'wfacp_show_item_quantity', [ $this, 'do_not_display' ], 10, 2 );
		add_filter( 'wfacp_show_you_save_text', [ $this, 'do_not_display' ], 10, 2 );
		add_filter( 'wfacp_mini_cart_enable_delete_item', [ $this, 'do_not_display' ], 10, 2 );
		add_filter( 'wfacp_display_quantity_increment', [ $this, 'do_not_display' ], 10, 2 );
		add_filter( 'wfacp_enable_delete_item', [ $this, 'do_not_display' ], 10, 2 );
		add_filter( 'wfacp_show_undo_message_for_item', [ $this, 'do_not_undo' ], 10, 2 );
		add_filter( 'wfacp_exclude_product_cart_count', [ $this, 'do_not_undo' ], 10, 2 );
		add_filter( 'wfacp_show_item_quantity_placeholder', [ $this, 'display_item_quantity' ], 10, 3 );
		add_filter( 'wfacp_delete_item_from_order_summary', [ $this, 'do_not_display_order_summary' ], 10, 3 );
	}

	public function do_not_display_order_summary( $allow_delete, $cart_item_key, $cart_item ) {
		return $this->do_not_display( $allow_delete, $cart_item );
	}

	public function do_not_display( $status, $cart_item ) {

		if ( isset( $cart_item['forced_by'] ) ) {
			$status = false;
		}

		return $status;
	}

	public function do_not_undo( $status, $cart_item ) {
		if ( isset( $cart_item['forced_by'] ) ) {
			$status = true;
		}

		return $status;
	}


	public function display_item_quantity( $cart_item ) {
		if ( isset( $cart_item['forced_by'] ) ) {
			?>
            <span><?php echo $cart_item['quantity']; ?></span>
			<?php
		}
	}


	public static function is_enable() {
		return class_exists( 'WC_Force_Sells' );
	}
}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_WC_Force_Sells(), 'wc_force_sell' );

