<?php if ( ! empty( $bottom_section ) ) { ?>
	<div class="qodef-m-content-shortcode">
		<?php echo Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $bottom_section ); ?>
	</div>
<?php } ?>
