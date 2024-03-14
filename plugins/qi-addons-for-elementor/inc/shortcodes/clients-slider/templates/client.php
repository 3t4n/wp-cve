<div <?php qi_addons_for_elementor_framework_class_attribute( $client_classes ); ?>>
	<div class="qodef-e-inner">
		<?php if ( ! empty( $client_link['url'] ) ) { ?>
			<a href="<?php echo esc_url( $client_link['url'] ); ?>" <?php echo qi_addons_for_elementor_framework_get_inline_attrs( qi_addons_for_elementor_get_link_attributes( $client_link ) ); ?>>
		<?php } ?>
		<div class="qodef-e-images-holder">
			<?php if ( ! empty( $client_main_image ) ) { ?>
				<div class="qodef-e-main-image">
					<?php echo qi_addons_for_elementor_get_list_shortcode_item_image( $images_proportion, $client_main_image, intval( $custom_image_width ), intval( $custom_image_height ) ); ?>
				</div>
			<?php } ?>
			<?php if ( ! empty( $client_hover_image ) ) { ?>
				<div class="qodef-e-hover-image">
					<?php echo qi_addons_for_elementor_get_list_shortcode_item_image( $images_proportion, $client_hover_image, intval( $custom_image_width ), intval( $custom_image_height ) ); ?>
				</div>
			<?php } ?>
		</div>
		<?php if ( ! empty( $client_link['url'] ) ) { ?>
			</a>
		<?php } ?>
		<?php qi_addons_for_elementor_template_part( 'shortcodes/clients-slider', 'templates/parts/title', '', $params ); ?>
		<?php qi_addons_for_elementor_template_part( 'shortcodes/clients-slider', 'templates/parts/icon', '', $params ); ?>
	</div>
</div>
