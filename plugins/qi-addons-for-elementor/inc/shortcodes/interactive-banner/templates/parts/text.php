<?php if ( ! empty( $text_field ) ) : ?>
	<?php echo '<' . qi_addons_for_elementor_framework_sanitize_tags( $text_tag ); ?> class="qodef-m-text">
		<?php echo esc_html( $text_field ); ?>
	<?php echo '</' . qi_addons_for_elementor_framework_sanitize_tags( $text_tag ); ?>>
<?php endif; ?>
