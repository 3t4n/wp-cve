<div <?php qi_addons_for_elementor_framework_class_attribute( $classes ); ?>>
	<?php if ( ! empty( $item_image ) ) { ?>
		<div class="qodef-e-image">
			<?php echo qi_addons_for_elementor_get_attachment_image( $item_image, 'full' ); ?>
		</div>
	<?php } ?>
	<div class="qodef-e-item-holder">
		<?php if ( ! empty( $item_title ) ) : ?>
			<div class="qodef-e-heading">
				<<?php echo qi_addons_for_elementor_framework_sanitize_tags( $title_tag ); ?> class="qodef-e-heading-title"><?php echo wp_kses_post( $item_title ); ?></<?php echo qi_addons_for_elementor_framework_sanitize_tags( $title_tag ); ?>>
				<div class="qodef-e-heading-line" <?php qi_addons_for_elementor_framework_inline_style( $border_style ); ?>></div>
				<?php if ( ! empty( $item_price ) ) : ?>
					<p class="qodef-e-heading-price"><?php echo esc_html( $item_price ); ?></p>
				<?php endif; ?>
				<?php if ( ! empty( $item_discount_price ) ) : ?>
					<p class="qodef-e-heading-discount-price"><?php echo esc_html( $item_discount_price ); ?></p>
				<?php endif; ?>
			</div>
		<?php endif; ?>
		<?php if ( ! empty( $item_description ) ) : ?>
			<p class="qodef-e-description"><?php echo wp_kses_post( $item_description ); ?></p>
		<?php endif; ?>
	</div>
</div>
