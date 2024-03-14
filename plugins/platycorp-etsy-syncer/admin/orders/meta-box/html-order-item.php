<?php
/**
 * Shows an order item
 *
 * @package WooCommerce\Admin
 * @var object $item The item being displayed
 * @var int $item_id The id of the item being displayed
 */

defined( 'ABSPATH' ) || exit;

$product_link = $item->get_etsy_link();
$etsy_id = wc_get_order_item_meta($item->get_id(), "_etsy_listing_id", true);
$woo_link = $item->get_woo_link();
$thumbnail    = $item->get_thumbnail();
$row_class    = "";
$items_props  = $item->get_item_variation_props();
$item_shipping_method = wc_get_order_item_meta($item->get_id(), "_etsy_shipping_method", true);
$item_shipping_upgrade = wc_get_order_item_meta($item->get_id(), "_etsy_shipping_upgrade", true);
$etsy_sku = wc_get_order_item_meta($item->get_id(), "_etsy_sku", true);

?>
<tr class="item <?php echo esc_attr( $row_class ); ?>" data-order_item_id="<?php echo esc_attr( $item_id ); ?>">
	<td class="thumb">
		<?php echo 
            '<div class="wc-order-item-thumbnail">' .
            "<img width='150' height='150' src='$thumbnail' class='attachment-thumbnail size-thumbnail' loading='lazy'>"
            . '</div>'; ?>
	</td>
	<td class="name" data-sort-value="<?php echo esc_attr( $item->get_name() ); ?>">
		<?php
		echo '<div class="wc-order-item-name">' .  $item->get_name()  . '</div>';
		if(!empty($etsy_sku)) {
			echo "<p>";
			echo "SKU: $etsy_sku";
			echo "</p>";
		}
		foreach($items_props as $prop_name => $prop_value){
			echo "<p>";
			echo "$prop_name: $prop_value";
			echo "</p>";
		}
		if(!empty($item_shipping_method)) {
			echo "<p>";
			echo "Shipping method: $item_shipping_method";
			echo "</p>";
		}
		if(!empty($item_shipping_upgrade)) {
			echo "<p>";
			echo "Shipping upgrade: $item_shipping_upgrade";
			echo "</p>";
		}
		?>
		<input type="hidden" class="order_item_id" name="order_item_id[]" value="<?php echo esc_attr( $item_id ); ?>" />
		<input type="hidden" name="order_item_tax_class[<?php echo absint( $item_id ); ?>]" value="<?php echo esc_attr( $item->get_tax_class() ); ?>" />
	</td>


	<td class="item_cost" width="1%" data-sort-value="<?php echo esc_attr( $order->get_item_subtotal( $item, false, true ) ); ?>">
		<div class="view">
			<?php
			echo wc_price( $order->get_item_subtotal( $item, false, true ), array( 'currency' => $order->get_currency() ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			?>
		</div>
	</td>
	<td class="quantity" width="1%">
		<div class="view">
			<?php
			echo '<small class="times">&times;</small> ' . esc_html( $item->get_quantity() );

			$refunded_qty = $order->get_qty_refunded_for_item( $item_id );

			if ( $refunded_qty ) {
				echo '<small class="refunded">-' . esc_html( $refunded_qty * -1 ) . '</small>';
			}
			?>
		</div>
	</td>
	<td class="line_cost" width="1%" data-sort-value="<?php echo esc_attr( $item->get_total() ); ?>">
		<div class="view">
			<?php
			echo wc_price( $item->get_total(), array( 'currency' => $order->get_currency() ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

			if ( $item->get_subtotal() !== $item->get_total() ) {
				/* translators: %s: discount amount */
				echo '<span class="wc-order-item-discount">' . sprintf( esc_html__( '%s discount', 'woocommerce' ), wc_price( wc_format_decimal( $item->get_subtotal() - $item->get_total(), '' ), array( 'currency' => $order->get_currency() ) ) ) . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}

			$refunded = $order->get_total_refunded_for_item( $item_id );

			if ( $refunded ) {
				echo '<small class="refunded">-' . wc_price( $refunded, array( 'currency' => $order->get_currency() ) ) . '</small>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
			?>
		</div>
		<td class="links" width="15%">
			<div class="view">
				<?php
					if($product_link) {
						$etsy_link = $product_link;
						$etsy_item = [
							'status' => true,
							'etsy_id' => $etsy_id
						];
						include PLATY_SYNCER_ETSY_DIR_PATH . "admin/views/platy-syncer-etsy-logo.php";
					}

					if($woo_link) {
						include PLATY_SYNCER_ETSY_DIR_PATH . "admin/views/platy-syncer-woo-logo.php";
					}
				?>
			</div>
		</td>
		<div class="edit" style="display: none;">
			<div class="split-input">
				<div class="input">
					<label><?php esc_attr_e( 'Before discount', 'woocommerce' ); ?></label>
					<input type="text" name="line_subtotal[<?php echo absint( $item_id ); ?>]" placeholder="<?php echo esc_attr( wc_format_localized_price( 0 ) ); ?>" value="<?php echo esc_attr( wc_format_localized_price( $item->get_subtotal() ) ); ?>" class="line_subtotal wc_input_price" data-subtotal="<?php echo esc_attr( wc_format_localized_price( $item->get_subtotal() ) ); ?>" />
				</div>
				<div class="input">
					<label><?php esc_attr_e( 'Total', 'woocommerce' ); ?></label>
					<input type="text" name="line_total[<?php echo absint( $item_id ); ?>]" placeholder="<?php echo esc_attr( wc_format_localized_price( 0 ) ); ?>" value="<?php echo esc_attr( wc_format_localized_price( $item->get_total() ) ); ?>" class="line_total wc_input_price" data-tip="<?php esc_attr_e( 'After pre-tax discounts.', 'woocommerce' ); ?>" data-total="<?php echo esc_attr( wc_format_localized_price( $item->get_total() ) ); ?>" />
				</div>
			</div>
		</div>
		<div class="refund" style="display: none;">
			<input type="text" name="refund_line_total[<?php echo absint( $item_id ); ?>]" placeholder="<?php echo esc_attr( wc_format_localized_price( 0 ) ); ?>" class="refund_line_total wc_input_price" />
		</div>
	</td>
</tr>