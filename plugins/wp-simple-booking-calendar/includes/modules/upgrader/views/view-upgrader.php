<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

?>

<div class="wrap wpsbc-wrap wpsbc-wrap-upgrader">
		
	<!-- Icon -->
	<div id="wpsbc-add-new-calendar-icon">
		<div class="wpsbc-icon-wrap">
			<span class="dashicons dashicons-calendar-alt"></span>
		</div>
	</div>

	<!-- Heading -->
	<h1 id="wpsbc-add-new-calendar-heading"><?php echo __( 'Welcome to WP Simple Booking Calendar v.2', 'wp-simple-booking-calendar' ); ?></h1>

	<div id="wpsbc-upgrader-content">

		<!-- Welcome Text Content -->	
		<div id="wpsbc-upgrader-content-inner">
			<p><?php echo __( "This new version was written from the ground up to make the plugin easier to use, more flexible and more stable.", 'wp-simple-booking-calendar' ); ?></p>
			<p><?php echo __( "To start using the new version we will have to do a quick setup to migrate your existing data to the new format.", 'wp-simple-booking-calendar' ); ?></p>
			<p><?php echo __( "The setup should take just a few moments. For safe keeping, your old data will not be removed.", 'wp-simple-booking-calendar' ); ?></p>
		</div>

		<!-- Upgrade Loading Bar -->
		<div id="wpsbc-upgrader-loading-bar-wrapper" data-step="1">

			<div id="wpsbc-upgrader-loading-bar">

				<!-- Loading Bar Steps -->
				<div id="wpsbc-upgrader-loading-bar-step-1" class="wpsbc-upgrader-loading-bar-step"></div>
				<div id="wpsbc-upgrader-loading-bar-step-2" class="wpsbc-upgrader-loading-bar-step"></div>
				<div id="wpsbc-upgrader-loading-bar-step-3" class="wpsbc-upgrader-loading-bar-step"></div>
				<div id="wpsbc-upgrader-loading-bar-step-4" class="wpsbc-upgrader-loading-bar-step"></div>

				<!-- Loading Messages - Doing -->
				<p id="wpsbc-upgrader-message-doing-step-1" class="wpsbc-upgrader-message-doing-step description"><?php echo __( 'Migrating calendars...', 'wp-simple-booking-calendar' ); ?></p>
				<p id="wpsbc-upgrader-message-doing-step-2" class="wpsbc-upgrader-message-doing-step description"><?php echo __( 'Migrating bookings...', 'wp-simple-booking-calendar' ); ?></p>
				<p id="wpsbc-upgrader-message-doing-step-3" class="wpsbc-upgrader-message-doing-step description"><?php echo __( 'Migrating general settings...', 'wp-simple-booking-calendar' ); ?></p>
				<p id="wpsbc-upgrader-message-doing-step-4" class="wpsbc-upgrader-message-doing-step description"><?php echo __( 'Finishing up...', 'wp-simple-booking-calendar' ); ?></p>

				<!-- Loading Messages - To-do/Done -->
				<p id="wpsbc-upgrader-message-step-1" class="wpsbc-upgrader-message-step description"><?php echo __( 'Calendars', 'wp-simple-booking-calendar' ); ?></p>
				<p id="wpsbc-upgrader-message-step-2" class="wpsbc-upgrader-message-step description"><?php echo __( 'Bookings', 'wp-simple-booking-calendar' ); ?></p>
				<p id="wpsbc-upgrader-message-step-3" class="wpsbc-upgrader-message-step description"><?php echo __( 'General settings', 'wp-simple-booking-calendar' ); ?></p>
				<p id="wpsbc-upgrader-message-step-4" class="wpsbc-upgrader-message-step description"><?php echo __( 'Finished', 'wp-simple-booking-calendar' ); ?></p>

				<!-- Loader -->
				<div class="spinner"><!-- --></div>

			</div>

		</div>

		<!-- Action Buttons -->
		<div id="wpsbc-upgrader-button-wrapper">

			<?php wp_nonce_field( 'wpsbc_upgrader', 'wpsbc_token', false ); ?>

			<a id="wpsbc-upgrader-button-start-upgrade" href="#" class="button-primary wpsbc-button-large">
				<span><?php echo __( "Let's start!", 'wp-simple-booking-calendar' ); ?></span>
				<span><?php echo __( "Please wait...", 'wp-simple-booking-calendar' ); ?></span>
			</a>

			<a id="wpsbc-upgrader-button-continue" href="<?php echo add_query_arg( array( 'page' => 'wpsbc-calendars' ), admin_url( 'admin.php' ) ); ?>" class="button-primary wpsbc-button-large"><?php echo __( "Continue to plugin", 'wp-simple-booking-calendar' ); ?></a>

			<div class="spinner"><!-- --></div>
		</div>

		<!-- Skip Action -->
		<div id="wpsbc-upgrader-skip-wrapper">
			<p class="description"><?php echo sprintf( __( "If you wish to start fresh and skip the migration of your calendars and settings, from the old version, %sclick here%s.", 'wp-simple-booking-calendar' ), '<a href="' . wp_nonce_url( add_query_arg( array( 'wpsbc_action' => 'skip_upgrade_process' ) ), 'wpsbc_skip_upgrade_process', 'wpsbc_token' ) . '">', '</a>' ); ?></p>
		</div>

	</div>

</div>