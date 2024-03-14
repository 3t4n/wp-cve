<div class="qodef-e-icon-holder">
	<div class="qodef-e-icon">
		<?php if ( isset( $icon_type ) && ! empty( $icon_type['value'] ) ) { ?>
			<span class="qodef-e-item-icon-text">
				<?php \Elementor\Icons_Manager::render_icon( $icon_type, array( 'aria-hidden' => 'true' ) ); ?>
			</span>
			<div class="qodef-e-number">
				<?php echo esc_html( $item_key ); ?>
			</div>
		<?php } else { ?>
			<span class="qodef-e-item-icon-text">
				<?php echo esc_html( $item_key ); ?>
			</span>
		<?php } ?>
	</div>
	<div class="qodef-e-line">
		<div class="qodef-e-line-inner"></div>
	</div>
</div>
