<?php
use Elementor\Group_Control_Image_Size;
use Elementor\Icons_Manager;
?>
<ul class="xpro-infolist-wrapper xpro-infolist-layout-<?php echo esc_attr( $settings['layout'] ); ?>">
	<?php foreach ( $settings['item'] as $i => $item ) { ?>
		<li class="xpro-infolist-item elementor-repeater-item-<?php echo esc_attr( $item['_id'] ); ?>">
			<?php
			$attr  = $item['link']['is_external'] ? ' target="_blank"' : '';
			$attr .= $item['link']['nofollow'] ? ' rel="nofollow"' : '';
			$attr .= $item['link']['url'] ? ' href="' . $item['link']['url'] . '"' : '';

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

			echo ( $item['link']['url'] ) ? '<a href="' . esc_url( $item['link']['url'] ) . '" ' . $attr . '>' : '';
			?>
			<?php if ( 'none' !== $item['media_type'] ) : ?>
				<div class="xpro-infolist-media xpro-infolist-media-type-<?php echo esc_attr( $item['media_type'] ); ?>">
					<?php
					if ( 'icon' === $item['media_type'] && $item['icon'] ) {
						Icons_Manager::render_icon( $item['icon'], array( 'aria-hidden' => 'true' ) );
					}

					if ( 'image' === $item['media_type'] && $item['image'] ) {
						echo wp_kses_post( Group_Control_Image_Size::get_attachment_image_html( $item, 'thumbnail', 'image' ) );
					}

					if ( 'custom' === $item['media_type'] && $item['custom'] ) {
						echo '<i class="xpro-infolist-custom">' . esc_html( $item['custom'] ) . '</i>';
					}
					?>
				</div>
			<?php endif; ?>

			<div class="xpro-infolist-content">
				<?php if ( $item['title'] ) : ?>
					<<?php echo esc_attr( $item['title_tag'] ); ?> class="xpro-infolist-title"><?php echo esc_html( $item['title'] ); ?></<?php echo esc_attr( $item['title_tag'] ); ?>>
				<?php endif; ?>
				<?php if ( $item['description'] ) : ?>
					<p class="xpro-infolist-desc"><?php echo wp_kses_post( $item['description'] ); ?></p>
				<?php endif; ?>
			</div>
			<?php echo ( $item['link']['url'] ) ? '</a>' : ''; ?>
		</li>
	<?php } ?>
</ul>
