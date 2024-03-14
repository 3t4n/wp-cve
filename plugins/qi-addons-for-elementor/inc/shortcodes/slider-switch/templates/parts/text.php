<div class="<?php echo esc_attr( $item_classes ); ?>">
	<?php if ( ! empty( $item_title ) ) { ?>
		<<?php echo qi_addons_for_elementor_framework_sanitize_tags( $item_title_tag ); ?> class="qodef-e-title">
			<?php echo esc_html( $item_title ); ?>
		</<?php echo qi_addons_for_elementor_framework_sanitize_tags( $item_title_tag ); ?>>
	<?php } ?>
	<p itemprop="description" class="qodef-e-text"><?php echo esc_html( $item_text ); ?></p>
	<?php if ( ! empty( $item_link['url'] ) ) { ?>
		<a itemprop="url" href="<?php echo esc_url( $item_link['url'] ); ?>" <?php echo qi_addons_for_elementor_framework_get_inline_attrs( qi_addons_for_elementor_get_link_attributes( $item_link ) ); ?>></a>
	<?php } ?>
</div>
