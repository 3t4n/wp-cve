<div <?php qi_addons_for_elementor_framework_class_attribute( $holder_classes ); ?>>
	<div class="qodef-before-after-image-holder qodef-m-image" <?php echo qi_addons_for_elementor_framework_get_inline_attrs( $holder_data ); ?>>
		<?php echo qi_addons_for_elementor_get_attachment_image( $image_before, 'full' ); ?>
		<?php echo qi_addons_for_elementor_get_attachment_image( $image_after, 'full' ); ?>
	</div>
	<?php if ( ! empty( $handle_text ) ) { ?>
		<div class="qodef-handle-text">
			<?php echo esc_html( $handle_text ); ?>
		</div>
	<?php } ?>
</div>
