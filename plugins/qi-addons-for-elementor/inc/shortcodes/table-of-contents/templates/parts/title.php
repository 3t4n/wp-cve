<?php if ( ! empty( $title ) ) { ?>
	<<?php echo qi_addons_for_elementor_framework_sanitize_tags( $title_tag ); ?> class="qodef-m-title qodef-exclude">
		<?php echo qi_addons_for_elementor_framework_wp_kses_html( 'content', $title ); ?>
	</<?php echo qi_addons_for_elementor_framework_sanitize_tags( $title_tag ); ?>>
<?php } ?>
