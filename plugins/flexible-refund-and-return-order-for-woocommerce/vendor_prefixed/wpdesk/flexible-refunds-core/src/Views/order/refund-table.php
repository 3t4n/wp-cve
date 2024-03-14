<?php

namespace FRFreeVendor;

use FRFreeVendor\WPDesk\Library\FlexibleRefundsCore\FormRenderer\FieldRenderer;
\defined('ABSPATH') || exit;
/**
 * @var WC_Order      $order
 * @var FieldRenderer $fields
 * @var string        $show_shipping
 */
if (!$order) {
    return;
}
$order_items = $order->get_items();
$refund_meta = $order->get_meta('fr_refund_request_data');
$show_shipping = $settings->get_fallback('refund_enable_shipment', 'no');
?>
<h2><?php 
\esc_html_e('Products', 'flexible-refund-and-return-order-for-woocommerce');
?></h2>
<p class="description"><?php 
\esc_html_e('Select your return status. Confirming the selection will send a message to the customer.', 'flexible-refund-and-return-order-for-woocommerce');
?></p>

<table class="fr-refund-table">
	<thead>
	<tr>
		<th class="product-name"><?php 
\esc_html_e('Product', 'flexible-refund-and-return-order-for-woocommerce');
?></th>
		<th class="item-cosst"><?php 
\esc_html_e('Cost', 'flexible-refund-and-return-order-for-woocommerce');
?></th>
		<th class="item-total"><?php 
\esc_html_e('Total', 'flexible-refund-and-return-order-for-woocommerce');
?></th>
		<th class="item-qty"><?php 
\esc_html_e('Quantity to refund', 'flexible-refund-and-return-order-for-woocommerce');
?></th>
		<th class="item-total"><?php 
\esc_html_e('Requested refund Total', 'flexible-refund-and-return-order-for-woocommerce');
?></th>
	</tr>
	</thead>
	<tbody>
	<?php 
$total_refund_sum = 0.0;
foreach ($order_items as $item_id => $item) {
    /**
     * @var WC_Order_Item_Product $item
     */
    $product = $item->get_product();
    $qty = $item->get_quantity();
    $refunded_qty = \abs($order->get_qty_refunded_for_item($item_id));
    $qty_display = $qty;
    if ($refunded_qty) {
        $qty = $qty - $refunded_qty;
        $qty_display = $qty;
    }
    $qty_to_refund = $refund_meta['items'][$item_id]['qty'] ?? 0;
    $qty_to_refund = $qty_to_refund > $qty_display ? $qty_display : $qty_to_refund;
    $item_price = ($item->get_total() + $item->get_total_tax()) / $item->get_quantity();
    $item_price_refund = ($item->get_total() + $item->get_total_tax()) / $item->get_quantity() * (int) $refunded_qty;
    $item_total = $item->get_total() + $item->get_total_tax();
    if ($qty > 0) {
        $total_refund_sum += $item_price_refund;
    }
    ?>
		<tr class="product_item">
			<td class="item-name">
				<?php 
    echo \esc_html($item->get_name());
    ?>
			</td>
			<td class="item-cost">
				<?php 
    echo \wc_price($order->get_item_total($item, \true), ['currency' => $order->get_currency()]);
    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    ?>
			</td>
			<td class="item-total">
				<?php 
    echo \wc_price($item_total, ['currency' => $order->get_currency()]);
    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    ?>
			</td>
			<td class="item-qty" style="width:160px;">
				<?php 
    if ($qty <= 0) {
        ?>
					<span class="refunded-icon" style="vertical-align: bottom;">
						<svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512" height="12px" width="12px" xmlns="http://www.w3.org/2000/svg">
							<path
								d="M212.333 224.333H12c-6.627 0-12-5.373-12-12V12C0 5.373 5.373 0 12 0h48c6.627 0 12 5.373 12 12v78.112C117.773 39.279 184.26 7.47 258.175 8.007c136.906.994 246.448 111.623 246.157 248.532C504.041 393.258 393.12 504 256.333 504c-64.089 0-122.496-24.313-166.51-64.215-5.099-4.622-5.334-12.554-.467-17.42l33.967-33.967c4.474-4.474 11.662-4.717 16.401-.525C170.76 415.336 211.58 432 256.333 432c97.268 0 176-78.716 176-176 0-97.267-78.716-176-176-176-58.496 0-110.28 28.476-142.274 72.333h98.274c6.627 0 12 5.373 12 12v48c0 6.627-5.373 12-12 12z"></path>
						</svg>
					</span>
					<?php 
        \esc_html_e('Refunded', 'flexible-refund-and-return-order-for-woocommerce');
        ?>
				<?php 
    } else {
        ?>
					<label>
						<input
							class="qty-input"
							step="1"
							type="number"
							min="0"
							max="<?php 
        echo \esc_attr($qty);
        ?>"
							value="<?php 
        echo \esc_attr($qty_to_refund);
        ?>"
							name="fr_refund_form[items][<?php 
        echo \esc_attr($item_id);
        ?>][qty]"
							data-item-price="<?php 
        echo \esc_attr($item_price);
        ?>"
						/>
					</label>
					<span class="product-quantity"><?php 
        echo \sprintf('&times;&nbsp;%s', $qty_display);
        ?></span>
				<?php 
    }
    ?>
			</td>
			<td class="item-refund-total">
				<?php 
    if ($qty <= 0) {
        echo '-';
    } else {
        echo \number_format($item_price_refund, \wc_get_price_decimals(), ',', '');
    }
    ?>
			</td>
		</tr>
	<?php 
}
?>
	<?php 
