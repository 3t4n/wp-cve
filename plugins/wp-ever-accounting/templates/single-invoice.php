<?php
/**
 * Single invoice page.
 *
 * This template can be overridden by copying it to yourtheme/eaccounting/single-invoice.php.
 *
 * @version 1.1.0
 * @var int $invoice_id
 * @var string $key
 * @package EverAccounting
 */

defined( 'ABSPATH' ) || exit();

if ( empty( $key ) || empty( $invoice_id ) ) {
	eaccounting_get_template( 'unauthorized.php' );
	exit();
}
$invoice = eaccounting_get_invoice( $invoice_id );
if ( empty( $invoice ) || ! $invoice->is_key_valid( $key ) ) {
	eaccounting_get_template( 'unauthorized.php' );
	exit();
}
?>


<?php do_action( 'eaccounting_public_before_invoice', $invoice ); ?>
<div class="ea-card">
	<div class="ea-card__inside">
		<?php eaccounting_get_template( 'invoice/invoice.php', array( 'invoice' => $invoice ) ); ?>
	</div>
</div>
<?php do_action( 'eaccounting_public_after_invoice', $invoice ); ?>
