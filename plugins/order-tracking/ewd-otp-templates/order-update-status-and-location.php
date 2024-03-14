<form action='#' method='post' class='ewd-otp-front-end-update-form'>
	
	<div class='ewd-otp-update-status-and-location'>
		
		<input type='hidden' name='action' value='ewd_otp_update_status' />
		<input type='hidden' name='ewd_otp_order_id' value='<?php echo esc_attr( $this->order->id ); ?>' />

		<div class="ewd-order-status">
			
			<select name='ewd_otp_order_status'>
				
				<?php foreach ( $this->get_possible_statuses() as $status ) { ?>
					<option value='<?php echo esc_attr( $status->status ); ?>'><?php echo esc_attr( $status->status ); ?></option>
				<?php } ?>
			
			</select>
		
		</div>

		<?php

		$location_list = $this->get_possible_locations();

		if( count( $location_list ) > 0) {

		?>

		<div class="ewd-order-location">

			<select name='ewd_otp_order_location'>

				<?php foreach ( $location_list as $location ) { ?>
					<option value='<?php echo esc_attr( $location->name ); ?>'><?php echo esc_attr( $location->name ); ?></option>
				<?php } ?>

			</select>

		</div>

		<?php

		}

		?>

		<div>
			<input type='submit' name='ewd_otp_update_status_and_location' value='<?php echo esc_attr( $this->get_label( 'label-order-update-status' ) ); ?>' />
		</div>

	</div>

</form>