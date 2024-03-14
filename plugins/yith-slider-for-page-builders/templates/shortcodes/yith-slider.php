<?php
/**
 * Slider shortcode template
 *
 * @package YITH Slider for page builders
 */

/**
 * Shortcode callback
 *
 * @param array $atts Shortcode atts.
 *
 * @return false|string
 * @author Francesco Grasso <francgrasso@yithemes.com>
 */
function yith_slider_for_page_builders_slider_sc( $atts ) {

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
		'numberposts' => - 1,
		'post_status' => 'publish',
		'orderby'     => 'meta_value_num',
		'order'       => 'ASC',
		'meta_key'    => 'slide_order', // phpcs:ignore
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
	$transition_type  = get_post_meta( $atts['slider'], 'yith_slider_control_animation_type', true ) === 'fade';
	$autoplay         = get_post_meta( $atts['slider'], 'yith_slider_control_autoplay', true ) === 'autoplay';
	$autoplay_timing  = get_post_meta( $atts['slider'], 'yith_slider_control_autoplay_timing', true );
	$infinite_sliding = get_post_meta( $atts['slider'], 'yith_slider_control_infinite_sliding', true ) === 'infinite-sliding';
	$slider_layout    = get_post_meta( $atts['slider'], 'yith_slider_control_slider_layout', true );
	$center_mode      = false;
	if ( '' === $slider_layout ) {
		$slider_layout = 'alignfull';
	}

	$arrow_nav       = get_post_meta( $atts['slider'], 'yith_slider_control_navigation_style', true );
	$arrow_nav_style = '';
	if ( '' === $arrow_nav || 'none' === $arrow_nav ) {
		$arrow_nav = false;
	} elseif ( 'prev_next_slides' === $arrow_nav ) {
		$arrow_nav   = false;
		$center_mode = true;
	} else {
		$arrow_nav_style = $arrow_nav;
		$arrow_nav       = true;
	}

	$dots_nav       = get_post_meta( $atts['slider'], 'yith_slider_control_dots_navigation_style', true );
	$dots_nav_style = '';
	if ( '' === $dots_nav || 'none' === $dots_nav ) {
		$dots_nav = false;
	} else {
		$dots_nav_style = $dots_nav;
		$dots_nav       = true;
	}

	$data_slick_options = array(
		'autoplay'      => $autoplay,
		'fade'          => $transition_type,
		'infinite'      => $infinite_sliding,
		'arrows'        => $arrow_nav,
		'prevArrow'     => '<button type="button" class="yith-slider-nav slide-prev ' . $arrow_nav_style . '">Previous</button>',
		'nextArrow'     => '<button type="button" class="yith-slider-nav slide-next ' . $arrow_nav_style . '">Next</button>',
		'centerMode'    => $center_mode,
		'dots'          => $dots_nav,
		'dotsClass'     => 'yith-slider-dots ' . $dots_nav_style,
		'autoplaySpeed' => $autoplay_timing,
		'rtl'           => is_rtl(),
	);

	$data_slick_options = wp_json_encode( $data_slick_options );
	$data_slick_attr    = _wp_specialchars( $data_slick_options, ENT_QUOTES, 'UTF-8', true );

	ob_start();
	?>
	<div data-slick="<?php echo $data_slick_attr; // phpcs:ignore WordPress.Security.EscapeOutput ?>" class="yith-slider <?php echo esc_attr( $slider_layout ); ?> yith-slider-<?php echo esc_attr( $atts['slider'] ); ?>" style="<?php echo esc_attr( $slider_style ); ?>">
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
	<?php if ( count( $slides ) > 1 ) : ?>
		<script>
			jQuery( function( $ ) {
					$('.yith-slider-<?php echo esc_attr( $atts['slider'] ); ?>').slick();
				});
		</script>
		<?php
	endif;

	return ob_get_clean();
}

add_shortcode( 'yith-slider', 'yith_slider_for_page_builders_slider_sc' );
