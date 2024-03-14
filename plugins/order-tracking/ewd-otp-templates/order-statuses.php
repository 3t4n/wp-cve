<?php foreach ( $this->order->status_history as $status_history ) { ?>

	<div class='ewd-otp-status-label'>
	
		<?php if ( in_array( 'order_status', $this->get_option( 'order-information' ) ) ) { ?>
		
			<div class='ewd-otp-statuses'>
				<?php echo esc_html( $status_history->status ); ?>
			</div>
	
		<?php } ?>
	
		<?php if ( in_array( 'order_location', $this->get_option( 'order-information' ) ) ) { ?>
	
			<div class='ewd-otp-statuses'>
				<?php echo esc_html( $status_history->location ); ?>
			</div>
	
		<?php } ?>
	
		<?php if ( in_array( 'order_updated', $this->get_option( 'order-information' ) ) ) { ?>
			
			<div class='ewd-otp-statuses'>
				<?php echo date( $this->get_option( 'date-format' ), strtotime( $status_history->updated_fmtd ) ); ?>
			</div>
	
		<?php } ?>
	
	</div>

<?php } ?>