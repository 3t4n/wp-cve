<?php
/**
 * Displays bill items.
 *
 * This template can be overridden by copying it to yourtheme/eaccounting/bill/bill-items.php.
 *
 * @var $bill Bill
 * @version 1.1.0
 * @package EverAccounting
 */

use EverAccounting\Models\Bill;

defined( 'ABSPATH' ) || exit;
$items          = $bill->get_items();
$item_label     = eaccounting()->settings->get( 'bill_item_label', __( 'Item', 'wp-ever-accounting' ) );
$price_label    = eaccounting()->settings->get( 'bill_price_label', __( 'Unit Price', 'wp-ever-accounting' ) );
$quantity_label = eaccounting()->settings->get( 'bill_quantity_label', __( 'Quantity', 'wp-ever-accounting' ) );
?>
<div class="ea-document__items-wrapper">
	<div class="ea-document__items-top">
		<table cellpadding="0" cellspacing="0" class="ea-document__items">
			<thead>
			<tr>
				<th class="ea-document__line-name" colspan="2"><?php echo esc_html( $item_label ); ?></th>
				<?php do_action( 'eaccounting_bill_items_headers', $bill ); ?>
				<th class="ea-document__line-price"><?php echo esc_html( $price_label ); ?></th>
				<th class="ea-document__line-quantity"><?php echo esc_html( $quantity_label ); ?></th>
				<?php if ( eaccounting_tax_enabled() ) : ?>
					<th class="ea-document__line-tax"><?php esc_html_e( 'Tax(%)', 'wp-ever-accounting' ); ?></th>
				<?php endif; ?>
				<th class="ea-document__line-subtotal"><?php esc_html_e( 'Subtotal', 'wp-ever-accounting' ); ?></th>
			</tr>
			</thead>
			<tbody id="ea-document__line-items">
			<?php
			foreach ( $items as $item_id => $item ) {
				do_action( 'eaccounting_before_bill_item_html', $item_id, $item, $bill );

				eaccounting_get_template(
					'bill/bill-item.php',
					array(
						'bill'    => $bill,
						'item_id' => $item_id,
						'item'    => $item,
					)
				);

				do_action( 'eaccounting_bill_item_html', $item_id, $item, $bill );
			}
			do_action( 'eaccounting_bill_items_after_line_items', $bill );
			?>
			</tbody>

		</table>
	</div>
	<?php eaccounting_get_template( 'bill/bill-totals.php', array( 'bill' => $bill ) ); ?>

</div>
