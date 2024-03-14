<div class='ewd-otp-tracking-graphic'>

	<div id='ewd-otp-progressbar-<?php echo $this->get_option( 'tracking-graphic' ); ?>'>
		<div class='<?php echo $this->get_option( 'tracking-graphic' ); ?>' style='width: <?php echo esc_attr( $this->order->current_status->percentage ); ?>%'></div>
	</div>
	
	<div class='ewd-otp-statuses'>

		<div class='ewd-otp-display-status' id='ewd-otp-initial-status'>
			<?php echo ( $this->order->current_status->percentage == 0 ? esc_html ( $this->order->current_status->status ) : esc_html( $this->get_starting_status() ) ); ?>
		</div>

		<?php if ( $this->order->current_status->percentage != 0 and $this->order->current_status->percentage != 100 ) { ?>

			<div class='ewd-otp-display-status' id='ewd-otp-current-status' style='margin-left: <?php echo max( 5, min( 55, $this->order->current_status->percentage - 10 ) ); ?>%'>
				<?php echo esc_html ( $this->order->current_status->status ); ?>
			</div>

		<?php } ?>

		<div class='ewd-otp-display-status' id='ewd-otp-ending-status'>
			<?php echo ( $this->order->current_status->percentage == 100 ? esc_html ( $this->order->current_status->status ) : esc_html( $this->get_ending_status() ) ); ?>
		</div>

	</div>

</div>