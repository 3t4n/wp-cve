<div <?php qi_addons_for_elementor_framework_class_attribute( $holder_classes ); ?>>
	<div class="qodef-grid-inner">
		<?php
		foreach ( $items as $item ) {
			$item['client_classes']      = $this_shortcode->get_item_classes( $params );
			$item['images_proportion']   = $images_proportion;
			$item['custom_image_width']  = $custom_image_width;
			$item['custom_image_height'] = $custom_image_height;
			$item['title_tag']           = ! empty( $title_tag ) ? $title_tag : 'h5';

			qi_addons_for_elementor_template_part( 'shortcodes/clients-list', 'templates/client', '', $item );
		}
		?>
	</div>
</div>
