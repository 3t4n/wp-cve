<div <?php qi_addons_for_elementor_framework_class_attribute( $holder_classes ); ?> <?php qi_addons_for_elementor_framework_inline_attrs( $data_attrs ); ?>>
	<div class="qodef-m-inner">
		<div class="qodef-m-canvas"></div>
		<div class="qodef-m-content">
			<?php if ( ! empty( $title ) ) { ?>
				<<?php echo qi_addons_for_elementor_framework_sanitize_tags( $title_tag ); ?> class="qodef-m-title">
					<?php echo qi_addons_for_elementor_framework_wp_kses_html( 'content', $title ); ?>
				</<?php echo qi_addons_for_elementor_framework_sanitize_tags( $title_tag ); ?>>
			<?php } ?>
			<div class="qodef-m-value"></div>
		</div>
	</div>
</div>
