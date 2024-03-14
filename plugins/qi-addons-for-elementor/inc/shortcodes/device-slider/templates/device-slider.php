<div <?php qi_addons_for_elementor_framework_class_attribute( $holder_classes ); ?>>
	<div class="qodef-device-slider-image">
		<?php if ( 'mobile' === $device ) { ?>
			<img src="<?php echo esc_url( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_URL_PATH . '/device-slider/assets/img/mob-mockup.svg' ); ?>" alt="<?php esc_attr_e( 'phone device', 'qi-addons-for-elementor' ); ?>">
		<?php } elseif ( 'tablet' === $device ) { ?>
			<img src="<?php echo esc_url( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_URL_PATH . '/device-slider/assets/img/tablet-mockup.svg' ); ?>" alt="<?php esc_attr_e( 'tablet device', 'qi-addons-for-elementor' ); ?>">
		<?php } elseif ( 'laptop' === $device ) { ?>
			<img src="<?php echo esc_url( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_URL_PATH . '/device-slider/assets/img/laptop-mockup.svg' ); ?>" alt="<?php esc_attr_e( 'laptop device', 'qi-addons-for-elementor' ); ?>">
		<?php } elseif ( 'custom' === $device ) { ?>
			<?php echo qi_addons_for_elementor_get_list_shortcode_item_image( 'full', $custom_device ); ?>
		<?php } ?>
	</div>
	<div class="qodef-m-items">
		<div <?php qi_addons_for_elementor_framework_class_attribute( $slider_classes ); ?> <?php qi_addons_for_elementor_framework_inline_attr( $slider_attr, 'data-options' ); ?>>
			<div class="swiper-wrapper">
				<?php
				if ( ! empty( $images ) ) {
					foreach ( $images as $image ) {
						qi_addons_for_elementor_template_part( 'shortcodes/device-slider', 'templates/parts/image', '', array_merge( $params, $image ) );
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
		?>
		<?php
		if ( 'outside' === $slider_pagination_position ) {
			qi_addons_for_elementor_template_part( 'content', 'templates/swiper-pag', $slider_pagination_position, $params );
		}
		?>
	</div>
</div>
