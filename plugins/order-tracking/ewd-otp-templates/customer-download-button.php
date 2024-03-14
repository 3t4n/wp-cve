<div class='ewd-otp-customer-order-download'>

	<form action='#' method='post'>
	
		<input type='hidden' name='ewd_otp_customer_id' value='<?php echo esc_attr( $this->customer->id ); ?>'>
		<input type='hidden' name='ewd_otp_customer_email' value='<?php echo esc_attr( $this->customer->email ); ?>'>

		<input type='submit' name='ewd_otp_customer_download' value='<?php echo esc_attr( $this->get_label( 'label-customer-display-download' ) ); ?>' />

	</form>

</div>