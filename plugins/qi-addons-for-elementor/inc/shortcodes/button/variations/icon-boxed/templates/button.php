<a <?php qi_addons_for_elementor_framework_class_attribute( $holder_classes ); ?> href="<?php echo esc_url( $button_link['url'] ); ?>" <?php qi_addons_for_elementor_framework_inline_attrs( $data_attrs ); ?>>
	<span class="qodef-m-text"><?php echo esc_html( $button_text ); ?></span>
	<?php
	if ( 'yes' === $button_icon_enable_side_border ) {
		?>
		<span class="qodef-m-border"></span>
		<?php
	}
	qi_addons_for_elementor_template_part( 'shortcodes/button', 'templates/parts/icon', '', $params );
	?>
</a>
