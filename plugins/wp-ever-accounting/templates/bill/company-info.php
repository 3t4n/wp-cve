<?php
/**
 * Displays company info.
 *
 * This template can be overridden by copying it to yourtheme/eaccounting/bill/company-info.php.
 *
 * @var $bill Bill
 * @version 1.1.0
 * @package EverAccounting
 */

use EverAccounting\Models\Bill;

defined( 'ABSPATH' ) || exit;
$company_details = array(
	'logo'       => eaccounting()->settings->get( 'company_logo', eaccounting()->plugin_url( '/dist/images/document-logo.png' ) ),
	'name'       => eaccounting()->settings->get( 'company_name' ),
	'street'     => eaccounting()->settings->get( 'company_address' ),
	'city'       => eaccounting()->settings->get( 'company_city' ),
	'state'      => eaccounting()->settings->get( 'company_state' ),
	'postcode'   => eaccounting()->settings->get( 'company_postcode' ),
	'country'    => eaccounting()->settings->get( 'company_country' ),
	'vat_number' => eaccounting()->settings->get( 'company_vat_number' ),
);
$countries       = eaccounting_get_countries();
?>
<div class="ea-document__logo">
	<img src="<?php echo esc_url( $company_details['logo'] ); ?>" alt="<?php echo esc_html( $company_details['name'] ); ?>">
</div>
<address class="ea-document__company-info">
	<span class="ea-document__company-name"><?php echo esc_html( $company_details['name'] ); ?></span>
	<span class="ea-document__info-street"><?php echo esc_html( $company_details['street'] ); ?></span>
	<span class="ea-document__info-city"><?php echo esc_html( implode( ' ', array_filter( array( $company_details['city'], $company_details['state'], $company_details['postcode'] ) ) ) ); ?></span>
	<span class="ea-document__info-country"><?php echo isset( $countries[ $company_details['country'] ] ) ? esc_html( $countries[ $company_details['country'] ] ) : ''; ?></span>
	<?php if ( $company_details['vat_number'] ) : ?>
		<span class="ea-document__var-number"><?php echo esc_html__( 'VAT Number', 'wp-ever-accounting' ); ?>: <span><?php echo esc_html( $company_details['vat_number'] ); ?></span></span>
	<?php endif; ?>
</address>
