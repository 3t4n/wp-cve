<?php
/**
 * Displays bill from information.
 *
 * This template can be overridden by copying it to yourtheme/eaccounting/bill/bill-from.php.
 *
 * @var $bill Bill
 * @version 1.1.0
 * @package EverAccounting
 */

use EverAccounting\Models\Bill;

$countries = eaccounting_get_countries();
defined( 'ABSPATH' ) || exit;
?>
<address class="ea-document__billing-info">
	<span class="ea-document__billing-name"><?php echo esc_html( $bill->get_name() ); ?></span>
	<span class="ea-document__info-street"><?php echo esc_html( $bill->get_street() ); ?></span>
	<span class="ea-document__info-city"><?php echo esc_html( implode( ' ', array_filter( array( $bill->get_city(), $bill->get_state(), $bill->get_postcode() ) ) ) ); ?></span>
	<span class="ea-document__info-country"><?php echo isset( $countries[ $bill->get_country() ] ) ? esc_html( $countries[ $bill->get_country() ] ) : ''; ?></span>
	<?php if ( $bill->get_vat_number() ) : ?>
		<span class="ea-document__var-number"><?php echo esc_html__( 'VAT Number', 'wp-ever-accounting' ); ?>: <span><?php echo esc_html( $bill->get_vat_number() ); ?></span></span>
	<?php endif; ?>
</address>
