<div class="xpro-dashboard-text-item <?php echo esc_attr( $class ); ?>">
	<label class="xpro-dashboard-control-label" for="xpro-dashboard-modules-text-<?php echo esc_attr( $name ); ?>">
		<?php echo esc_html( $label ); ?>
		<span class="xpro-dashboard-control-label-switch" data-active="ON" data-inactive="OFF"></span>
	</label>
	<input
			type="text"
			class="xpro-dashboard-form-control"
			id="xpro-dashboard-modules-text-<?php echo esc_attr( $name ); ?>"
			placeholder="<?php echo esc_attr( $placeholder ); ?>"
			name="<?php echo esc_attr( $name ); ?>"
			autocomplete="off"
			value="<?php echo esc_attr( $value ); ?>"
		<?php echo esc_attr( $disabled ); ?>
	>
</div>