$shipping_items = $order->get_items('shipping');
foreach ($shipping_items as $shipping_item) {
    if ($show_shipping === 'yes') {
        /**
         * @var \WC_Order_Item_Shipping $shipping_item
         */
        $shipping_total = (float) $shipping_item->get_total() + (float) $shipping_item->get_total_tax();
        $refunded = \false;
        $refunded_shipping_qty = $order->get_qty_refunded_for_item($shipping_item->get_id(), 'shipping');
        if ($refunded_shipping_qty === 1) {
            $refunded = \true;
        }
        if (isset($refund_meta['items'][$shipping_item->get_id()]) && $refunded === \false) {
            $total_refund_sum += $shipping_item->get_total() + $shipping_item->get_total_tax();
        }
        ?>
			<tr class="shipping-item">
				<td><?php 
        echo \sprintf(\esc_html__('Shipping: %s', 'flexible-refund-and-return-order-for-woocommerce'), $shipping_item->get_name());
        ?></td>
				<td><?php 
        echo \wc_price((float) $shipping_item->get_total() + (float) $shipping_item->get_total_tax(), ['currency' => $order->get_currency()]);
        ?></td>
				<td><?php 
        echo \wc_price((float) $shipping_item->get_total() + (float) $shipping_item->get_total_tax(), ['currency' => $order->get_currency()]);
        ?></td>
				<td class="item-qty">
					<?php 
        if ($refunded) {
            ?>
						<span class="refunded-icon" style="vertical-align: bottom;">
								<svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512" height="12px" width="12px" xmlns="http://www.w3.org/2000/svg">
									<path
										d="M212.333 224.333H12c-6.627 0-12-5.373-12-12V12C0 5.373 5.373 0 12 0h48c6.627 0 12 5.373 12 12v78.112C117.773 39.279 184.26 7.47 258.175 8.007c136.906.994 246.448 111.623 246.157 248.532C504.041 393.258 393.12 504 256.333 504c-64.089 0-122.496-24.313-166.51-64.215-5.099-4.622-5.334-12.554-.467-17.42l33.967-33.967c4.474-4.474 11.662-4.717 16.401-.525C170.76 415.336 211.58 432 256.333 432c97.268 0 176-78.716 176-176 0-97.267-78.716-176-176-176-58.496 0-110.28 28.476-142.274 72.333h98.274c6.627 0 12 5.373 12 12v48c0 6.627-5.373 12-12 12z"></path>
								</svg>
							</span>
						<?php 
            \esc_html_e('Refunded', 'flexible-refund-and-return-order-for-woocommerce');
            ?>
					<?php 
        } else {
            ?>
						<?php 
            if ($shipping_total > 0) {
                ?>
							<label>
								<input
									class="qty-input"
									type="checkbox"
									value="1"
									<?php 
                \checked(isset($refund_meta['items'][$shipping_item->get_id()]), \true);
                ?>
									name="fr_refund_form[items][<?php 
                echo $shipping_item->get_id();
                ?>][qty]"
									data-item-price="<?php 
                echo \esc_attr($shipping_item->get_total() + $shipping_item->get_total_tax());
                ?>"
								/>
							</label>
						<?php 
            }
            ?>
					<?php 
        }
        ?>
				</td>
				<td class="item-total-refund-qty">
				<?php 
        if ($refunded) {
            echo '-';
        } else {
            echo \number_format((float) $shipping_total, \wc_get_price_decimals(), ',', '');
        }
        ?>
				</td>
			</tr>
			<?php 
    }
}
?>
	</tbody>
	<tfoot>
	<tr>
		<td colspan="4"></td>
		<?php 
?>
		<td class="total-refund-amount"><strong><span class="refund-total-calc">
		<?php 
if ($total_refund_sum <= 0) {
    echo '-';
} else {
    echo \number_format((float) $total_refund_sum, \wc_get_price_decimals(), ',', '');
}
?>
		</span></strong>
		</td>
	</tr>
	</tfoot>
</table>
<?php 
