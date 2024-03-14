<div <?php qi_addons_for_elementor_framework_class_attribute( $holder_classes ); ?>>
	<?php if ( ! empty( $text ) ) { ?>
		<span class="qodef-m-letter">
			<?php echo esc_html( substr( $text, 0, 1 ) ); ?>
		</span>
		<p class="qodef-m-text">
			<?php echo qi_addons_for_elementor_framework_wp_kses_html( 'content', substr( $text, 1 ) ); ?>
		</p>
	<?php } ?>
</div>
