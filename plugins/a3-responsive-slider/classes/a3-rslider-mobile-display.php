<?php
namespace A3Rev\RSlider;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Mobile_Display
{
	public static function mobile_dispay_slider( $slide_items = array(), $is_used_mobile_skin = false , $slider_settings = array() ) {

		$device_detect = new Mobile_Detect();

		$slider_template = 'template-mobile';
		$templateid = 'template_mobile';

		// Return empty if it does not have any slides
		if ( ! is_array( $slide_items ) || count( $slide_items ) < 1 ) return '';

		$is_enable_progressive = 1;
		$z_index = '';

		extract( $slider_settings );

		$caption_fx_out = 'fadeOut';
		$caption_fx_in = 'fadeIn';

		$unique_id = rand( 100, 1000 );

		$overlay_class = '#cycle-template-mobile-overlay-' . $unique_id;

		$slider_transition_data 		= Functions::get_slider_transition( $slider_transition_effect, $slider_settings );
		$fx 							= 'scrollHorz';
		$timeout 						= $slider_transition_data['timeout'];
		$speed 							= $slider_transition_data['speed'];

		if ( $support_youtube_videos == 1 ) {
			$timeout 						= $yt_slider_timeout*1000;
			$speed 							= $yt_slider_speed*1000;
			$delay 							= 0;
		}

		ob_start();
		$exclude_lazyload = 'a3-notlazy';
		$lazy_load        = '';
		$lazy_hidden      = '';
		if ( ! is_admin() && function_exists( 'a3_lazy_load_enable' ) && ! class_exists( 'A3_Portfolio' ) && ! class_exists( '\A3Rev\Portfolio' ) ) {
			$exclude_lazyload = '';
			$lazy_load        = '-lazyload';
			$lazy_hidden      = '<div class="a3-cycle-lazy-hidden lazy-hidden"></div>';
		}
	?>
    <div class="a3-slider-card-container-mobile a3-slider-card-container-basic-mobile-skin " style="<?php if ( '' !== trim( $z_index ) ) echo "z-index:$z_index !important;"; ?>">

    <div id="a3-rslider-container-<?php echo $unique_id; ?>" class="a3-rslider-container a3-rslider-<?php echo $slider_template; ?>" slider-id="<?php echo $unique_id; ?>" is-responsive="1" is-tall-dynamic="0" >
    	<?php echo $lazy_hidden;?>
    	<div style="height:150px" id="a3-cycle-slideshow-<?php echo $unique_id; ?>" class="cycle-slideshow<?php echo $lazy_load;?> a3-cycle-slideshow"
        	data-cycle-fx="<?php echo $fx; ?>"
            data-cycle-paused=true
            data-cycle-auto-height=container

            data-cycle-center-horz=true

            data-cycle-swipe=true

            data-cycle-caption="> .cycle-caption-container .cycle-caption"
            data-cycle-caption-template="{{slideNum}} / <?php echo count( $slide_items); ?>"
            data-cycle-caption-plugin="caption2"

            data-cycle-loader=true

            <?php if ( 1 == $is_enable_progressive ) { ?>
            data-enable-progressive="1"
            data-cycle-progressive="#a3-slider-progressive-<?php echo $unique_id; ?>"
            <?php } ?>
        >

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

        	<div class="cycle-caption-container">
            	<div class="cycle-caption-inside">
            		<div class="cycle-caption-overlay"></div>
                	<div class="cycle-caption"></div>
                </div>
            </div>
		<?php
			$total_item          = 0;
			$have_caption        = false;
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
					if ( trim( $item->img_link ) != '' ) {
						if ( stristr( $item->img_link, 'http' ) === FALSE && stristr( $item->img_link, 'https' ) === FALSE )
							$item->img_link = 'http://' . $item->img_link;
						$img_title = '<div class="cycle-title"><a target="'.$open_type.'" href="'. trim( $item->img_link ) .'">'. trim( stripslashes( $item->img_title ) ) .'</a></div>';
					} else {
						$img_title = '<div class="cycle-title">'.trim( stripslashes( $item->img_title ) ).'</div>';
					}
				}

				$img_description = '';
				if ( trim( $item->img_description ) != '' ) {
					$have_caption = true;
					$img_description = '<div class="cycle-description">' . Functions::limit_words( stripslashes( $item->img_description ), $caption_lenght, '...' ) . '</div>';
				}

				$image_click = '';
            	if ( trim( $item->img_link ) != '' ) {
            		if ( 1 == $item->open_newtab ) {
						$image_click = ' onclick="window.open(\''.esc_attr( trim( $item->img_link ) ).'\', \'_blank\');" ';
					} else {
						$image_click = ' onclick="window.location=\''.esc_attr( trim( $item->img_link ) ).'\';" ';
					}
				}
		?>
                <img class="a3-rslider-image <?php echo $exclude_lazyload; ?> <?php if ( trim( $item->img_link ) != '' ) { echo 'a3-rslider-image-url'; } ?>" <?php echo $image_click; ?> src="<?php echo esc_url( $item->img_url ); ?>" title="" alt="<?php echo trim( stripslashes( $item->img_alt) ); ?>" style="position:absolute; visibility:hidden; top:0; left:0;" />

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
    </div>

    </div>

    <?php
		$slider_output = ob_get_clean();

		$slider_output = str_replace( array("\r\n", "\r", "\n"), '', $slider_output );

		$script_settings = array(
			'fx'       => 'scrollHorz',
			'caption2' => $have_caption,
			'swipe'    => true,
			'video'    => false,
    	);
    	Hook_Filter::enqueue_frontend_script( $script_settings );
    	$slider_output = apply_filters( 'a3_lazy_load_images', $slider_output, false );

		return $slider_output;

	}
}
