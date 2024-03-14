<?php
/**
 * Displays a Bill.
 *
 * This template can be overridden by copying it to yourtheme/eaccounting/bill/bill.php.
 *
 * @var $bill Bill
 * @version 1.1.0
 * @package EverAccounting
 */

use EverAccounting\Models\Bill;

defined( 'ABSPATH' ) || exit;
?>
<div class="ea-document ea-bill">
	<?php if ( ! $bill->needs_payment() ) : ?>
		<div class="ea-document__watermark">
			<p><?php echo esc_html( $bill->get_status_nicename() ); ?></p>
		</div>
	<?php endif; ?>

	<div class="ea-document__section">
		<div class="ea-document__column alignleft">
			<h4 class="ea-document__number"><?php echo esc_html( $bill->get_bill_number() ); ?></h4>
			<?php eaccounting_get_template( 'bill/company-info.php', array( 'bill' => $bill ) ); ?>
		</div>
		<div class="ea-document__column alignright">
			<span class="ea-document__to"><?php esc_html_e( 'Bill From:', 'wp-ever-accounting' ); ?></span>
			<?php eaccounting_get_template( 'bill/bill-from.php', array( 'bill' => $bill ) ); ?>
			<?php eaccounting_get_template( 'bill/bill-meta.php', array( 'bill' => $bill ) ); ?>
		</div>
	</div>

	<?php eaccounting_get_template( 'bill/bill-items.php', array( 'bill' => $bill ) ); ?>
	<?php eaccounting_get_template( 'bill/bill-note.php', array( 'bill' => $bill ) ); ?>
	<?php eaccounting_get_template( 'bill/bill-terms.php', array( 'bill' => $bill ) ); ?>

</div>
