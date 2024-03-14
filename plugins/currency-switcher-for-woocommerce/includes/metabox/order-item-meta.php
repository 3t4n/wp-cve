<?php

class PMCS_Order_Item_Meta {

	protected $keys = array(
		'_line_subtotal_converted' => 'price',
		'_line_subtotal_tax_converted' => 'price',
		'_line_total_converted' => 'price',
		'_line_tax_converted' => 'price',
	);

	public function __construct() {
		// add_filter( 'woocommerce_hidden_order_itemmeta', 'pmcs_order_item_hidden_fields' );
		add_filter( 'woocommerce_order_item_display_meta_key', array( $this, 'custom_label' ), 15, 3 );
		add_filter( 'woocommerce_order_item_display_meta_value', array( $this, 'custom_display_value' ), 15, 3 );

		// add_action( 'woocommerce_after_order_itemmeta', array( $this, 'after_order_item_meta' ), 15, 3 );
		add_action( 'woocommerce_admin_order_totals_after_discount', array( $this, 'order_discount' ), 15 );
		add_action( 'woocommerce_admin_order_totals_after_shipping', array( $this, 'order_shipping' ), 15 );
	}

	public function order_discount( $order_id ) {
		$order = wc_get_order( $order_id );
		if ( 0 >= $order->get_total_discount() ) {
			return false;
		}
		$currency = $order->get_currency();
		$checkout_currency = $order->get_meta( '_currency_checkout' );
		if ( $checkout_currency != $currency ) {
			?>
			<tr>
				<td class="label"><?php printf( __( 'Discount(%1$s):', 'pmcs' ), $checkout_currency ); ?></td>
				<td width="1%"></td>
				<td class="total">
					<?php echo wc_price( $order->get_meta( '_cart_discount_converted' ), array( 'currency' => $checkout_currency ) ); // WPCS: XSS ok. ?>
				</td>
			</tr>
			<?php
		}
	}

	public function order_shipping( $order_id ) {
		$order = wc_get_order( $order_id );
		if ( ! $order->get_shipping_methods() ) {
			return false;
		}
		$currency = $order->get_currency();
		$checkout_currency = $order->get_meta( '_currency_checkout' );
		if ( $checkout_currency != $currency ) {
			?>
			<tr>
				<td class="label"><?php printf( __( 'Shipping(%1$s):', 'pmcs' ), $checkout_currency ); ?></td>
				<td width="1%"></td>
				<td class="total">
					<?php echo wc_price( $order->get_meta( '_cart_discount_converted' ), array( 'currency' => $checkout_currency ) ); // WPCS: XSS ok. ?>
				</td>
			</tr>
			<?php
		}
	}


	public function after_order_item_meta( $item_id, $item, $product ) {
		$type = $item->get_type();
		if ( ! $product ) {

			switch ( $type ) {
				case 'shipping':
					break;
			}
		}
	}



	public function custom_label( $display_key, $meta, $item ) {
		$order = $item->get_order();
		$currency = $order->get_meta( '_currency_checkout' );
		// $rate = $order_meta['_currency_rate'];
		switch ( $display_key ) {
			case '_line_subtotal_converted':
				return sprintf( __( 'Subtotal%1$s', 'pmcs' ), ' (' . $currency . ')' );
			break;
			case '_line_subtotal_tax_converted':
				return sprintf( __( 'Subtotal tax%1$s', 'pmcs' ), ' (' . $currency . ')' );
			break;
			case '_line_tax_converted':
				return sprintf( __( 'Tax%1$s', 'pmcs' ), ' (' . $currency . ')' );
			break;
			case '_line_total_converted':
				return sprintf( __( 'Total%1$s', 'pmcs' ), ' (' . $currency . ')' );
			break;
			case 'cost_converted':
				return sprintf( __( 'Cost%1$s', 'pmcs' ), ' (' . $currency . ')' );
			break;
			case 'shipping_tax_amount_converted':
				return sprintf( __( 'Shipping tax%1$s', 'pmcs' ), ' (' . $currency . ')' );
			break;
		}

		return $display_key;
	}

	public function custom_display_value( $display_value, $meta, $item ) {
		$order = $item->get_order();
		$currency = $order->get_meta( '_currency_checkout' );
		switch ( $meta->key ) {
			case '_line_subtotal_converted':
				return wc_price( $meta->value, array( 'currency' => $currency ) );
			break;
			case '_line_subtotal_tax_converted':
				return wc_price( $meta->value, array( 'currency' => $currency ) );
			break;
			case '_line_tax_converted':
				return wc_price( $meta->value, array( 'currency' => $currency ) );
			break;
			case '_line_total_converted':
				return wc_price( $meta->value, array( 'currency' => $currency ) );
			break;
			case 'cost_converted':
				return wc_price( $meta->value, array( 'currency' => $currency ) );
			break;
			case 'shipping_tax_amount_converted':
				return wc_price( $meta->value, array( 'currency' => $currency ) );
			break;
		}

		return $display_value;
	}

}

new PMCS_Order_Item_Meta();


// function pmcs_order_item_hidden_fields( $fields ) {
// $fields[] = '_line_subtotal_converted';
// $fields[] = '_line_subtotal_tax_converted';
// $fields[] = '_line_total_converted';
// $fields[] = '_line_tax_converted';
// return $fields;
// }

