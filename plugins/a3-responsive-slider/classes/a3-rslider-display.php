<?php
namespace A3Rev\RSlider;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Display
{
	public static function a3_responsive_slider( $slider_id = 0 ) {

		$slider_data = get_post( $slider_id );
		if ( $slider_data == NULL ) return '';

		$have_slider_id = get_post_meta( $slider_id, '_a3_slider_id' , true );
		if ( $have_slider_id < 1 ) return '';


		$slider_settings =  get_post_meta( $slider_id, '_a3_slider_settings', true );
		$slider_template = get_post_meta( $slider_id, '_a3_slider_template' , true );

		$slide_items = Data::get_all_images_from_slider_client( $slider_id );

		$templateid = 'template1';

		$slider_template = 'template-1';

		global ${'a3_rslider_'.$templateid.'_dimensions_settings'}; // @codingStandardsIgnoreLine // phpcs:ignore

		$dimensions_settings = ${'a3_rslider_'.$templateid.'_dimensions_settings'};

		return self::dispay_slider( $slide_items, $slider_template, $dimensions_settings, $slider_settings );

	}

	public static function dispay_slider( $slide_items = array(), $slider_template= 'template-1', $dimensions_settings = array() , $slider_settings = array(), $rslider_custom_style = '', $rslider_inline_style = '', $description_html = '' ) {
		global $a3_rslider_template1_global_settings;

		$templateid = 'template1';

		$slider_template = 'template-1';

		global ${'a3_rslider_'.$templateid.'_dimensions_settings'}; // @codingStandardsIgnoreLine // phpcs:ignore
		global ${'a3_rslider_'.$templateid.'_title_settings'}; // @codingStandardsIgnoreLine // phpcs:ignore
		global ${'a3_rslider_'.$templateid.'_caption_settings'}; // @codingStandardsIgnoreLine // phpcs:ignore
		global ${'a3_rslider_'.$templateid.'_readmore_settings'}; // @codingStandardsIgnoreLine // phpcs:ignore

		// Detect the slider is viewing on Mobile, if True then Show Slider for Mobile
		$device_detect = new Mobile_Detect();
		if ( $device_detect->isMobile() ) {
			$is_used_mobile_skin = false;

			return Mobile_Display::mobile_dispay_slider( $slide_items, $is_used_mobile_skin , $slider_settings );
		}

		// TEST MOBILE
		//$is_used_mobile_skin = false;
		//if ( ${'a3_rslider_'.$templateid.'_global_settings'}['is_used_mobile_skin'] == 1 ) $is_used_mobile_skin = true;
		//return Mobile_Display::mobile_dispay_slider( $slide_items, $is_used_mobile_skin , $slider_settings );

		if ( is_array( $dimensions_settings ) && count( $dimensions_settings ) > 0 ) {
			extract( $dimensions_settings );
		} else {
			extract( ${'a3_rslider_'.$templateid.'_dimensions_settings'} );
		}
		extract( ${'a3_rslider_'.$templateid.'_title_settings'} );
		extract( ${'a3_rslider_'.$templateid.'_caption_settings'} );
		extract( ${'a3_rslider_'.$templateid.'_readmore_settings'} );

		// Return empty if it does not have any slides
		if ( ! is_array( $slide_items ) || count( $slide_items ) < 1 ) return '';

		$is_enable_progressive = 1;
		$z_index = '';
		
		extract( $slider_settings );

		$caption_class = '> .cycle-caption-title .cycle-caption';
		$overlay_class = '> .cycle-caption-title .cycle-overlay';
		$caption_fx_out = 'fadeOut';
		$caption_fx_in = 'fadeIn';

		$unique_id = rand( 100, 1000 );

		// Find max height and width of max height for set all images
		$max_height = 0;
		$width_of_max_height = 0;

		$slider_transition_data 		= Functions::get_slider_transition( $slider_transition_effect, $slider_settings );
		$fx 							= $slider_transition_data['fx'];
		$transition_attributes 			= $slider_transition_data['transition_attributes'];
		$timeout 						= $slider_transition_data['timeout'];
		$speed 							= $slider_transition_data['speed'];
		$delay 							= $slider_transition_data['delay'];

		$dynamic_tall = 'false';
		if ( $is_slider_tall_dynamic == 1 ) $dynamic_tall = 'container';

		$have_image_title = false;
		$have_image_caption = false;

		ob_start();
	?>
	<?php
	$exclude_lazyload = 'a3-notlazy';
	$lazy_load = '';
	$lazy_hidden = '';
	if ( ! is_admin() && function_exists( 'a3_lazy_load_enable' ) && ! class_exists( 'A3_Portfolio' ) && ! class_exists( '\A3Rev\Portfolio' ) ) {
		$exclude_lazyload = '';
		$lazy_load = '-lazyload';
		$lazy_hidden = '<div class="a3-cycle-lazy-hidden lazy-hidden"></div>';
	}
	?>
    <div id="a3-rslider-container-<?php echo $unique_id; ?>" class="a3-rslider-container a3-rslider-<?php echo $slider_template; ?>" slider-id="<?php echo $unique_id; ?>" max-height="<?php echo $max_height; ?>" width-of-max-height="<?php echo $width_of_max_height; ?>" is-responsive="<?php echo $is_slider_responsive; ?>" is-tall-dynamic="<?php echo $is_slider_tall_dynamic; ?>" style="<?php if ( '' !== trim( $z_index ) ) echo "z-index:$z_index !important;"; ?> <?php echo $rslider_custom_style; ?>" >
    	<?php echo $lazy_hidden;?>
    	<div style=" <?php echo $rslider_inline_style; ?>" id="a3-cycle-slideshow-<?php echo $unique_id; ?>" class="cycle-slideshow<?php echo $lazy_load;?> a3-cycle-slideshow <?php if ( $is_slider_tall_dynamic == 1 ) { ?>a3-cycle-slideshow-dynamic-tall<?php } ?>"
        	data-cycle-fx="<?php echo $fx; ?>"
            <?php echo $transition_attributes; ?>

        	data-cycle-timeout=<?php echo $timeout; ?>
            data-cycle-speed=<?php echo $speed; ?>
            data-cycle-delay=<?php echo $delay; ?>
            data-cycle-swipe=true

            data-cycle-prev="> .a3-cycle-controls .cycle-prev"
            data-cycle-next="> .a3-cycle-controls .cycle-next"
            data-cycle-pager="> .cycle-pager-container .cycle-pager-inside .cycle-pager"

            <?php if ( 0 == $is_slider_tall_dynamic ) { ?>
            data-cycle-center-vert=true
            <?php  } ?>
            data-cycle-auto-height=<?php echo $dynamic_tall; ?>
    		data-cycle-center-horz=true

            data-cycle-caption="<?php echo $caption_class; ?>"
            data-cycle-caption-template="{{name}}"
            data-cycle-caption-plugin="caption2"
            data-cycle-caption-fx-out="<?php echo $caption_fx_out; ?>"
            data-cycle-caption-fx-in="<?php echo $caption_fx_in; ?>"

            data-cycle-overlay="<?php echo $overlay_class; ?>"
			data-cycle-overlay-fx-out="<?php echo $caption_fx_out; ?>"
			data-cycle-overlay-fx-in="<?php echo $caption_fx_in; ?>"

            data-cycle-loader=true

            <?php if ( 1 == $is_enable_progressive ) { ?>
            data-enable-progressive="1"
            data-cycle-progressive="#a3-slider-progressive-<?php echo $unique_id; ?>"
            <?php } ?>
        >

			<?php if ( $is_slider_tall_dynamic == 1 ) { ?>
				<?php foreach ( $slide_items as $item ) { ?>
	        		<?php if ( $item->is_video != 1 ) { ?>
						<?php
							$first_img = $item->img_url;
							if ( false === stristr( $first_img, 'http' ) ) {
								$first_img = is_ssl() ? str_replace( '//', 'https://', $first_img ) : str_replace( '//', 'http://', $first_img ) ;
							}
							$_size = version_compare( get_bloginfo( 'version' ), '5.7', '>=' ) ? wp_getimagesize( $first_img ) : @getimagesize( $first_img );
						?>
			        	<div class="cycle-sentinel"><img class="cycle-sentinel" style="width:<?php echo $_size[0]; ?>px; max-height:<?php echo $_size[1]; ?>px;" src="<?php echo esc_url( $item->img_url ); ?>"></div>
						<?php break; ?>
					<?php } ?>
				<?php } ?>
			<?php } ?>

        	<div class="a3-cycle-controls <?php if ( $support_youtube_videos == 1 ) { ?>a3-cycle-video-controls<?php } ?>" style="display: none;">
            	<span class="cycle-prev-control"><span class="cycle-prev"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M41.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.3 256 246.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z"/></svg></span></span>
                <span class="cycle-next-control"><span class="cycle-next"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M278.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-160 160c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L210.7 256 73.4 118.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l160 160z"/></svg></span></span>
            </div>

            <div class="a3-cycle-pauseplay">
                <span class="cycle-pause-control"><span class="cycle-pause" data-cycle-cmd="pause" data-cycle-context="#a3-cycle-slideshow-<?php echo $unique_id; ?>" onclick="return false;" style=" <?php if ( $is_auto_start == 0 ) { echo 'display:none'; } ?>"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M48 64C21.5 64 0 85.5 0 112V400c0 26.5 21.5 48 48 48H80c26.5 0 48-21.5 48-48V112c0-26.5-21.5-48-48-48H48zm192 0c-26.5 0-48 21.5-48 48V400c0 26.5 21.5 48 48 48h32c26.5 0 48-21.5 48-48V112c0-26.5-21.5-48-48-48H240z"/></svg></span></span>
                <span class="cycle-play-control"><span class="cycle-play" data-cycle-cmd="resume" data-cycle-context="#a3-cycle-slideshow-<?php echo $unique_id; ?>" onclick="return false;" style=" <?php if ( $is_auto_start != 0 ) { echo 'display:none'; } ?>"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path d="M73 39c-14.8-9.1-33.4-9.4-48.5-.9S0 62.6 0 80V432c0 17.4 9.4 33.4 24.5 41.9s33.7 8.1 48.5-.9L361 297c14.3-8.7 23-24.2 23-41s-8.7-32.2-23-41L73 39z"/></svg></span></span>
            </div>

            <?php
			// NOT FOR WIDGET & CARD TEMPLATE
			self::get_caption_title();
			?>

        	<div class="cycle-pager-container" style="display: none;">
            	<div class="cycle-pager-inside">
            		<div class="cycle-pager-overlay"></div>
                	<div class="cycle-pager"></div>
                </div>
            </div>

		<?php
			$total_item          = 0;
			$add_progressive_tag = false;
		?>
		<?php foreach ( $slide_items as $item ) { ?>
		<?php
				if ( $item->is_video == 1 ) continue;
				if ( trim( $item->img_url ) == '' ) continue;

				$total_item++;

				if ( 1 == $is_enable_progressive && $total_item > 2 ) {
					echo '---';
				}

				$img_title = '';
				$open_type = '';
				if ( 1 == $item->open_newtab ) {
					$open_type = '_blank';
				}
				if ( trim( $item->img_title ) != '' ) {
					$have_image_title = true;
					if ( trim( $item->img_link ) != '' ) {
						if ( stristr( $item->img_link, 'http' ) === FALSE && stristr( $item->img_link, 'https' ) === FALSE )
							$item->img_link = 'http://' . $item->img_link;
						$img_title = '<div class="cycle-caption-text"><a target="'.$open_type.'" href="'. trim( $item->img_link ) .'">'. trim( stripslashes( $item->img_title ) ) .'</a></div>';
					} else {
						$img_title = '<div class="cycle-caption-text">'.trim( stripslashes( $item->img_title ) ).'</div>';
					}
				}

				$read_more = '';
				if ( trim( $item->img_link ) != '' && $item->show_readmore == 1 ) {
					$read_more_class = 'a3-rslider-read-more-link';
					$read_more_text = $readmore_link_text;
					if ( $readmore_bt_type == 'button' ) {
						$read_more_class = 'a3-rslider-read-more-bt';
						$read_more_text = $readmore_bt_text;
					}
					$read_more_class = 'a3-rslider-read-more '. $read_more_class ;
					$read_more = esc_attr( '<a target="'.$open_type.'" class="'.$read_more_class.'" href="'. trim( $item->img_link ). '">' . $read_more_text . '</a>' );
				}

				$img_description = '';
				if ( trim( $item->img_description ) != '' ) {
					$have_image_caption = true;
					$img_description = '<div class="cycle-description">' . Functions::limit_words( stripslashes( $item->img_description ), $caption_lenght, '...' ) . ' '. $read_more . '</div>';
				}
		?>

        	<img class="a3-rslider-image <?php echo $exclude_lazyload; ?> <?php if ( trim( $item->img_link ) != '' ) { echo 'a3-rslider-image-url'; } ?>" src="<?php echo esc_url( $item->img_url ); ?>" name="<?php echo esc_attr( $img_title ); ?>" title="" data-cycle-desc="<?php echo esc_attr( $img_description ); ?>" alt="<?php echo trim( stripslashes( $item->img_alt ) ); ?>"
            style="position:absolute; visibility:hidden; top:0; left:0;"
            <?php
				if ( $fx == 'random' ) {
					echo Functions::get_transition_random( $slider_settings );
				}

				if ( trim( $item->img_link ) != '' ) {
					if ( 1 == $item->open_newtab ) {
						echo ' onclick="window.open(\''.esc_attr( trim( $item->img_link ) ).'\', \'_blank\');" ';
					} else {
						echo ' onclick="window.location=\''.esc_attr( trim( $item->img_link ) ).'\';" ';
					}
				}
			?>
            />

            <?php
            	if ( 1 == $is_enable_progressive && $total_item > 0 && ! $add_progressive_tag ) {
					$add_progressive_tag = true;
					$exclude_lazyload = 'a3-notlazy';
					echo '<script id="a3-slider-progressive-'.$unique_id.'" type="text/cycle" data-cycle-split="---">';
				}
            ?>

        <?php } ?>

        <?php if ( 1 == $is_enable_progressive && $add_progressive_tag ) { echo '</script>'; } ?>
        
        </div>

        <?php echo $description_html; ?>

    </div>

	<?php
    	if ( $total_item < 2 ) {
    ?>
	<style type="text/css">
	#a3-rslider-container-<?php echo $unique_id; ?> .a3-cycle-controls,
	#a3-rslider-container-<?php echo $unique_id; ?> .cycle-pager-container {
		display: none !important;
	}
	</style>
	<?php } ?>
    <?php
		$slider_output = ob_get_clean();

		$slider_output = str_replace( array("\r\n", "\r", "\n"), '', $slider_output );

		$script_settings = array(
			'fx'       => $fx,
			'caption2' => $have_image_caption,
			'swipe'    => true,
			'video'    => false,
    	);
    	Hook_Filter::enqueue_frontend_script( $script_settings );
    	$slider_output = apply_filters( 'a3_lazy_load_images', $slider_output, false );

		return $slider_output;

	}

	public static function get_caption_title() {
	?>
    		<div class="cycle-caption-title">
				<div class="cycle-caption-container">
                	<div class="cycle-caption-bg"></div>
                    <div class="cycle-caption"></div>
                </div>
                <div class="cycle-overlay-container">
                	<div class="cycle-overlay-bg"></div>
					<div class="cycle-overlay"></div>
                </div>
        	</div>
    <?php
	}

}
