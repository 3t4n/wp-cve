<?php
/**
 * Displays bill meta.
 *
 * This template can be overridden by copying it to yourtheme/eaccounting/bill/bill-meta.php.
 *
 * @var $bill Bill
 * @version 1.1.0
 * @package EverAccounting
 */

use EverAccounting\Models\Bill;

defined( 'ABSPATH' ) || exit;
?>
<table class="ea-document__meta">
	<?php if ( $bill->get_order_number() ) : ?>
		<tr>
			<th><?php esc_html_e( 'Order Number', 'wp-ever-accounting' ); ?></th>
			<td>:</td>
			<td><?php echo esc_html( $bill->get_order_number( 'view' ) ); ?></td>
		</tr>
	<?php endif; ?>

	<tr>
		<th><?php esc_html_e( 'Issue Date', 'wp-ever-accounting' ); ?></th>
		<td>:</td>
		<td><?php echo empty( $bill->get_issue_date() ) ? '&mdash;' : eaccounting_date( $bill->get_issue_date(), 'M j, Y' ); //phpcs:ignore ?></td>
	</tr>
	<tr>
		<th><?php esc_html_e( 'Due Date', 'wp-ever-accounting' ); ?></th>
		<td>:</td>
		<td><?php echo empty( $bill->get_due_date() ) ? '&mdash;' : eaccounting_date( $bill->get_due_date(), 'M j, Y' ); //phpcs:ignore ?></td>
	</tr>
	<tr>
		<th><?php esc_html_e( 'Payment Date', 'wp-ever-accounting' ); ?></th>
		<td>:</td>
		<td><?php echo empty( $bill->get_payment_date() ) ? '&mdash;' : eaccounting_date( $bill->get_payment_date(), 'M j, Y' ); //phpcs:ignore ?></td>
	</tr>
</table>
