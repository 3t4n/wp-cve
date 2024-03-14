<?php
/**
 * Responsive Slider Shortcode
 *
 * @access    public
 *
 * @return    Create Fontend Slider Gallery Output
 */
add_shortcode( 'responsive-slider', 'responsive_slider_shortcode' );
function responsive_slider_shortcode( $post_id ) {
	wp_enqueue_script( 'awl-fotorama-js' );
	wp_enqueue_style( 'awl-fotorama-css' );
	ob_start();
	$allslides      = array(
		'p'         => $post_id['id'],
		'post_type' => 'responsive_slider',
	);
	$allslides_loop = new WP_Query( $allslides );
	while ( $allslides_loop->have_posts() ) :
		$allslides_loop->the_post();
		$post_id         = get_the_ID();
		$allslidesetting = unserialize( base64_decode( get_post_meta( $post_id, 'awl_slider_settings_' . $post_id, true ) ) );
		// start the sider contents
		if ( isset( $allslidesetting['width'] ) ) {
			$width = $allslidesetting['width'];
		} else {
			$width = '100%';
		}
		if ( isset( $allslidesetting['height'] ) ) {
			$height = $allslidesetting['height'];
		} else {
			$height = '';
		}
		if ( isset( $allslidesetting['nav-style'] ) ) {
			$navstyle = $allslidesetting['nav-style'];
		} else {
			$navstyle = 'dots';
		}
		if ( isset( $allslidesetting['nav-width'] ) ) {
			$navwidth = $allslidesetting['nav-width'];
		} else {
			$navwidth = '';
		}
		if ( isset( $allslidesetting['fullscreen'] ) ) {
			$fullscreen = $allslidesetting['fullscreen'];
		} else {
			$fullscreen = 'true';
		}
		if ( isset( $allslidesetting['fit-slides'] ) ) {
			$fitslides = $allslidesetting['fit-slides'];
		} else {
			$fitslides = 'cover';
		}
		if ( isset( $allslidesetting['transition-duration'] ) ) {
			$transitionduration = $allslidesetting['transition-duration'];
		} else {
			$transitionduration = '300';
		}
		if ( isset( $allslidesetting['slide-text'] ) ) {
			$slidetext = $allslidesetting['slide-text'];
		} else {
			$slidetext = 'false';
		}
		if ( isset( $allslidesetting['autoplay'] ) ) {
			$autoplay = $allslidesetting['autoplay'];
		} else {
			$autoplay = 'true';
		}
		if ( isset( $allslidesetting['loop'] ) ) {
			$loop = $allslidesetting['loop'];
		} else {
			$loop = 'true';
		}
		if ( isset( $allslidesetting['nav-arrow'] ) ) {
			$navarrow = $allslidesetting['nav-arrow'];
		} else {
			$navarrow = 'true';
		}
		if ( isset( $allslidesetting['touch-slide'] ) ) {
			$touchslide = $allslidesetting['touch-slide'];
		} else {
			$touchslide = 'true';
		}
		if ( isset( $allslidesetting['spinner'] ) ) {
			$spinner = $allslidesetting['spinner'];
		} else {
			$spinner = 'true';
		}
		?>
		<div class="fotorama responsive-image-silder" 
			data-width="<?php echo esc_html( $width ); ?>" 
			data-height="<?php echo esc_html( $height ); ?>" 
			data-nav="<?php echo esc_html( $navstyle ); ?>"
			data-navwidth="<?php echo esc_html( $navwidth ); ?>"		
			data-allowfullscreen="<?php echo esc_html( $fullscreen ); ?>" 
			data-fit="<?php echo esc_html( $fitslides ); ?>" 
			data-transitionduration="<?php echo esc_html( $transitionduration ); ?>" 
			data-autoplay="<?php echo esc_html( $autoplay ); ?>" 
			data-loop="<?php echo esc_html( $loop ); ?>" 
			data-arrows="<?php echo esc_html( $navarrow ); ?>" 
			data-swipe="<?php echo esc_html( $touchslide ); ?>" 
			data-spinner="<?php echo esc_html( $spinner ); ?>" 
			data-transition="slide" 
		>
			<?php
			if ( isset( $allslidesetting['slide-ids'] ) && count( $allslidesetting['slide-ids'] ) > 0 ) {
				foreach ( $allslidesetting['slide-ids'] as $attachment_id ) {
					$thumb         = wp_get_attachment_image_src( $attachment_id, 'thumb', true );
					$thumbnail     = wp_get_attachment_image_src( $attachment_id, 'thumbnail', true );
					$medium        = wp_get_attachment_image_src( $attachment_id, 'medium', true );
					$large         = wp_get_attachment_image_src( $attachment_id, 'large', true );
					$postthumbnail = wp_get_attachment_image_src( $attachment_id, 'post-thumbnail', true );

					$attachment_details = get_post( $attachment_id );
						$href           = get_permalink( $attachment_details->ID );
						$src            = $attachment_details->guid;
						$title          = $attachment_details->post_title;
					if ( $slidetext == 'true' ) {
						$text = $title;
					} else {
						$text = '';
					}
					?>
					<img src="<?php echo esc_url( $thumb[0] ); ?>" data-caption="<?php echo esc_html( $text ); ?>">
										 <?php
				}// end of attachment foreach
			} else {

				_e( 'Sorry! No slides added to the slider shortcode yet. Please add few slide into shortcode', 'responsive-slider-gallery' );
			} // end of if esle of slides avaialble check into slider
			?>
		</div>
		<?php
	endwhile;
	wp_reset_query();
	return ob_get_clean();
	?>
	<!-- HTML Script Part Start From Here-->
	<script>
	jQuery(function () {
	  jQuery('.responsive-image-silder').fotorama({
		  spinner: {
			lines: 13,
			color: 'rgba(0, 0, 0, .75)',
			className: 'fotorama',
		  }		  
	  });
	});
	</script>
	<?php
}
?>
