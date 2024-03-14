<div <?php qi_addons_for_elementor_framework_class_attribute( $holder_classes ); ?>>
	<?php if ( isset( $icon_type ) && ! empty( $icon_type['value'] ) ) { ?>
		<div class="qodef-m-icon-wrapper">
			<?php qi_addons_for_elementor_template_part( 'shortcodes/info-cards', 'templates/parts/icon', '', $params ); ?>
		</div>
	<?php } ?>
	<div class="qodef-m-content">
		<?php qi_addons_for_elementor_template_part( 'shortcodes/info-cards', 'templates/parts/subtitle', '', $params ); ?>
		<?php qi_addons_for_elementor_template_part( 'shortcodes/info-cards', 'templates/parts/title', '', $params ); ?>
		<?php qi_addons_for_elementor_template_part( 'shortcodes/info-cards', 'templates/parts/text', '', $params ); ?>
	</div>
	<?php qi_addons_for_elementor_template_part( 'shortcodes/info-cards', 'templates/parts/button', '', $params ); ?>
	<?php if ( 'yes' === $enable_link_overlay && ! empty( $link['url'] ) ) { ?>
		<a class="qodef-m-link" href="<?php echo esc_url( $link['url'] ); ?>" <?php echo qi_addons_for_elementor_framework_get_inline_attrs( qi_addons_for_elementor_get_link_attributes( $link ) ); ?>></a>
	<?php } ?>
</div>
