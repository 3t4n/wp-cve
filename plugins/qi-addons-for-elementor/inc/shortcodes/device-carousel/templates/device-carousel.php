<div <?php qi_addons_for_elementor_framework_class_attribute( $main_holder_classes ); ?>>
	<div <?php qi_addons_for_elementor_framework_class_attribute( $slider_classes ); ?> <?php qi_addons_for_elementor_framework_inline_attr( $main_slider_attr, 'data-options' ); ?>>
		<div class="swiper-wrapper">
			<?php
			if ( ! empty( $items ) ) {
				foreach ( $items as $item ) {
					$params['item']               = $item;
					$params['item_classes_final'] = $params['item_classes'] . ' elementor-repeater-item-' . $item['_id'];
					qi_addons_for_elementor_template_part( 'shortcodes/device-carousel', 'templates/parts/device', '', $params );
				}
			}
			?>
		</div>
		<?php
		if ( 'inside' === $slider_navigation_position ) {
			qi_addons_for_elementor_template_part( 'content', 'templates/swiper-nav', '', $params );
		}
		if ( 'inside' === $slider_pagination_position ) {
			qi_addons_for_elementor_template_part( 'content', 'templates/swiper-pag', '', $params );
		}
		?>
	</div>
	<?php
	if ( 'outside' === $slider_navigation_position || 'together' === $slider_navigation_position ) {
		qi_addons_for_elementor_template_part( 'content', 'templates/swiper-nav', 'outside', $params );
	}
	if ( 'outside' === $slider_pagination_position ) {
		qi_addons_for_elementor_template_part( 'content', 'templates/swiper-pag', $slider_pagination_position, $params );
	}
	?>
</div>
