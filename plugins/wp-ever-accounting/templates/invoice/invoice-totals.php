<?php
/**
 * Displays invoice totals.
 *
 * This template can be overridden by copying it to yourtheme/eaccounting/invoice/invoice-totals.php.
 *
 * @var $invoice Invoice
 * @version 1.1.0
 * @package EverAccounting
 */

use EverAccounting\Models\Invoice;

defined( 'ABSPATH' ) || exit;
?>
<div class="ea-document__data-row ea-invoice__totals">
	<table class="ea-document__total-items">
		<tr>
			<td class="label"><?php esc_html_e( 'Items Subtotal:', 'wp-ever-accounting' ); ?></td>
			<td width="1%"></td>
			<td class="total">
				<?php echo wp_kses_post( eaccounting_price( $invoice->get_subtotal(), $invoice->get_currency_code() ) ); ?>
			</td>
		</tr>

		<tr>
			<td class="label"><?php esc_html_e( 'Discount:', 'wp-ever-accounting' ); ?></td>
			<td width="1%"></td>
			<td class="total">-
				<?php echo wp_kses_post( eaccounting_price( $invoice->get_total_discount(), $invoice->get_currency_code() ) ); ?>
			</td>
		</tr>

		<?php if ( ! empty( $invoice->get_total_fees() ) ) : ?>
			<tr>
				<td class="label"><?php esc_html_e( 'Fees:', 'wp-ever-accounting' ); ?></td>
				<td width="1%"></td>
				<td class="total">
					<?php echo wp_kses_post( eaccounting_price( $invoice->get_total_fees(), $invoice->get_currency_code() ) ); ?>
				</td>
			</tr>
		<?php endif; ?>

		<?php if ( ! empty( $invoice->get_total_shipping() ) ) : ?>
			<tr>
				<td class="label"><?php esc_html_e( 'Shipping:', 'wp-ever-accounting' ); ?></td>
				<td width="1%"></td>
				<td class="total">
					<?php echo wp_kses_post( eaccounting_price( $invoice->get_total_shipping(), $invoice->get_currency_code() ) ); ?>
				</td>
			</tr>
		<?php endif; ?>

		<?php if ( eaccounting_tax_enabled() ) : ?>
			<?php if ( 'total' === eaccounting()->settings->get( 'tax_display_totals', 'total' ) ) : ?>
				<tr>
					<td class="label"><?php esc_html_e( 'Tax', 'wp-ever-accounting' ); ?>:</td>
					<td width="1%"></td>
					<td class="total">
						<?php echo wp_kses_post( eaccounting_price( $invoice->get_total_tax(), $invoice->get_currency_code() ) ); ?>
					</td>
				</tr>
			<?php else : ?>
				<?php foreach ( $invoice->get_taxes()  as $tax ) : ?>
					<tr>
						<td class="label"><?php echo esc_html( __( 'Tax', 'wp-ever-accounting' ) . '(' . number_format_i18n( $tax['rate'] ) . '%)' ); ?>:</td>
						<td width="1%"></td>
						<td class="total">
							<?php echo wp_kses_post( eaccounting_price( $tax['amount'], $invoice->get_currency_code() ) ); ?>
						</td>
					</tr>
				<?php endforeach; ?>
			<?php endif; ?>
		<?php endif; ?>
		<tr>
			<td class="label"><?php esc_html_e( 'Total', 'wp-ever-accounting' ); ?>:</td>
			<td width="1%"></td>
			<td class="total">
				<?php echo wp_kses_post( eaccounting_price( $invoice->get_total(), $invoice->get_currency_code() ) ); ?>
			</td>
		</tr>
		<tr>
			<td class="label"><?php esc_html_e( 'Paid', 'wp-ever-accounting' ); ?>:</td>
			<td width="1%"></td>
			<td class="total">
				<?php echo wp_kses_post( eaccounting_price( $invoice->get_total_paid(), $invoice->get_currency_code() ) ); ?>
			</td>
		</tr>
		<?php if ( $invoice->exists() && ! empty( $invoice->get_total_due() ) ) : ?>
			<tr class="ea-document__due">
				<td class="label"><?php esc_html_e( 'Due', 'wp-ever-accounting' ); ?>:</td>
				<td width="1%"></td>
				<td class="total">
					<?php echo wp_kses_post( eaccounting_price( $invoice->get_total_due(), $invoice->get_currency_code() ) ); ?>
				</td>
			</tr>
		<?php endif; ?>

	</table>

</div>
