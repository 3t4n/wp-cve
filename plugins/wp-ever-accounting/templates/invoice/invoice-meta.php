<?php
/**
 * Displays invoice meta.
 *
 * This template can be overridden by copying it to yourtheme/eaccounting/invoice/invoice-meta.php.
 *
 * @var $invoice Invoice
 * @version 1.1.0
 * @package EverAccounting
 */

use EverAccounting\Models\Invoice;

defined( 'ABSPATH' ) || exit;
?>
<table class="ea-document__meta">
	<?php if ( $invoice->get_order_number() ) : ?>
		<tr>
			<th><?php esc_html_e( 'Order Number', 'wp-ever-accounting' ); ?></th>
			<td>:</td>
			<td><?php echo esc_html( $invoice->get_order_number( 'view' ) ); ?></td>
		</tr>
	<?php endif; ?>

	<tr>
		<th><?php esc_html_e( 'Issue Date', 'wp-ever-accounting' ); ?></th>
		<td>:</td>
		<td><?php echo empty( $invoice->get_issue_date() ) ? '&mdash;' : esc_html( eaccounting_date( $invoice->get_issue_date(), 'M j, Y' ) ); ?></td>
	</tr>
	<tr>
		<th><?php esc_html_e( 'Due Date', 'wp-ever-accounting' ); ?></th>
		<td>:</td>
		<td><?php echo empty( $invoice->get_due_date() ) ? '&mdash;' : esc_html( eaccounting_date( $invoice->get_due_date(), 'M j, Y' ) ); ?></td>
	</tr>
	<tr>
		<th><?php esc_html_e( 'Payment Date', 'wp-ever-accounting' ); ?></th>
		<td>:</td>
		<td><?php echo empty( $invoice->get_payment_date() ) ? '&mdash;' : esc_html( eaccounting_date( $invoice->get_payment_date(), 'M j, Y' ) ); ?></td>
	</tr>
</table>
