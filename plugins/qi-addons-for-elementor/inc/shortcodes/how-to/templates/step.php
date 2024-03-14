<div class="qodef-e-step">
	<?php if ( ! empty( $item_title ) ) { ?>
		<<?php echo qi_addons_for_elementor_framework_sanitize_tags( $item_title_tag ); ?> class="qodef-e-step-title" id="<?php echo esc_attr( $title_id ); ?>">
			<?php echo esc_html( $item_title ); ?>
		</<?php echo qi_addons_for_elementor_framework_sanitize_tags( $item_title_tag ); ?>>
		<?php if ( ! empty( $item_content ) ) { ?>
			<p class="qodef-e-step-text">
				<?php echo esc_html( $item_content ); ?>
			</p>
		<?php } ?>
		<?php if ( ! empty( $item_image ) ) { ?>
			<div class="qodef-e-step-image">
				<?php echo qi_addons_for_elementor_get_attachment_image( $item_image, 'full' ); ?>
			</div>
		<?php } ?>
	<?php } ?>
</div>
