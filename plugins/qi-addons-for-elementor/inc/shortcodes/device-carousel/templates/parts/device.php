<div class="<?php echo esc_attr( $item_classes_final ); ?>">
	<div class="qodef-device-carousel-device">
		<div class="qodef-device-carousel-device-image">
			<?php if ( 'mobile' === $item['device'] ) { ?>
				<img src="<?php echo esc_url( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_URL_PATH . '/device-carousel/assets/img/mob-mockup.svg' ); ?>" alt="<?php esc_attr_e( 'phone device', 'qi-addons-for-elementor' ); ?>">
			<?php } elseif ( 'mobile-landscape' === $item['device'] ) { ?>
				<img src="<?php echo esc_url( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_URL_PATH . '/device-carousel/assets/img/mob-land-mockup.svg' ); ?>" alt="<?php esc_attr_e( 'phone landscape device', 'qi-addons-for-elementor' ); ?>">
			<?php } elseif ( 'tablet' === $item['device'] ) { ?>
				<img src="<?php echo esc_url( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_URL_PATH . '/device-carousel/assets/img/tablet-mockup.svg' ); ?>" alt="<?php esc_attr_e( 'tablet device', 'qi-addons-for-elementor' ); ?>">
			<?php } elseif ( 'laptop' === $item['device'] ) { ?>
				<img src="<?php echo esc_url( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_URL_PATH . '/device-carousel/assets/img/laptop-mockup.svg' ); ?>" alt="<?php esc_attr_e( 'laptop device', 'qi-addons-for-elementor' ); ?>">
			<?php } elseif ( 'custom' === $item['device'] ) { ?>
				<?php echo qi_addons_for_elementor_get_list_shortcode_item_image( 'full', $item['custom_device'] ); ?>
			<?php } ?>
		</div>
		<div class="qodef-m-items">
			<div <?php qi_addons_for_elementor_framework_class_attribute( $slider_classes ); ?> <?php qi_addons_for_elementor_framework_inline_attr( $device_slider_attr, 'data-options' ); ?>>
				<div class="swiper-wrapper">
					<?php
					$image_ids = explode( ',', $item['images'] );
					if ( ! empty( $image_ids ) ) {
						foreach ( $image_ids as $image_id ) {
							$params['image_id'] = $image_id;
							qi_addons_for_elementor_template_part( 'shortcodes/device-carousel', 'templates/parts/device-image', '', $params );
						}
					}
					?>
				</div>
			</div>
		</div>
	</div>
</div>
