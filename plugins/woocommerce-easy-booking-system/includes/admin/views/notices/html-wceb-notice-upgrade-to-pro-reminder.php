<?php

/**
*
* Show a notice to remind add-on users that the new PRO version of Easy Booking is available.
* @version 3.1.9
*
**/

defined( 'ABSPATH' ) || exit;

?>

<div class="notice easy-booking-notice">

	<button type="button" class="notice-dismiss easy-booking-notice-close" data-notice="pro_upgrade_reminder"></button>

	<h4><?php esc_html_e( 'Easy Booking: upgrade to PRO version.', 'woocommerce-easy-booking-system' ); ?></h4>

	<p>
		<?php esc_html_e( 'You have until December 31st, 2021 to upgrade your old Easy Booking add-ons to the PRO version.', 'woocommerce-easy-booking-system' ); ?>
		<br />
		<?php printf( esc_html__( 'If you have one or several add-ons and a valid license key, %syou can download the new PRO version for free%s.', 'woocommerce-easy-booking-system' ), '<b>', '</b>' ); ?>
	</p>
	
	<p>
		<a href="https://easy-booking.pro/migrate-to-pro/" target="_blank" class="button easy-booking-button"><?php esc_html_e( 'Upgrade to PRO', 'woocommerce-easy-booking-system' ); ?></a>
	</p>

</div>