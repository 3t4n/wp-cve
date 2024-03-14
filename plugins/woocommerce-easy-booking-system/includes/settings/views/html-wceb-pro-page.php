<?php

/**
*
* Admin: Pro page template.
* @version 3.1.9
*
**/

defined( 'ABSPATH' ) || exit;

$active_plugins = (array) get_option( 'active_plugins', array() );

if ( is_multisite() ) {
    $active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
}
        
?>

<div class="wrap">

	<h2 class="screen-reader-text"><?php esc_html_e( 'Easy Booking PRO', 'woocommerce-easy-booking-system' ); ?></h2>

	<?php if ( ! array_key_exists( 'easy-booking-pro/easy-booking-pro.php', $active_plugins ) && ! in_array( 'easy-booking-pro/easy-booking-pro.php', $active_plugins ) ) : ?>

		<div class="wceb-pro-settings-wrapper">
			
			<h3><?php esc_html_e( 'What is Easy Booking PRO?', 'woocommerce-easy-booking-system' ); ?></h3>

			<p><?php esc_html_e( 'Easy Booking PRO is a premium extension for Easy Booking which adds lots of important features to enhance your WooCommerce store: stock management per date, disabled dates, prices depending on duration and/or dates, manual bookings and more.', 'woocommerce-easy-booking-system' ); ?></p>

			<a href="https://easy-booking.pro/pro/" class="button easy-booking-button" target="_blank"><?php esc_html_e( 'Read more', 'woocommerce-easy-booking-system' ); ?></a>

		</div>

	<?php endif; ?>

	<?php do_action( 'easy_booking_pro_page' ) ?>

</div>