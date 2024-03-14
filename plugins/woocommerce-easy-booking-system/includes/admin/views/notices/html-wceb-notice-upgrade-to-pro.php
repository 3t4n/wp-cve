<?php

/**
*
* Show a notice to inform add-on users that the new PRO version of Easy Booking is available.
* @version 3.1.9
*
**/

defined( 'ABSPATH' ) || exit;

?>

<div class="notice easy-booking-notice">

	<button type="button" class="notice-dismiss easy-booking-notice-close" data-notice="pro_upgrade"></button>

	<h4><?php esc_html_e( 'Easy Booking: upgrade to PRO version.', 'woocommerce-easy-booking-system' ); ?></h4>

	<p>
		<?php esc_html_e( 'Add-ons have been merged into a single PRO version regrouping all features. Add-ons will be maintained and supported until December 31st, 2021 only.', 'woocommerce-easy-booking-system' ); ?>
		<br />
		<?php printf( esc_html__( 'If you have one or several add-ons and a valid license key, %syou can download the new PRO version for free%s.', 'woocommerce-easy-booking-system' ), '<b>', '</b>' ); ?>
		<br />
		<?php printf( esc_html__( 'Please be careful when upgrading and %sfollow the instructions%s.', 'woocommerce-easy-booking-system' ), '<b>', '</b>' ); ?>
	</p>
	
	<p>
		<a href="https://easy-booking.pro/migrate-to-pro/" target="_blank" class="button easy-booking-button"><?php esc_html_e( 'Upgrade to PRO', 'woocommerce-easy-booking-system' ); ?></a>
	</p>

</div>