<label class="pb-settings-label">
	<span class="control"><?php echo wp_kses_post( $field['name'] ); ?>
		<input type="text" value="" name="<?php echo esc_attr( $field['code'] ); ?>" class="js-serialize js-colpick"></span>
</label>
<div class="pb-settings-description"><p><?php echo wp_kses_post( $field['description'] ); ?></p></div>
