<div <?php qi_addons_for_elementor_framework_class_attribute( $holder_classes ); ?>>
	<div class="qodef-m-inner">
		<?php qi_addons_for_elementor_template_part( 'shortcodes/call-to-action', 'templates/parts/content', '', $params ); ?>
		<?php qi_addons_for_elementor_template_part( 'shortcodes/call-to-action', 'templates/parts/button', '', $params ); ?>
		<?php if ( 'yes' === $enable_link_overlay && ! empty( $button_link['url'] ) ) { ?>
			<a class="qodef-m-link" href="<?php echo esc_url( $button_link['url'] ); ?>" <?php echo qi_addons_for_elementor_framework_get_inline_attrs( qi_addons_for_elementor_get_link_attributes( $button_link ) ); ?>></a>
		<?php } ?>
	</div>
</div>
