<div class="qodef-m-digit"></div>
<?php if ( 'yes' === $enable_icon ) { ?>
	<div class="qodef-m-icon">
		<?php if ( isset( $digit_icon ) && ! empty( $digit_icon['value'] ) ) { ?>
			<?php \Elementor\Icons_Manager::render_icon( $digit_icon, array( 'aria-hidden' => 'true' ) ); ?>
		<?php } ?>
	</div>
<?php } ?>
