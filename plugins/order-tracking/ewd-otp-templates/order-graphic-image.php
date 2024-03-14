<?php
global $ewd_otp_controller;
$graphic_type = $ewd_otp_controller->settings->get_setting( 'tracking-graphic' );
?>

<div class='ewd-otp-tracking-graphic ewd-otp-<?php echo esc_attr( $graphic_type ); ?>'>

	<div class='ewd-otp-empty-display'>
		<img src='<?php echo EWD_OTP_PLUGIN_URL . '/assets/img/' . $this->get_option( 'tracking-graphic' ) . '.png'; ?>' style='width: 100%'/>
	</div>

	<div class='ewd-otp-full-display' style='width:<?php echo esc_attr( $this->order->current_status->percentage ); ?>%'>
		<img src='<?php echo EWD_OTP_PLUGIN_URL . '/assets/img/' . $this->get_option( 'tracking-graphic' ) . '_full.png'; ?>' style='width: 100%; max-width: initial;'/>
	</div>

	<div class='ewd-otp-statuses'>
		
		<div class='ewd-otp-display-status' id='ewd-otp-initial-status'>
			<?php echo esc_html( $this->get_starting_status() ); ?>
		</div>
	
		<div class='ewd-otp-display-status ewd-otp-current-status-length-<?php echo round( $this->order->current_status->percentage / 100, 1 ) * 10; ?>' id='ewd-otp-current-status'>
			<?php echo esc_html( $this->order->current_status->status ); ?>
		</div>
	
		<div class='ewd-otp-display-status' id='ewd-otp-ending-status'>
			<?php echo esc_html( $this->get_ending_status() ); ?>
		</div>

	</div>

</div>

<div class='ewd-otp-clear'></div>