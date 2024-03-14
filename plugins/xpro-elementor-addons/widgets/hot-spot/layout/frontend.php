<div class="xpro-hotspot-wrapper">
	<!-- Image -->
	<figure class="xpro-hotspot-image">
		<?php if ( $settings['image'] ) {
			echo wp_kses_post( \Elementor\Group_Control_Image_Size::get_attachment_image_html( $settings, 'media_thumbnail', 'image' ) );
		} ?>
	</figure>

	<?php
	foreach ( $settings['hotspot_items'] as $i => $item ) :

		$html_tag = ( $item['link']['url'] ) ? 'a' : 'span';
		$attr     = $item['link']['is_external'] ? ' target="_blank"' : '';
		$attr    .= $item['link']['nofollow'] ? ' rel="nofollow"' : '';
		$attr    .= $item['link']['url'] ? ' href="' . $item['link']['url'] . '"' : '';

		if ( $item['link'] && $item['link']['custom_attributes'] ) {
			$attributes = explode( ',', $item['link']['custom_attributes'] );

			foreach ( $attributes as $attribute ) {
				if ( ! empty( $attribute ) ) {
					$custom_attr = explode( '|', $attribute, 2 );
					if ( ! isset( $custom_attr[1] ) ) {
						$custom_attr[1] = '';
					}
					$attr .= ' ' . $custom_attr[0] . '="' . $custom_attr[1] . '"';
				}
			}
		}

		?>
	<<?php echo esc_attr( $html_tag ); ?> <?php xpro_elementor_kses( $attr ); ?> class="elementor-repeater-item-<?php echo esc_attr( $item['_id'] ); ?> xpro-hotspot-item">

		<span class="xpro-hotspot-item-wrap xpro-hotspot-type-<?php echo esc_attr( $settings['type'] ); ?> ">

			<?php if ( 'yes' === $item['show_tooltip'] ) : ?>
			<!-- Tooltip -->
			<span class="xpro-hotspot-tooltip-text <?php echo esc_attr( $item['show_default_tooltip'] === 'yes' ? 'xpro-active' : '' ); ?> xpro-hotspot-<?php echo esc_attr( $item['position'] ); ?>">
				<?php echo wp_kses_post( $item['tooltip_text'] ); ?>
			</span>
			<?php endif; ?>

			<?php
			if ( 'icon' === $item['hot_media_type'] ) {
				\Elementor\Icons_Manager::render_icon( $item['hot_icon'], array( 'aria-hidden' => 'true' ) );
			}
			if ( 'image' === $item['hot_media_type'] ) {
				echo wp_kses_post( \Elementor\Group_Control_Image_Size::get_attachment_image_html( $item, 'spots_thumbnail', 'spots_image' ) );
			}
			?>
		</span>
	</<?php echo esc_attr( $html_tag ); ?>>
	<?php endforeach; ?>

</div>
