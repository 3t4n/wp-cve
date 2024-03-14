<div <?php qi_addons_for_elementor_framework_class_attribute( $main_holder_classes ); ?>>
	<div class="qodef-m-main-text">
		<div <?php qi_addons_for_elementor_framework_class_attribute( array_merge( $slider_classes, array( 'qodef-slider-switch-device-swiper' ) ) ); ?> <?php qi_addons_for_elementor_framework_inline_attr( $device_slider_attr, 'data-options' ); ?>>
			<div class="swiper-wrapper">
				<?php
				if ( ! empty( $items ) ) {
					foreach ( $items as $item ) {
						$item['item_classes']   = $item_classes;
						$item['item_title_tag'] = $item_title_tag;

						qi_addons_for_elementor_template_part( 'shortcodes/slider-switch', 'templates/parts/text', '', $item );
					}
				}
				?>
			</div>
		</div>
	</div>
	<div class="qodef-m-main">
		<div class="qodef-slider-main-image">
			<?php if ( 'tablet' === $main_device ) { ?>
				<img src="<?php echo esc_url( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_URL_PATH . '/slider-switch/assets/img/tablet-mockup.svg' ); ?>" alt="<?php esc_attr_e( 'tablet device', 'qi-addons-for-elementor' ); ?>">
			<?php } elseif ( 'laptop' === $main_device ) { ?>
				<img src="<?php echo esc_url( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_URL_PATH . '/slider-switch/assets/img/laptop-mockup.svg' ); ?>" alt="<?php esc_attr_e( 'laptop device', 'qi-addons-for-elementor' ); ?>">
			<?php } elseif ( 'custom' === $main_device ) { ?>
				<?php echo qi_addons_for_elementor_get_list_shortcode_item_image( 'full', $main_custom_device ); ?>
			<?php } ?>
		</div>
		<div class="qodef-m-main-slider">
			<div <?php qi_addons_for_elementor_framework_class_attribute( $slider_classes ); ?> <?php qi_addons_for_elementor_framework_inline_attr( $main_slider_attr, 'data-options' ); ?>>
				<div class="swiper-wrapper">
					<?php
					if ( ! empty( $main_images ) ) {
						foreach ( $main_images as $image ) {
							qi_addons_for_elementor_template_part( 'shortcodes/slider-switch', 'templates/parts/image', '', array_merge( $params, $image ) );
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
	</div>
	<div class="qodef-slider-switch-device-holder">
		<div class="qodef-slider-switch-device">
			<div class="qodef-slider-switch-device-image">
				<?php if ( 'mobile' === $device ) { ?>
					<img src="<?php echo esc_url( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_URL_PATH . '/slider-switch/assets/img/mob-mockup.svg' ); ?>" alt="<?php esc_attr_e( 'phone device', 'qi-addons-for-elementor' ); ?>">
				<?php } elseif ( 'tablet' === $device ) { ?>
					<img src="<?php echo esc_url( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_URL_PATH . '/slider-switch/assets/img/tablet-mockup.svg' ); ?>" alt="<?php esc_attr_e( 'tablet device', 'qi-addons-for-elementor' ); ?>">
				<?php } elseif ( 'laptop' === $device ) { ?>
					<img src="<?php echo esc_url( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_URL_PATH . '/slider-switch/assets/img/laptop-mockup.svg' ); ?>" alt="<?php esc_attr_e( 'laptop device', 'qi-addons-for-elementor' ); ?>">
				<?php } elseif ( 'custom' === $device ) { ?>
					<?php echo qi_addons_for_elementor_get_list_shortcode_item_image( 'full', $custom_device ); ?>
				<?php } ?>
			</div>
			<div class="qodef-m-items">
				<div <?php qi_addons_for_elementor_framework_class_attribute( array_merge( $slider_classes, array( 'qodef-slider-switch-device-swiper' ) ) ); ?> <?php qi_addons_for_elementor_framework_inline_attr( $device_slider_attr, 'data-options' ); ?>>
					<div class="swiper-wrapper">
						<?php
						if ( ! empty( $device_images ) ) {
							foreach ( $device_images as $image ) {
								qi_addons_for_elementor_template_part( 'shortcodes/slider-switch', 'templates/parts/device-image', '', array_merge( $params, $image ) );
							}
						}
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
