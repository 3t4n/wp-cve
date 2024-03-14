<div class='ewd-otp-sales-rep-order-download'>

	<form action='#' method='post'>
	
		<input type='hidden' name='ewd_otp_sales_rep_id' value='<?php echo esc_attr( $this->sales_rep->id ); ?>'>
		<input type='hidden' name='ewd_otp_sales_rep_email' value='<?php echo esc_attr( $this->sales_rep->email ); ?>'>

		<input type='submit' name='ewd_otp_sales_rep_download' value='<?php echo esc_html( $this->get_label( 'label-sales-rep-display-download' ) ); ?>' />

	</form>

</div>