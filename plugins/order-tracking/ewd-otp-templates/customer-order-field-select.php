<?php $options = explode( ',', $this->custom_field->options ); ?>

<div class='ewd-otp-customer-order-form-field'>

	<div class='ewd-otp-customer-order-form-label'>
		<?php echo esc_html( $this->custom_field->name ); ?>:
	</div>
	
	<div class='ewd-otp-customer-order-form-value'>
		
		<select name='ewd_otp_custom_field_<?php echo esc_attr( $this->custom_field->id ); ?>' <?php echo ( $this->custom_field->required ? 'required' : '' ); ?> >

			<?php foreach ( $options as $option ) { ?>
				<option value='<?php echo esc_attr( $option ); ?>'><?php echo esc_html( $option ); ?></option>
			<?php } ?>

		</select>
		
	</div>
	
</div>