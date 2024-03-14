<?php $disabled = ( $field['premium_only'] && ! photob_fs()->is_plan_or_trial( $field['min_plan'] ) ) ? 'disabled' : ''; ?>
<label class="pb-settings-label">
	<span class="control"><?php echo wp_kses_post( $field['name'] ); ?>
	<?php
	if ( $field['premium_only'] && ! photob_fs()->is_plan_or_trial( $field['min_plan'] ) ) :
		?>
		<span class='pb-badge'>PREMIUM: <?php echo wp_kses_post( $field['min_plan'] ); ?> plan</span><?php endif ?>
	<select <?php echo esc_attr( $disabled ); ?> name="<?php echo esc_attr( $field['code'] ); ?>" class="js-serialize p-<?php echo esc_attr( $field['code'] ); ?> <?php echo esc_attr( $field['css_classes'] ); ?>">
	<?php foreach ( $field['values'] as $k => $v ) : ?>
		<option value="<?php echo esc_attr( $k ); ?>"><?php echo wp_kses_post( $v ); ?></option>
	<?php endforeach ?>
	</select>
</label>
<div class="pb-settings-description"><p><?php echo wp_kses_post( $field['description'] ); ?></p></div>
