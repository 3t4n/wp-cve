<div class='ewd-otp-tracking-results-field'>

	<div class='ewd-otp-tracking-results-label'>
		<?php echo esc_html( $this->get_label( 'label-order-current-location' ) ); ?>:
	</div>

	<div class='ewd-otp-tracking-results-value'>
		<iframe width='450' height='250' frameborder='0' style='border:0' src='https://www.google.com/maps/embed/v1/place?key=<?php echo esc_attr( $this->get_option( 'google-maps-api-key' ) ); ?>&q=<?php echo esc_attr( $this->order->current_location->latitude ); ?>,<?php echo esc_attr( $this->order->current_location->longitude ); ?>&zoom=15' allowfullscreen></iframe>

	</div>

</div>