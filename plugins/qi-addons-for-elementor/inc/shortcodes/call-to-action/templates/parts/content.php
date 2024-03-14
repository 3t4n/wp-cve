<div class="qodef-m-content">
	<?php if ( ! empty( $title ) ) { ?>
		<<?php echo qi_addons_for_elementor_framework_sanitize_tags( $title_tag ); ?> class="qodef-m-title">
			<?php echo esc_html( $title ); ?>
		</<?php echo qi_addons_for_elementor_framework_sanitize_tags( $title_tag ); ?>>
	<?php } ?>
	<?php if ( ! empty( $text ) ) { ?>
		<div class="qodef-m-text">
			<?php echo qi_addons_for_elementor_framework_wp_kses_html( 'content', $text ); ?>
		</div>
	<?php } ?>
</div>
