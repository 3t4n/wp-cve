<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class MXMTZC_Shortcode
{

	/*
	* MXMTZC_Shortcode
	*/
	public function __construct()
	{

	}

	/*
	* Registration of styles and scripts
	*/
	public function mxmtzc_register_shortcode()
	{

		// register shortcode
		add_shortcode( 'mxmtzc_time_zone_clocks', array( $this, 'mxmtzc_time_zone_clocks_function' ) );

	}

		public function mxmtzc_time_zone_clocks_function( $atts ) {
			
			$time_zone = 'Europe/London';

			if( isset( $atts['time_zone'] ) ) {

				$time_zone = esc_attr( $atts['time_zone'] );

			}

			$city_name = '';

			if( isset( $atts['city_name'] ) ) {

				$city_name = html_entity_decode( esc_attr( $atts['city_name'] ) );

			}

			$time_format = 24;

			if( isset( $atts['time_format'] ) ) {

				if( $atts['time_format'] == 12 ) {

					$time_format = 12;

				}

			}

			$digital_clock = 'false';

			if( isset( $atts['digital_clock'] ) ) {

				if( $atts['digital_clock'] !== 'false' ) {

					$digital_clock = 'true';

				}	

			}

			$lang = 'en-US';

			if( isset( $atts['lang'] ) ) {

				$lang = esc_attr( $atts['lang'] );

			}

			$lang_for_date = 'en-US';

			if( isset( $atts['lang_for_date'] ) ) {

				$lang_for_date = esc_attr( $atts['lang_for_date'] );

			}

			$clean_str = str_replace( '/', '-', $time_zone );

			$class_of_clock = 'mx-clock-' . strtolower( $clean_str ) . rand( 0, 1000 );

			// show days
			$show_days = 'false';

			if( isset( $atts['show_days'] ) ) {

				if( $atts['show_days'] !== 'false' ) {

					$show_days = 'true';

				}

			}	

			// font size
			$clock_font_size = '';

			if( isset( $atts['clock_font_size'] ) ) {

				$clock_font_size = esc_attr( $atts['clock_font_size'] );

			}			

			// show seconds
			$show_seconds = 'true';

			if( isset( $atts['show_seconds'] ) ) {

				if( $atts['show_seconds'] == 'false' ) {

					$show_seconds = 'false';

				}

			}			

			// arrow type
			$arrow_type = 'classical';

			if( isset( $atts['arrow_type'] ) ) {

				$arrow_type = esc_attr( $atts['arrow_type'] );

			}

			// super simple clock
			$super_simple = 'false';

			if( isset( $atts['super_simple'] ) ) {

				if( $atts['super_simple'] == 'true' ) {

					$super_simple = 'true';

				}

			}

			// arrows color
			$arrows_color = 'unset';

			if( isset( $atts['arrows_color'] ) ) {

				$arrows_color = esc_attr( $atts['arrows_color'] );

			}

			// image
			$clock_type = 'clock-face2.png';

			if( isset( $atts['clock_type'] ) ) {

				$clock_type = esc_attr( $atts['clock_type'] );

			}

			// upload clock
			$clock_upload = 'false';

			if( isset( $atts['clock_upload'] ) ) {

				$clock_upload = esc_attr( $atts['clock_upload'] );

			}

			ob_start(); 

			?>

				<?php if( !$clock_font_size == '' ) : ?>

					<style>

						.<?php echo $class_of_clock; ?> * {
							font-size: <?php echo $clock_font_size . 'px !important';?>
						}
						
					</style>

				<?php endif; ?>

				<div class="mx-localize-time">

					<?php if( $clock_upload == 'false' ) : ?>
				
						<div class='<?php echo $class_of_clock; ?> mx-clock-live-el'
							data-bg-img-url='<?php echo MXMTZC_PLUGIN_URL; ?>includes/admin/assets/img/<?php echo $clock_type; ?>'
							data-time_zone='<?php echo $time_zone; ?>'
							data-city_name='<?php echo $city_name; ?>'
							data-date_format='<?php echo $time_format; ?>'
							data-digital_clock='<?php echo $digital_clock; ?>'
							data-lang='<?php echo $lang; ?>'
							data-lang_for_date='<?php echo $lang_for_date; ?>'
							data-show_days='<?php echo $show_days; ?>'
							data-showSecondHand='<?php echo $show_seconds; ?>'
							data-arrow_type='<?php echo $arrow_type; ?>'
							data-super_simple='<?php echo $super_simple; ?>'
							data-arrows_color='<?php echo $arrows_color; ?>'
						></div>

					<?php else : ?>

						<div class='<?php echo $class_of_clock; ?> mx-clock-live-el' 
							data-bg-img-url='<?php echo $clock_upload; ?>'
							data-time_zone='<?php echo $time_zone; ?>'
							data-city_name='<?php echo $city_name; ?>'
							data-date_format='<?php echo $time_format; ?>'
							data-digital_clock='<?php echo $digital_clock; ?>'
							data-lang='<?php echo $lang; ?>'
							data-lang_for_date='<?php echo $lang_for_date; ?>'
							data-show_days='<?php echo $show_days; ?>'
							data-showSecondHand='<?php echo $show_seconds; ?>'
							data-arrow_type='<?php echo $arrow_type; ?>'
							data-super_simple='<?php echo $super_simple; ?>'
							data-arrows_color='<?php echo $arrows_color; ?>'
						></div>

					<?php endif; ?>

				</div>

			<?php return ob_get_clean();

		}

}