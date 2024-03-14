<<?php echo qi_addons_for_elementor_framework_sanitize_tags( $title_tag ); ?> class="qodef-e-title-holder">
	<span class="qodef-e-title">
		<?php qi_addons_for_elementor_template_part( 'shortcodes/faq', 'templates/parts/number', '', $params ); ?>
		<?php echo esc_html( $item_title ); ?>
	</span>
	<?php qi_addons_for_elementor_template_part( 'shortcodes/faq', 'templates/parts/mark', '', $params ); ?>
</<?php echo qi_addons_for_elementor_framework_sanitize_tags( $title_tag ); ?>>
<div class="qodef-e-content">
	<div class="qodef-e-content-inner">
		<?php echo qi_addons_for_elementor_framework_wp_kses_html( 'content', $item_content ); ?>
	</div>
</div>
