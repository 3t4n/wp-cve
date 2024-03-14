<?php $options = explode( ',', $this->custom_field->options ); ?>

<select name='ewd-otp-custom-field-<?php echo esc_attr( $this->custom_field->id ); ?>'>
	
	<?php foreach ( $options as $option ) { ?>
		<option value='<?php echo esc_attr( $option ); ?>' <?php echo ( $this->custom_field->field_value == $option ? 'selected' : '' ); ?> ><?php echo esc_html( $option ); ?></option>
	<?php } ?>

</select>