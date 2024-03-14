<?php $options = explode( ',', $this->custom_field->options ); ?>

<?php foreach ( $options as $option ) { ?>

	<input type='radio' name='ewd-otp-custom-field-<?php echo esc_attr( $this->custom_field->id ); ?>' <?php echo ( $this->custom_field->field_value == $option ? 'checked' : '' ); ?> value='<?php echo esc_attr( $option ); ?>' /> <?php echo esc_html( $option ); ?><br />

<?php } ?>