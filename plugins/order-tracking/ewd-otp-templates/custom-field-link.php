<div class='ewd-otp-custom-field ewd-otp-tracking-results-field'>

	<div class='ewd-otp-custom-field-label ewd-otp-tracking-results-label'>
		<?php echo esc_html( $this->custom_field->name); ?>
	</div>

	<div class='ewd-otp-custom-field-value ewd-otp-tracking-results-value'>

		<a href='<?php echo esc_attr( $this->custom_field->value ); ?>' target='_blank'>
			<?php echo esc_html( $this->custom_field->value ); ?>
		</a>

	</div>

</div>