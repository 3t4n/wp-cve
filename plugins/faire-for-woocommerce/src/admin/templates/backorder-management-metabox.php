<?php
/**
 * Template for Faire order products backorder metabox.
 *
 * @package  Wc_Integration_Faire
 *
 * @var int    $wc_order_id
 * @var string $faire_order_id
 * @var array<array{ wc_item_id: int, faire_item_id: string, product_id: int, parent_id: int, is_variation: bool, name: string, thumbnail: string, link: string, quantity: int, stock: int }> $products
 */

$tomorrow = wp_date( 'Y-m-j\TH:i', strtotime( '+1 day' ) );
?>
<div class="woocommerce_order_backorder_products_wrapper">
	<table cellpadding="0" cellspacing="0" class="woocommerce_order_backorder_products" >
		<thead class="backorder_products__header">
			<tr>
				<th></th>
				<th class="product__name"><?php echo esc_html__( 'Backorder Product', 'faire-for-woocommerce' ); ?></th>
				<th class="product__purchased-qty"><?php echo esc_html__( 'Purchased Qty', 'faire-for-woocommerce' ); ?></th>
				<th class="product__available-qty">
					<span><?php echo esc_html__( 'Available Qty', 'faire-for-woocommerce' ); ?></span>
					<span
						class="woocommerce-help-tip"
						data-tip="<?php echo esc_attr__( 'Product available quantity should be equal or less than the purchased quantity.', 'faire-for-woocommerce' ); ?>"
					></span>
				</th>
				<th class="product__backorder-date">
					<span><?php echo esc_html__( 'Backorder Date', 'faire-for-woocommerce' ); ?></span>
					<span
						class="woocommerce-help-tip"
						data-tip="<?php echo esc_attr__( 'Date when the item will be back in stock. Default: tomorrow', 'faire-for-woocommerce' ); ?>"
					></span>
				</th>
			</tr>
		</thead>
		<tbody class="backorder_products__body">
			<?php foreach ( $products as $product ) : ?>
			<tr
				class="faire_backorder_product_data"
				data-wc_item_id="<?php echo esc_attr( $product['wc_item_id'] ); ?>"
				data-faire_item_id="<?php echo esc_attr( $product['faire_item_id'] ); ?>"
				data-product_id="<?php echo esc_attr( $product['product_id'] ); ?>"
				data-product_stock="<?php echo esc_attr( $product['stock'] ); ?>"
			>
				<td class="product__thumbnail">
					<div class="thumbnail__wrapper"><?php echo wp_kses_post( $product['thumbnail'] ); ?></td></div>
				<td class="product__name"><a href="<?php echo esc_attr( $product['link'] ); ?>"><?php echo esc_html( $product['name'] ); ?></a></td>
				<td class="product__purchased-qty"><?php echo esc_html( $product['quantity'] ); ?></td>
				<td class="product__backorder-qty">
					<?php
						echo wp_kses_post(
							woocommerce_form_field(
								'backorder_products_qty',
								array(
									'id'                => 'backorder_products_qty',
									'type'              => 'number',
									'custom_attributes' => array(
										'min'  => '0',
										'max'  => $product['quantity'],
										'step' => '1',
									),
								),
								'0'
							)
						);
					?>
				</td>
				<td class="product__backorder-date">
					<?php
						echo wp_kses_post(
							woocommerce_form_field(
								'backorder-date',
								array(
									'id'                => 'backorder_products_date',
									'type'              => 'datetime-local',
									'min'               => (string) $tomorrow,
									'custom_attributes' => array(
										'data-tomorrow_date' => (string) $tomorrow,
									),
								),
								(string) $tomorrow
							)
						);
					?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<div class="backorder_products__actions">
		<button
			id="btn_faire_backorder_products"
			class="button button-primary"
			data-wc_order_id="<?php echo esc_attr( (string) $wc_order_id ); ?>"
			data-faire_order_id="<?php echo esc_attr( (string) $faire_order_id ); ?>"
			data-tomorrow_date="<?php echo esc_attr( (string) $tomorrow ); ?>"
		>
			<?php echo esc_html__( 'Backorder Products', 'faire-for-woocommerce' ); ?>
		</button>
		<p id="faire_backorder_products_msg"></p>
	</div>
</div>
<?php
