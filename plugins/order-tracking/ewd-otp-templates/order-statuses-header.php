<div class='ewd-otp-status-label'>

	<?php if ( in_array( 'order_status', $this->get_option( 'order-information' ) ) ) { ?>
	
		<div class='ewd-otp-statuses-header'>
			<?php echo esc_html( $this->get_label( 'label-order-status' ) ); ?>
		</div>

	<?php } ?>

	<?php if ( in_array( 'order_location', $this->get_option( 'order-information' ) ) ) { ?>

		<div class='ewd-otp-statuses-header'>
			<?php echo esc_html( $this->get_label( 'label-order-location' ) ); ?>
		</div>

	<?php } ?>

	<?php if ( in_array( 'order_updated', $this->get_option( 'order-information' ) ) ) { ?>
		
		<div class='ewd-otp-statuses-header'>
			<?php echo esc_html( $this->get_label( 'label-order-updated' ) ); ?>
		</div>

	<?php } ?>

</div>