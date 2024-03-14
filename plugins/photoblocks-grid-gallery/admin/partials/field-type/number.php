<label class="pb-settings-label">
	<span class="control"><?php echo wp_kses_post( $field['name'] ); ?>
		<?php
		if ( array_key_exists( 'deprecated', $field ) ) :
			?>
			<span class="pb-badge"><?php esc_html_e( 'Deprecated', 'photoblocks' ); ?></span><?php endif ?>
		<input type="number" value="" onkeyup="<?php echo esc_attr( $field['onchange'] ); ?>" name="<?php echo esc_attr( $field['code'] ); ?>" class="js-serialize"></span>
</label>
<div class="pb-settings-description"><p><?php echo wp_kses_post( $field['description'] ); ?></p></div>
