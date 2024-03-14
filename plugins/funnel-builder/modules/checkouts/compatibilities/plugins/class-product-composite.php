<?php

#[AllowDynamicProperties] 

  class WFACP_WooCommerce_Product_Composite {

	public function __construct() {
		add_filter( 'wfacp_show_item_quantity', [ $this, 'do_not_display_quantity_increment' ], 10, 2 );
		add_filter( 'wfacp_show_you_save_text', [ $this, 'do_not_display' ], 10, 2 );
		add_filter( 'wfacp_mini_cart_enable_delete_item', [ $this, 'do_not_display' ], 10, 2 );
		add_filter( 'wfacp_display_quantity_increment', [ $this, 'do_not_display_quantity_increment' ], 10, 2 );
		add_filter( 'wfacp_show_item_price', [ $this, 'do_not_display_main_product_price' ], 10, 2 );
		add_filter( 'wfacp_enable_delete_item', [ $this, 'do_not_display' ], 10, 2 );
		add_filter( 'wfacp_show_undo_message_for_item', [ $this, 'do_not_undo' ], 10, 2 );
		add_filter( 'wfacp_exclude_product_cart_count', [ $this, 'do_not_undo' ], 10, 2 );
		add_filter( 'wfacp_show_item_quantity_placeholder', [ $this, 'display_item_quantity' ], 10, 3 );
		add_action( 'wfacp_internal_css', [ $this, 'hide_quantity' ] );

	}

	public function do_not_display( $status, $cart_item ) {
		if ( isset( $cart_item['composite_parent'] ) ) {
			$status = false;
		}

		return $status;
	}

	public function do_not_display_quantity_increment( $status, $cart_item ) {
		if ( isset( $cart_item['composite_parent'] ) ) {
			$status         = false;
			$item_id        = $cart_item['composite_item'];
			$composite_data = $cart_item['composite_data'];
			$quantity       = $cart_item['quantity'];
			if ( isset( $composite_data[ $item_id ] ) ) {
				$quantity_min = $composite_data[ $item_id ]['quantity_min'];
				$quantity_max = $composite_data[ $item_id ]['quantity_max'];
				if ( empty( $quantity_max ) || $quantity_max > $quantity_min ) {

					$status = true;
					if ( $quantity_max > 0 && $quantity > $quantity_max ) {
						$status = false;
					}
				}
			}
		}

		return $status;

	}

	public function do_not_undo( $status, $cart_item ) {
		if ( isset( $cart_item['composite_parent'] ) ) {
			$status = true;
		}

		return $status;
	}


	public function do_not_display_main_product_price( $status, $cart_item ) {
		if ( isset( $cart_item['composite_parent'] ) ) {
			$status = false;
		}

		return $status;
	}


	public function display_item_quantity( $cart_item ) {

		if ( isset( $cart_item['composite_parent'] ) ) {
			?>
            <span><?php echo $cart_item['quantity']; ?></span>
			<?php
		}
	}

	public function hide_quantity() {
		echo "<style>.composited_product_quantity{display:none}</style>";
	}


}
new WFACP_WooCommerce_Product_Composite();
