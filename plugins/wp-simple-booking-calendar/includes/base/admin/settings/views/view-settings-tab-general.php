<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

?>


<!-- Calendar Back-end Start Day -->
<div class="wpsbc-settings-field-wrapper wpsbc-settings-field-inline wpsbc-settings-field-small">

	<label class="wpsbc-settings-field-label"><?php echo __( 'Backend Start Day', 'wp-simple-booking-calendar' ); ?></label>

	<div class="wpsbc-settings-field-inner">
		<select name="wpsbc_settings[backend_start_day]">
			<?php 

				$weekday = wpsbc_get_weekdays();

				foreach( $weekday as $key => $day_name ) {

					// Weekdays keys start at 1, not 0
					$key++;

					echo '<option value="' . esc_attr( $key ) . '" ' . ( ! empty( $settings['backend_start_day'] ) ? selected( $settings['backend_start_day'], $key, false ) : '' ) . '>' . $day_name . '</option>';

				}

			?>
		</select>
	</div>
	
</div>


<!-- Submit button -->
<input type="submit" class="button-primary" value="<?php echo __( 'Save Settings', 'wp-simple-booking-calendar' ); ?>" />