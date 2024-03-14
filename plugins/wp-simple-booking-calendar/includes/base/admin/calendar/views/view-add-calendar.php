<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

$calendars = wpsbc_get_calendars( array( 'status' => 'active' ) );

?>

<div class="wrap wpsbc-wrap wpsbc-wrap-add-calendar">

	<form action="" method="POST">
		
		<!-- Icon -->
		<div id="wpsbc-add-new-calendar-icon">
			<div class="wpsbc-icon-wrap">
				<span class="dashicons dashicons-calendar-alt"></span>
				<span class="dashicons dashicons-plus"></span>
			</div>
		</div>

		<!-- Heading -->
		<h1 id="wpsbc-add-new-calendar-heading"><?php echo __( 'Add New Calendar', 'wp-simple-booking-calendar' ); ?></h1>

		<!-- Postbox -->
		<div id="wpsbc-add-new-calendar-postbox" class="postbox">

			<!-- Form Fields -->
			<div class="inside">

				<!-- Add Calendar Name -->
				<label for="wpsbc-new-calendar-name"><?php echo __( 'Calendar Name', 'wp-simple-booking-calendar' ); ?> *</label>
				<input id="wpsbc-new-calendar-name" name="calendar_name" type="text" value="<?php echo ( ! empty( $_POST['calendar_name'] ) ? esc_attr( $_POST['calendar_name'] ) : '' ); ?>" />
			
			</div>

			<!-- Form Submit button -->
			<div id="major-publishing-actions">
				<a href="<?php echo admin_url( $this->admin_url ); ?>"><?php echo __( 'Cancel', 'wp-simple-booking-calendar' ); ?></a>
				<input type="submit" class="button-primary wpsbc-button-large" value="<?php echo __( 'Add Calendar', 'wp-simple-booking-calendar' ); ?>" />
			</div>

			<!-- Action and nonce -->
			<input type="hidden" name="wpsbc_action" value="add_calendar" />
			<?php wp_nonce_field( 'wpsbc_add_calendar', 'wpsbc_token', false ); ?>

		</div>

	</form>

</div>