<div class='ewd-upcp-single-product-custom-field'>

	<span class='ewd-upcp-single-product-extra-element-label'>
		<?php echo esc_html( $this->custom_field->name ); ?>:
	</span>

	<span class='ewd-upcp-single-product-extra-element-value'>
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
	</span>

</div>