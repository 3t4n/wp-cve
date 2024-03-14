<?php $disabled = ( $field['premium_only'] && ! photob_fs()->is_plan_or_trial( $field['min_plan'] ) ) ? 'disabled' : ''; ?>
<label class="pb-settings-label">
	<span class="control"><?php echo wp_kses_post( $field['name'] ); ?>
	<?php
	if ( $field['premium_only'] && ! photob_fs()->is_plan_or_trial( $field['min_plan'] ) ) :
		?>
		<span class='pb-badge'>PREMIUM: <?php echo wp_kses_post( $field['min_plan'] ); ?> plan</span><?php endif ?>
		<input type="text" <?php echo esc_attr( $disabled ); ?> value="" name="<?php echo esc_attr( $field['code'] ); ?>" class="js-serialize"></span>
</label>
<div class="pb-settings-description"><p><?php echo wp_kses_post( $field['description'] ); ?></p></div>
