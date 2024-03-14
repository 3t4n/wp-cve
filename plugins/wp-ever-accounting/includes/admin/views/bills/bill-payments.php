<?php
/**
 * Shows notes
 * Used in view bill page.
 *
 * @package EverAccounting\Admin
 * @var Bill $bill The item being used
 */

use EverAccounting\Models\Bill;

defined( 'ABSPATH' ) || exit();

$payments = $bill->get_payments();
?>
<div class="ea-card__body">
	<?php if ( empty( $payments ) ) : ?>
		<p class="ea-card__inside"><?php esc_html_e( 'There are no payments yet.', 'wp-ever-accounting' ); ?></p>

	<?php else : ?>
		<table class="ea-card__body ea-bill-payment widefat" style="border: 0;">
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
									'page' => 'ea-expenses',
									'tab'  => 'bills',
									's'    => $bill->get_bill_number(),
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
							<?php echo esc_html( eaccounting_price( eaccounting_price_convert( $payment->get_amount(), $payment->get_currency_code(), $bill->get_currency_code(), $payment->get_currency_rate(), $bill->get_currency_rate() ), $bill->get_currency_code() ) ); ?>
						</abbr>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>

	<?php endif; ?>

</div>
