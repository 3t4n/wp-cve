<?php
/**
 * Shows payments
 * Used in view invoice page.
 *
 * @package EverAccounting\Admin
 * @var Invoice $invoice The item being used
 */

use EverAccounting\Models\Invoice;

defined( 'ABSPATH' ) || exit();

$payments = $invoice->get_payments();
?>
<div class="ea-card__body">
	<?php if ( empty( $payments ) ) : ?>
		<p class="ea-card__inside"><?php esc_html_e( 'There are no payments yet.', 'wp-ever-accounting' ); ?></p>

	<?php else : ?>
		<table class="ea-card__body ea-invoice-payment widefat" style="border: 0;">
			<thead>
			<tr>
				<th><?php esc_html_e( 'Date', 'wp-ever-accounting' ); ?></th>
				<th><?php esc_html_e( 'Payment', 'wp-ever-accounting' ); ?></th>
			</tr>
			</thead>
			<tbody>
			<?php foreach ( $payments as $payment ) : ?>
				<tr>
					<td>
						<a href="
						<?php
						echo esc_url(
							add_query_arg(
								array(
									'page' => 'ea-sales',
									'tab'  => 'invoices',
									's'    => $invoice->get_invoice_number(),
								),
								admin_url( 'admin.php' )
							)
						);
						?>
									">
							<?php echo esc_html( $payment->get_payment_date() ); ?>
						</a>
					</td>
					<td>
						<abbr title="<?php echo esc_attr( eaccounting_price( $payment->get_amount(), $payment->get_currency_code() ) ); ?>">
							<?php echo esc_html( eaccounting_price( eaccounting_price_convert( $payment->get_amount(), $payment->get_currency_code(), $invoice->get_currency_code(), $payment->get_currency_rate(), $invoice->get_currency_rate() ), $invoice->get_currency_code() ) ); ?>
						</abbr>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>

	<?php endif; ?>

</div>
