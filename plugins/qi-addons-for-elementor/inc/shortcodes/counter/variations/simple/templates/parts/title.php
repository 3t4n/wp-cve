<?php if ( ! empty( $title ) ) { ?>
	<<?php echo qi_addons_for_elementor_framework_sanitize_tags( $title_tag ); ?> class="qodef-m-title">
		<?php echo esc_html( $title ); ?>
	</<?php echo qi_addons_for_elementor_framework_sanitize_tags( $title_tag ); ?>>
<?php } ?>
