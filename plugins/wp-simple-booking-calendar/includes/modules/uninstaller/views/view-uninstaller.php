<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

?>

<div class="wpsbc-notice-error">

	<p><strong><?php echo __( 'Important!', 'wp-simple-booking-calendar' ) ?></strong></p>

	<p><?php echo __( 'The uninstaller will remove all WP Simple Booking Calendar information stored in the database for version 8 and higher.', 'wp-simple-booking-calendar' ); ?></p>

	<p><?php echo __( 'This includes, but is not limited to, all calendars, all legend items, all bookings, all plugin settings.', 'wp-simple-booking-calendar' ); ?></p>

	<p><?php echo __( 'After the uninstall process is complete the plugin will be automatically deactivated.', 'wp-simple-booking-calendar' ); ?></p>

</div>

<div id="wpsbc-uninstaller-confirmation" class="wpsbc-notice-error">

	<p><?php echo __( 'To confirm that you really want to uninstall WP Simple Booking Calendar, please type REMOVE in the field below.', 'wp-simple-booking-calendar' ); ?></p>

	<p><input id="wpsbc-uninstaller-confirmation-field" type="text" /></p>

</div>

<a id="wpsbc-uninstaller-button" class="button-primary" href="<?php echo add_query_arg( array( 'wpsbc_action' => 'uninstall_plugin', 'wpsbc_token' => wp_create_nonce( 'wpsbc_uninstall_plugin' ) ), admin_url( 'admin.php' ) ); ?>"><?php echo __( 'Uninstall Plugin', 'wp-simple-booking-calendar' ); ?></a>