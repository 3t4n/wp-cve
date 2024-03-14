<?php
namespace GSLOGO;

/**
 * GS Logo Slider - Slider Layout 1
 * @author GS Plugins <hello@gsplugins.com>
 * 
 * This template can be overridden by copying it to yourtheme/gs-logo/gs-logo-theme-slider-1.php
 * 
 * @package GS_Logo_Slider/Templates
 * @version 1.0.0
 */

$options = array(
	'direction' 		=> 'horizontal', // Force Value
	'speed' 			=> (int) $speed,
	'isAutoplay' 		=> gs_validate_boolean( $gs_l_is_autop ),
	'autoplayDelay' 	=> (int) $gs_l_autop_pause,
	'loop' 				=> gs_validate_boolean( $inf_loop ),
	'pauseOnHover' 		=> gs_validate_boolean( $gs_l_slider_stop ),
	'ticker' 			=> false, // Force Value
	'navs' 				=> gs_validate_boolean( $gs_l_ctrl ),
	'navs_pos' 			=> sanitize_key( $gs_l_ctrl_pos ),
	'dots' 				=> gs_validate_boolean( $gs_l_pagi ),
	'dynamic_dots' 		=> gs_validate_boolean( $gs_l_pagi_dynamic ),
	'slideSpace' 		=> (int) $gs_l_margin,
	'slidesPerGroup' 	=> (int) $gs_l_move_logo,
	'desktopLogos'      => (int) $gs_l_min_logo,
	'tabletLogos'      	=> (int) $gs_l_tab_logo,
	'mobileLogos'      	=> (int) $gs_l_mob_logo,
	'reverseDirection'  => gs_validate_boolean( $gs_reverse_direction ),
);

$options = json_encode($options, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);

global $gs_logo_loop;

?>

<div class="gs_logo_container gs_carousel_swiper gs_logo_fix_height_and_center" data-carousel-config='<?php echo $options; // already sanitized ?>'>

	<?php if ( $gs_logo_loop->have_posts() ) : ?>

		<?php while ( $gs_logo_loop->have_posts() ) : $gs_logo_loop->the_post(); ?>

			<div class="gs_logo_single--wrapper">
				<div class="gs_logo_single">

					<!-- Logo Img -->
					<?php include Template_Loader::locate_template( 'partials/gs-logo-layout-image.php' ); ?>

					<!-- Logo Title -->
					<?php include Template_Loader::locate_template( 'partials/gs-logo-layout-title.php' ); ?>
					
					<!-- Logo Category -->
					<?php include Template_Loader::locate_template( 'partials/gs-logo-layout-cat.php' ); ?>
					
				</div>
			</div>

		<?php endwhile; ?>
		
	<?php else: ?>

		<?php include Template_Loader::locate_template( 'partials/gs-logo-empty.php' ); ?>
		
	<?php endif; ?>

</div>