<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<tr class="shipping <?php echo ( ! empty( $class ) ) ? esc_attr( $class ) : ''; ?>" data-order_item_id="<?php echo esc_attr( $item_id ); ?>">
	<td class="thumb"><div></div></td>

	<td class="name">
		<div class="view">
			<b><?php echo esc_html( $item->get_name() ? $item->get_name() : __( 'Shipping', 'woocommerce' ) ); ?></b>
		</div>
	</td>

	<td class="item_cost" width="1%">&nbsp;</td>
	<td class="quantity" width="1%">&nbsp;</td>

	<td class="line_cost" width="1%">
		<div class="edit" style="display: none;">
			<input type="text" name="shipping_cost[<?php echo esc_attr( $item_id ); ?>]" placeholder="<?php echo esc_attr( wc_format_localized_price( 0 ) ); ?>" value="<?php echo esc_attr( wc_format_localized_price( $item->get_total() ) ); ?>" class="line_total wc_input_price" />
		</div>
	</td>
</tr>
