<?php $options = explode( ',', $this->custom_field->options ); ?>
<?php $field_values = explode( ',', $this->custom_field->field_value ); ?>

<?php foreach ( $options as $option ) { ?>

	<input type='checkbox' name='ewd-otp-custom-field-<?php echo esc_attr( $this->custom_field->id ); ?>[]' <?php echo ( in_array( $option, $field_values ) ? 'checked' : '' ); ?> value='<?php echo esc_attr( $option ); ?>' /> <?php echo esc_html( $option ); ?><br/>

<?php } ?>