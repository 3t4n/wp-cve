<div class='ewd-upcp-catalog-product-custom-field <?php echo esc_attr( $this->custom_field->slug ); ?>'>

	<span><?php echo esc_html( $this->custom_field->name ); ?>:</span>
	
	<?php
		$value = wp_kses_post(
			is_array( $this->product->custom_fields[ $this->custom_field->id ] )
				? implode( ',', $this->product->custom_fields[ $this->custom_field->id ] )
				: $this->product->custom_fields[ $this->custom_field->id ]
		);

		switch( $this->custom_field->type ) {
			case 'link':
				echo "<a href='{$value}' target='_blank'>{$value}</a>";
				break;
			default:
				echo $value;
		}
	?>

</div>