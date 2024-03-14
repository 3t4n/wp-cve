<?php $icons = json_decode( file_get_contents( AREOI__PLUGIN_DIR . 'blocks/icon/icons.json' ), true ); ?>

<select 
	name="<?php echo esc_attr( $option['name'] ) ?>" 
	type="text" 
	id="<?php echo esc_attr( $option['name'] ) ?>" 
	data-form-type="other"
	data-default="<?php echo esc_attr( $option['default'] ) ?>"
>
	<option value="">None</option>
	<?php foreach ( $icons as $icon_key => $icon ) : ?>
		<option 
			<?php echo esc_attr( ( $value == $icon ) || ( !$value && $icon == $option['default'] ) ? 'selected="selected"' : '' ) ?> 
			value="<?php echo esc_attr( $icon ) ?>" 
		>
			<?php echo esc_attr( $icon ) ?>
		</option>
	<?php endforeach; ?>
</select>

<p>You can browse all available icons <a href="https://icons.getbootstrap.com/" target="_blank">here</a>.</p>