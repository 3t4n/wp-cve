<?php
/**
 * @var string $item_hover_animation_class
 */

if ( ! empty( $settings ) ) :
	if ( $settings['items_list'] ) :
		$item_count = 0;
		foreach ( $settings['items_list'] as $item ) : ?>
			<?php
			$link_start = '<div class="owl-overlay">';
			$link_end   = '</div>';
			if ( ! empty( $item['item_url']['url'] ) ) {
				$link_html  = owce_get_item_link( $this, $item, $item_count, 'owl-overlay' );
				$link_start = $link_html[0] ?? '';
				$link_end   = $link_html[1] ?? '';
			}
			$item_count++;
			?>
			<div class="item carousel-item-<?php echo esc_attr( $item['_id'] . ' ' . $item_hover_animation_class ); ?> js-carousel-item">
				<?php
				echo wp_kses_post( $link_start );
				    require OWCE_PLUGIN_PATH . '/includes/widgets/views/thumbnail.php';
				echo wp_kses_post( $link_end );
				?>
				<div class="owl-team-footer">
					<?php
					require OWCE_PLUGIN_PATH . '/includes/widgets/views/title.php';
					require OWCE_PLUGIN_PATH . '/includes/widgets/views/subtitle.php';
					require OWCE_PLUGIN_PATH . '/includes/widgets/views/social.php';
					?>
				</div>
			</div>
		<?php
		endforeach;
	endif;
endif;
