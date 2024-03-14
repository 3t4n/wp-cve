<input name='ewd-otp-custom-field-<?php echo esc_attr( $this->custom_field->id ); ?>' type='file' value='<?php echo esc_attr( $this->custom_field->field_value ); ?>' />

<?php if ( ! empty( $this->custom_field->field_value ) ) { ?>

	<div class='ewd-otp-image-preview'>
		<img class='ewd-otp-preview-image' src='<?php echo esc_attr( $this->custom_field->field_value ); ?>' />
		<input type='hidden' name='ewd-otp-custom-field-<?php echo esc_attr( $this->custom_field->id ); ?>' value='<?php echo esc_attr( $this->custom_field->field_value ); ?>' />
	</div>

<?php } ?>