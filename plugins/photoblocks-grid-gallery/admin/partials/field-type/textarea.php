<label class="pb-settings-label">
	<span class="control"><?php echo wp_kses_post( $field['name'] ); ?></span>
	<textarea name="<?php echo esc_attr( $field['code'] ); ?>" class="js-serialize"></textarea>
</label>
<div class="pb-settings-description"><p><?php echo wp_kses_post( htmlentities( $field['description'] ) ); ?></p></div>
