<a itemprop="url" class="qodef-m-item qodef-e" href="<?php echo esc_url( $item_link['url'] ); ?>" <?php echo qi_addons_for_elementor_framework_get_inline_attrs( qi_addons_for_elementor_get_link_attributes( $item_link ) ); ?>>
	<span class="qodef-e-title"><span class="qodef-e-inner-title"><?php echo esc_html( $item_title ); ?></span></span>
	<?php if ( ! empty( $item_text ) ) { ?>
		<span class="qodef-e-text"><?php echo esc_html( $item_text ); ?></span>
	<?php } ?>
</a>
