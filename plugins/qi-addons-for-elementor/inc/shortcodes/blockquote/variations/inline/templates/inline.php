<div <?php qi_addons_for_elementor_framework_class_attribute( $holder_classes ); ?>>
	<?php if ( ! empty( $text ) ) { ?>
		<<?php echo qi_addons_for_elementor_framework_sanitize_tags( $text_tag ); ?> class="qodef-m-text">
			<?php if ( isset( $icon_type ) && ! empty( $icon_type['value'] ) ) { ?>
				<span class="qodef-m-icon">
					<?php \Elementor\Icons_Manager::render_icon( $icon_type, array( 'aria-hidden' => 'true' ) ); ?>
				</span>
			<?php } ?>
			<?php echo qi_addons_for_elementor_framework_wp_kses_html( 'content', $text ); ?>
		</<?php echo qi_addons_for_elementor_framework_sanitize_tags( $text_tag ); ?>>
	<?php } ?>
</div>
