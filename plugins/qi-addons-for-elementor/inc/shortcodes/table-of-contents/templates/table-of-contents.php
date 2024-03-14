<div <?php qi_addons_for_elementor_framework_class_attribute( $holder_classes ); ?>>
	<?php qi_addons_for_elementor_template_part( 'shortcodes/table-of-contents', 'templates/section-title', '', $params ); ?>
	<div class="qodef-m-table-content" data-type="<?php echo esc_attr( $list_type ); ?>" data-excluded-tags="<?php echo esc_html( $exclude_tags ); ?>" data-excluded-cids="<?php echo esc_html( $exclude_cid ); ?>">
		<<?php echo qi_addons_for_elementor_framework_sanitize_tags( $list_type, 'list' ); ?>></<?php echo qi_addons_for_elementor_framework_sanitize_tags( $list_type, 'list' ); ?>>
	</div>
</div>
