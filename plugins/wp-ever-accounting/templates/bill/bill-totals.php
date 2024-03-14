<?php
/**
 * Displays bill totals.
 *
 * This template can be overridden by copying it to yourtheme/eaccounting/bill/bill-totals.php.
 *
 * @var $bill Bill
 * @version 1.1.0
 * @package EverAccounting
 */

use EverAccounting\Models\Bill;

defined( 'ABSPATH' ) || exit;
?>
<div class="ea-document__data-row ea-bill__totals">
	<table class="ea-document__total-items">
		<tr>
			<td class="label"><?php esc_html_e( 'Items Subtotal:', 'wp-ever-accounting' ); ?></td>
			<td width="1%"></td>
			<td class="total">
				<?php echo wp_kses_post( eaccounting_price( $bill->get_subtotal(), $bill->get_currency_code() ) ); ?>
			</td>
		</tr>

		<tr>
			<td class="label"><?php esc_html_e( 'Discount:', 'wp-ever-accounting' ); ?></td>
			<td width="1%"></td>
			<td class="total">-
				<?php echo wp_kses_post( eaccounting_price( $bill->get_total_discount(), $bill->get_currency_code() ) ); ?>
			</td>
		</tr>

		<?php if ( eaccounting_tax_enabled() ) : ?>
			<?php if ( 'total' === eaccounting()->settings->get( 'tax_display_totals', 'total' ) ) : ?>
				<tr>
					<td class="label"><?php esc_html_e( 'Tax', 'wp-ever-accounting' ); ?>:</td>
					<td width="1%"></td>
					<td class="total">
						<?php echo wp_kses_post( eaccounting_price( $bill->get_total_tax(), $bill->get_currency_code() ) ); ?>
					</td>
				</tr>
			<?php else : ?>
				<?php foreach ( $bill->get_taxes()  as $tax ) : ?>
					<tr>
						<td class="label"><?php echo esc_html( __( 'Tax', 'wp-ever-accounting' ) . '(' . number_format_i18n( $tax['rate'] ) . '%)' ); ?>:</td>
						<td width="1%"></td>
						<td class="total">
							<?php echo wp_kses_post( eaccounting_price( $tax['amount'], $bill->get_currency_code() ) ); ?>
						</td>
					</tr>
				<?php endforeach; ?>
			<?php endif; ?>
		<?php endif; ?>
		<tr>
			<td class="label"><?php esc_html_e( 'Total', 'wp-ever-accounting' ); ?>:</td>
			<td width="1%"></td>
			<td class="total">
				<?php echo wp_kses_post( eaccounting_price( $bill->get_total(), $bill->get_currency_code() ) ); ?>
			</td>
		</tr>
		<tr>
			<td class="label"><?php esc_html_e( 'Paid', 'wp-ever-accounting' ); ?>:</td>
			<td width="1%"></td>
			<td class="total">
				<?php echo wp_kses_post( eaccounting_price( $bill->get_total_paid(), $bill->get_currency_code() ) ); ?>
			</td>
		</tr>
		<?php if ( $bill->exists() && ! empty( $bill->get_total_due() ) ) : ?>
			<tr class="ea-document__due">
				<td class="label"><?php esc_html_e( 'Due', 'wp-ever-accounting' ); ?>:</td>
				<td width="1%"></td>
				<td class="total">
					<?php echo wp_kses_post( eaccounting_price( abs( $bill->get_total_due() ), $bill->get_currency_code() ) ); ?>
				</td>
			</tr>
		<?php endif; ?>

	</table>

</div>
