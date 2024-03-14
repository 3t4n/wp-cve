<?php $disabled = ( $field['premium_only'] && ! photob_fs()->is_plan_or_trial( $field['min_plan'] ) ) ? 'disabled' : ''; ?>
<label class="pb-settings-label">
	<span class="control">
		<input <?php echo esc_attr( $disabled ); ?> type="checkbox" value="1" class="js-serialize" name="<?php echo esc_attr( $field['code'] ); ?>">
		<?php echo wp_kses_post( $field['name'] ); ?>
		<?php
		if ( $field['premium_only'] && ! photob_fs()->is_plan_or_trial( $field['min_plan'] ) ) :
			?>
			<span class='pb-badge'>PREMIUM: <?php echo wp_kses_post( $field['min_plan'] ); ?> plan</span><?php endif ?>
		</span>
</label>
<div class="pb-settings-description"><p><?php echo wp_kses_post( $field['description'] ); ?></p></div>
