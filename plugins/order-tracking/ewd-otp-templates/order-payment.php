<div class='ewd-otp-tracking-results-field'>

	<div class='ewd-otp-tracking-results-label'>
		<?php _e( 'Order Payment', 'order-tracking' ) ?>:
	</div>

	<div class='ewd-otp-tracking-results-value'>

		<?php if ( ! $this->order->payment_completed ) { ?>

			<div class='ewd-otp-paypal-form'>
			
				<form action='https://www.paypal.com/cgi-bin/webscr' method='post' class='standard-form'>
    				
    				<input type='hidden' name='item_name_1' value='<?php echo __( 'Payment for order number' , 'order-tracking' ) . ' ' . esc_attr( $this->order->number ); ?>' />
    				<input type='hidden' name='quantity_1' value='1' />
    				<input type='hidden' name='amount_1' value='<?php echo esc_attr( $this->order->payment_price ); ?>' />
					<input type='hidden' name='custom' value='<?php echo esc_attr( $this->order->id ); ?>' />
   					<input type='hidden' name='cmd' value='_cart' />
   					<input type='hidden' name='upload' value='1' />
   					<input type='hidden' name='business' value='<?php echo esc_attr( $this->get_option( 'paypal-email-address' ) ); ?>' />
   					<input type='hidden' name='currency_code' value='<?php echo esc_attr( $this->get_option( 'pricing-currency-code' ) ); ?>' />
   					<input type='hidden' name='return' value='<?php echo esc_attr( $this->get_option( 'thank-you-url' ) ); ?>' />
   					<input type='hidden' name='notify_url' value='<?php echo esc_attr( get_site_url() ); ?>' />

   					<input type='submit' class='submit-button' value='<?php _e( 'Proceed to Payment', 'order-tracking' ); ?>' />
				
				</form>
			
			</div>

		<?php } ?>

		<?php if ( $this->order->payment_completed ) { ?>

			<?php _e( 'Payment Completed', 'order-tracking' ) ?>

		<?php } ?>

	</div>

</div>