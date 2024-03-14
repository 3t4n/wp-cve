<?php
/**
 * Displays invoice from information.
 *
 * This template can be overridden by copying it to yourtheme/eaccounting/invoice/invoice-to.php.
 *
 * @var $invoice Invoice
 * @version 1.1.0
 * @package EverAccounting
 */

use EverAccounting\Models\Invoice;

$countries = eaccounting_get_countries();
defined( 'ABSPATH' ) || exit;
?>
<address class="ea-document__billing-info">
	<span class="ea-document__billing-name"><?php echo esc_html( $invoice->get_name() ); ?></span>
	<span class="ea-document__info-street"><?php echo esc_html( $invoice->get_street() ); ?></span>
	<span class="ea-document__info-city"><?php echo esc_html( implode( ' ', array_filter( array( $invoice->get_city(), $invoice->get_state(), $invoice->get_postcode() ) ) ) ); ?></span>
	<span class="ea-document__info-country"><?php echo isset( $countries[ $invoice->get_country() ] ) ? esc_html( $countries[ $invoice->get_country() ] ) : ''; ?></span>
	<?php if ( $invoice->get_vat_number() ) : ?>
		<span class="ea-document__var-number"><?php echo esc_html__( 'VAT Number', 'wp-ever-accounting' ); ?>: <span><?php echo esc_html( $invoice->get_vat_number() ); ?></span></span>
	<?php endif; ?>
</address>
