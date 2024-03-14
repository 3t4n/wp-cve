<?php

/**
 * Handler for post title block
 * @param $atts
 *
 * @return string
 */
function yith_slider_for_page_builders_block_handler( $atts ) {
	// Attributes.
	$atts = shortcode_atts(
		array(
			'slider' => '',
		),
		$atts
	);

	$slider_args  = array(
		'post_parent' => $atts['slider'],
		'post_type'   => 'yith_slide',
		'numberposts' => 1,
		'post_status' => 'publish',
		'orderby'     => 'meta_value_num',
		'order'       => 'ASC',
		'meta_key'    => 'slide_order',
	);
	$slides       = get_children( $slider_args );
	$slider_style = '';

	$slide_container_height = get_post_meta( $atts['slider'], 'yith_slider_control_heigth', true );
	if ( '' !== $slide_container_height ) {
		$slider_style .= 'height: ' . $slide_container_height . 'px; ';
	}

	if ( has_post_thumbnail( $atts['slider'] ) ) {
		$slide_bg      = get_the_post_thumbnail_url( $atts['slider'] );
		$slider_style .= 'background-image: url(\'' . $slide_bg . '\'); ';

		$slide_bg_position = get_post_meta( $atts['slider'], 'single_slide_background_position', true );
		if ( '' !== $slide_bg_position ) {
			$slider_style .= 'background-position: ' . $slide_bg_position . '; ';
		} else {
			$slider_style .= 'background-position: center; ';
		}

		$slide_bg_repeat = get_post_meta( $atts['slider'], 'single_slide_background_repeat', true );
		if ( '' !== $slide_bg_repeat ) {
			$slider_style .= 'background-repeat: ' . $slide_bg_repeat . '; ';
		} else {
			$slider_style .= 'background-repeat: no-repeat; ';
		}

		$slide_bg_size = get_post_meta( $atts['slider'], 'single_slide_background_size', true );
		if ( '' !== $slide_bg_size ) {
			$slider_style .= 'background-size: ' . $slide_bg_size . '; ';
		} else {
			$slider_style .= 'background-size: cover; ';
		}
	}

	$slide_bg_color = get_post_meta( $atts['slider'], 'single_slide_background_color', true );
	$slider_style  .= 'background-color: ' . $slide_bg_color . '; ';

	// Slider option.
	$slider_layout = get_post_meta( $atts['slider'], 'yith_slider_control_slider_layout', true );
	if ( '' === $slider_layout ) {
		$slider_layout = 'alignfull';
	}

	ob_start();
	?>
	<div class="yith-slider <?php echo esc_attr( $slider_layout ); ?> yith-slider-<?php echo esc_attr( $atts['slider'] ); ?>" style="<?php echo esc_attr( $slider_style ); ?>">
		<?php
		foreach ( $slides as $slide ) :
			$slide_id = $slide->ID;
			$style    = 'style="';
			if ( has_post_thumbnail( $slide_id ) ) {
				$slide_bg = get_the_post_thumbnail_url( $slide_id );
				$style   .= 'background-image: url(\'' . $slide_bg . '\'); ';

				$slide_bg_position = get_post_meta( $slide_id, 'single_slide_background_position', true );
				if ( '' !== $slide_bg_position ) {
					$style .= 'background-position: ' . $slide_bg_position . '; ';
				} else {
					$style .= 'background-position: center; ';
				}

				$slide_bg_repeat = get_post_meta( $slide_id, 'single_slide_background_repeat', true );
				if ( '' !== $slide_bg_repeat ) {
					$style .= 'background-repeat: ' . $slide_bg_repeat . '; ';
				} else {
					$style .= 'background-repeat: no-repeat; ';
				}

				$slide_bg_size = get_post_meta( $slide_id, 'single_slide_background_size', true );
				if ( '' !== $slide_bg_size ) {
					$style .= 'background-size: ' . $slide_bg_size . '; ';
				} else {
					$style .= 'background-size: cover; ';
				}
			}

			$slide_bg_color = get_post_meta( $slide_id, 'single_slide_background_color', true );
			$style         .= 'background-color: ' . $slide_bg_color . '; ';

			if ( 1 === count( $slides ) ) {
				$style .= 'display: block; float: none; ';
			}

			$style .= '"';

			$slide_container_max_width = get_post_meta( $atts['slider'], 'yith_slider_control_container_max_width', true );
			if ( '' !== $slide_container_max_width ) {
				$slide_container_max_width = '<style type="text/css"> .slide-id-' . esc_attr( $slide_id ) . ' .slide-container { max-width: ' . $slide_container_max_width . 'px;}</style>';

			}
			?>
			<div class="yith-slider-slide slide-id-<?php echo esc_attr( $slide_id ); ?> slick-slide" <?php echo $style; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
				<?php echo $slide_container_max_width; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<div class="slide-container">
					<?php echo do_shortcode( do_blocks( $slide->post_content ) ); ?>
				</div>
			</div>
			<?php
		endforeach;
		?>
	</div>
	<?php

	return ob_get_clean();
}

add_action( 'init', 'yith_slider_for_page_builders_register_block' );
/**
 * Register block
 *
 * @return void
 */
function yith_slider_for_page_builders_register_block() {
	// Skip block registration if Gutenberg is not enabled/merged.
	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}

	$index_js = 'index.js';
	wp_register_script(
		'yith-slider-for-page-builders-block-script',
		plugins_url( $index_js, __FILE__ ),
		array(
			'wp-blocks',
			'wp-i18n',
			'wp-element',
			'wp-components',
		),
		YITH_SLIDER_FOR_PAGE_BUILDERS_VERSION,
		true
	);

	$localize = array(
		'slidersArray' => yith_slider_for_page_builders_get_sliders_list_array(),
	);

	wp_localize_script( 'yith-slider-for-page-builders-block-script', 'yith_slider_for_page_builders_block_localized_array', $localize );

	register_block_type(
		'yith-slider-for-page-builders/slider-block',
		array(
			'editor_script'   => 'yith-slider-for-page-builders-block-script',
			'render_callback' => 'yith_slider_for_page_builders_block_handler',
			'attributes'      => array(
				'slider' => array(
					'default' => '',
					'type'    => 'string',
				),
			),
		)
	);
}
