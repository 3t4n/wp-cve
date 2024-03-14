<?php $fake_card = end( $items ); ?>
<div <?php qi_addons_for_elementor_framework_class_attribute( $holder_classes ); ?> <?php qi_addons_for_elementor_framework_inline_attrs( $data_attrs ); ?>>
	<div class="qodef-e-inner">
		<?php foreach ( $items as $item ) { ?>
			<div class="qodef-m-card">
				<div class="qodef-m-bundle-item">
					<?php if ( '' !== $item['item_link']['url'] ) { ?>
						<a href="<?php echo esc_url( $item['item_link']['url'] ); ?>" <?php echo qi_addons_for_elementor_framework_get_inline_attrs( qi_addons_for_elementor_get_link_attributes( $item['item_link'] ) ); ?>>
					<?php } ?>
						<?php echo qi_addons_for_elementor_get_list_shortcode_item_image( $images_proportion, $item['item_image'], intval( $custom_image_width ), intval( $custom_image_height ) ); ?>
					<?php if ( '' !== $item['item_link']['url'] ) { ?>
						</a>
					<?php } ?>
				</div>
			</div>
		<?php } ?>
	</div>
	<div class="qodef-m-fake-card">
		<?php echo qi_addons_for_elementor_get_list_shortcode_item_image( $images_proportion, $fake_card['item_image'], intval( $custom_image_width ), intval( $custom_image_height ) ); ?>
	</div>
	<div class="qodef-m-navigation">
		<div class="qodef-nav qodef--prev"><?php qi_addons_for_elementor_template_part( 'content', 'templates/parts/arrow-left', '', $params ); ?></div>
		<div class="qodef-nav qodef--next"><?php qi_addons_for_elementor_template_part( 'content', 'templates/parts/arrow-right', '', $params ); ?></div>
	</div>
</div>
