<div class="xpro-dashboard-widget-item xpro-dashboard-input-switch <?php echo esc_attr( $class ); ?>">
	<input type="checkbox" <?php echo esc_attr( true === $options['checked'] ? 'checked' : '' ); ?> value="<?php echo esc_attr( $value ); ?>" name="<?php echo esc_attr( $name ); ?>" id="xpro-dashboard-modules-switch-<?php echo esc_attr( $value ); ?>"
		<?php
		if ( isset( $attr ) ) {
			foreach ( $attr as $k => $v ) {
				echo esc_html( "$k=$v" );
			}
		}
		?>
		>
	<label class="xpro-dashboard-control-label" for="xpro-dashboard-modules-switch-<?php echo esc_attr( $value ); ?>">
		<?php echo esc_html( $label ); ?>
		<span class="xpro-dashboard-control-label-switch" data-active="ON" data-inactive="OFF"></span>
	</label>
</div>
