<?php
/**
 * Displays invoice items.
 *
 * This template can be overridden by copying it to yourtheme/eaccounting/invoice/invoice-items.php.
 *
 * @var $invoice Invoice
 * @version 1.1.0
 * @package EverAccounting
 */

use EverAccounting\Models\Invoice;

defined( 'ABSPATH' ) || exit;
$items          = $invoice->get_items();
$item_label     = eaccounting()->settings->get( 'invoice_item_label', __( 'Item', 'wp-ever-accounting' ) );
$price_label    = eaccounting()->settings->get( 'invoice_price_label', __( 'Unit Price', 'wp-ever-accounting' ) );
$quantity_label = eaccounting()->settings->get( 'invoice_quantity_label', __( 'Quantity', 'wp-ever-accounting' ) );
?>
<div class="ea-document__items-wrapper">
	<div class="ea-document__items-top">
		<table cellpadding="0" cellspacing="0" class="ea-document__items">
			<thead>
			<tr>
				<th class="ea-document__line-name" colspan="2"><?php echo esc_html( $item_label ); ?></th>
				<?php do_action( 'eaccounting_invoice_items_headers', $invoice ); ?>
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
				do_action( 'eaccounting_before_invoice_item_html', $item_id, $item, $invoice );

				eaccounting_get_template(
					'invoice/invoice-item.php',
					array(
						'invoice' => $invoice,
						'item_id' => $item_id,
						'item'    => $item,
					)
				);

				do_action( 'eaccounting_invoice_item_html', $item_id, $item, $invoice );
			}
			do_action( 'eaccounting_invoice_items_after_line_items', $invoice );
			?>
			</tbody>

		</table>
	</div>

	<?php eaccounting_get_template( 'invoice/invoice-totals.php', array( 'invoice' => $invoice ) ); ?>

</div>
