<?php
	defined( 'ABSPATH' ) || exit;
	use platy\etsy\logs\PlatySyncerLogger;
	use platy\etsy\EtsySyncerException;
	use platy\etsy\orders\EtsyOrdersSyncer;
	use platy\etsy\orders\EtsyOrderItem;
	global $wpdb;

	$line_items = array_map(function($item) {
			return new EtsyOrderItem($item);
		}, $order->get_items( 'line_item' ));

	$line_items_shipping = array_map(function($item) {
		return new EtsyOrderItem($item);
		}, $order->get_items( 'shipping' ));

	$syncer = new EtsyOrdersSyncer();
?>
<div class="woocommerce_order_items_wrapper">
	<table cellpadding="0" cellspacing="0" class="woocommerce_order_items">
		<thead>
			<tr>
				<th class="item sortable" colspan="2" data-sort="string-ins"><?php esc_html_e( 'Item', 'woocommerce' ); ?></th>
				<th class="item_cost sortable" data-sort="float"><?php esc_html_e( 'Cost', 'woocommerce' ); ?></th>
				<th class="quantity sortable" data-sort="int"><?php esc_html_e( 'Qty', 'woocommerce' ); ?></th>
				<th class="line_cost sortable" data-sort="float"><?php esc_html_e( 'Total', 'woocommerce' ); ?></th>
                <th class="links"><?php esc_html_e( 'Links', 'woocommerce' ); ?></th>
			</tr>
		</thead>
		<tbody id="order_line_items">
			<?php
			foreach ( $line_items as $item_id => $item ) {
				include __DIR__ . '/html-order-item.php';
			}
			?>
		</tbody>
		<tbody id="order_shipping_line_items">
			<?php
			foreach ( $line_items_shipping as $item_id => $item ) {
				if ( $item->get_name() == 'Shipping' ) {
					// this is the default shipping line item
					// it has no information
					continue;
				}
				include __DIR__ . '/html-order-shipping.php';
			}
			?>
		</tbody>
	</table>
</div>
<div class="wc-order-data-row wc-order-totals-items wc-order-items-editable">
	<table class="wc-order-totals">
			<tr>
				<td class="label"><?php esc_html_e( 'Items Subtotal:', 'woocommerce' ); ?></td>
				<td width="1%"></td>
				<td class="total">
					<?php echo wc_price( $order->get_subtotal(), array( 'currency' => $order->get_currency() ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</td>
			</tr>
			<?php if($order->get_discount_total()) { ?>
				<tr>
					<td class="label"><?php esc_html_e( 'Items Discount:', 'woocommerce' ); ?></td>
					<td width="1%"></td>
					<td class="total">
						<?php echo "-" . wc_price( $order->get_discount_total(), array( 'currency' => $order->get_currency() ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</td>
				</tr>
			<?php } ?>
			<tr>
				<td class="label"><?php esc_html_e( 'Shipping:', 'woocommerce' ); ?></td>
				<td width="1%"></td>
				<td class="total">
					<?php echo wc_price( $order->get_shipping_total(), array( 'currency' => $order->get_currency() ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</td>
			</tr>
			<?php foreach ( $order->get_tax_totals() as $code => $tax_total ) : ?>
				<tr>
					<td class="label"><?php echo esc_html( $tax_total->label ); ?>:</td>
					<td width="1%"></td>
					<td class="total">
						<?php echo wc_price( $tax_total->amount, array( 'currency' => $order->get_currency() ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</td>
				</tr>
			<?php endforeach; ?>
		<tr>
			<td class="label"><?php esc_html_e( 'Order Total', 'woocommerce' ); ?>:</td>
			<td width="1%"></td>
			<td class="total">
				<?php echo wc_price( $order->get_total(), array( 'currency' => $order->get_currency() ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</td>
		</tr>
		<?php
			if($order->get_total_refunded() > 0){
		?>
		<tr>
			<td class="label refunded-total"><?php esc_html_e( 'Refunded', 'woocommerce' ); ?>:</td>
			<td width="1%"></td>
			<td class="total refunded-total">-<?php echo wc_price( $order->get_total_refunded(), array( 'currency' => $order->get_currency() ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
		</tr>

		<?php do_action( 'woocommerce_admin_order_totals_after_refunded', $order->get_id() ); ?>

		<tr>
			<td class="label label-highlight"><?php esc_html_e( 'Net Payment', 'woocommerce' ); ?>:</td>
			<td width="1%"></td>
			<td class="total">
			<?php echo wc_price( $order->get_total() - $order->get_total_refunded(), array( 'currency' => $order->get_currency() ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</td>
		</tr>
		<?php
			}
		?>
		<tr>
			<td class="label" ></td>
			<td width="1%"></td>
			<td class="total">
				<?php
					try{
						$shop_id = $syncer->get_shop_id();
						$etsy_item = PlatySyncerLogger::get_instance()->get_etsy_item_data($order->get_id(), $shop_id);
						$etsy_order_id = $etsy_item['etsy_id'];
						$etsy_link = "https://www.etsy.com/your/orders/sold/new?order_id=$etsy_order_id";
						
						include PLATY_SYNCER_ETSY_DIR_PATH . "admin/views/platy-syncer-etsy-logo.php";
					}catch(EtsySyncerException $e){

					}
					
				?>
			</td>
		</tr>
		
	</table>

	<div class="clear"></div>
	<?php if ( in_array( $order->get_status(), array( 'processing', 'completed', 'refunded' ), true ) && ! empty( $order->get_date_paid() ) ) : ?>

	<table class="wc-order-totals" style="border-top: 1px solid #999; margin-top:12px; padding-top:12px">
		<tr>
			<td class="<?php echo $order->get_total_refunded() ? 'label' : 'label label-highlight'; ?>"><?php esc_html_e( 'Paid', 'woocommerce' ); ?>: <br /></td>
			<td width="1%"></td>
			<td class="total">
				<?php echo wc_price( $order->get_total(), array( 'currency' => $order->get_currency() ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</td>
		</tr>
		<tr>
			<td>
				<span class="description">
				<?php
				if ( $order->get_payment_method_title() ) {
					/* translators: 1: payment date. 2: payment method */
					echo esc_html( sprintf( __( '%1$s via %2$s', 'woocommerce' ), $order->get_date_paid()->date_i18n( get_option( 'date_format' ) ), $order->get_payment_method_title() ) );
				} else {
					echo esc_html( $order->get_date_paid()->date_i18n( get_option( 'date_format' ) ) );
				}
				?>
				</span>
			</td>
			<td colspan="2"></td>
		</tr>
	</table>
	<div class="clear"></div>

	<?php endif; ?>

</div>