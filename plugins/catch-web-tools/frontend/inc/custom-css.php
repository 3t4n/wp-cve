<?php
/**
 * @package Frontend
 * @sub-package Custom Css
 */

/**
 * Get the custom css setting and format catchwebtools_custom_css_display
 * @return [string] [custom css information]
 */
function catchwebtools_custom_css_display(){
	//delete_transient( 'catchwebtools_custom_css' );
	if ( ! $output = get_transient( 'catchwebtools_custom_css' ) ) {
		$output ='';

		//For Social Icons
		$social_settings = catchwebtools_get_options( 'catchwebtools_social' );

		if ( $social_settings['status'] ) {
			//Add font size with ::before to add prioirty
			if ( '' != $social_settings['social_icon_size'] ) {
				$output .= '.catchwebtools-social .genericon::before { font-size : '. esc_attr( $social_settings['social_icon_size'] ) .'px }'. PHP_EOL;
			}

			$social_brand_color = $social_settings['social_icon_brand_color'];

            if( ! ( 'hover' == $social_brand_color || 'hover-static' == $social_brand_color ) ) {
                //Add hover color if it exists
                if ( '' != $social_settings['social_icon_hover_color'] ) {
                    $output .= '.catchwebtools-social .genericon:hover { color :  '. esc_attr( $social_settings['social_icon_hover_color'] ) .'; text-decoration: none; }'. PHP_EOL;
                }
            }

			if ( '' != $social_settings['social_icon_size'] || ( ( 'hover-static' != $social_brand_color ) && ( '' != $social_settings['social_icon_color'] ) ) ) {
					$output .=  '.catchwebtools-social .genericon { '. PHP_EOL;
			}

			if ( '' != $social_settings['social_icon_size'] ) {
                $output .= 'width : '. esc_attr( $social_settings['social_icon_size'] ).'px; height : '. esc_attr( $social_settings['social_icon_size'] ).'px;'. PHP_EOL;
            }

			if( 'hover-static' != $social_brand_color ) {
                if ( '' != $social_settings['social_icon_color'] ) {
				   $output .= ' color : '. esc_attr( $social_settings['social_icon_color'] ) .';'. PHP_EOL;
			    }
            }

            if ( '' != $social_settings['social_icon_size'] || ( ( 'hover-static' != $social_brand_color ) && ( '' != $social_settings['social_icon_color'] ) ) ) {
					$output .=  '}'. PHP_EOL;
			}

			if ( '' != $output ) {
				$output = PHP_EOL . '/* CWT Social Icons Custom CSS */' . PHP_EOL . $output . PHP_EOL ;
			}
		}

		/**
		 * catchwebtools: catch_web_tools_webmaster_page
		 * Catch Web Tools Webmaster Display Function
		 */
		if ( !function_exists( 'wp_update_custom_css_post' ) ) {
			$custom_css	=	catchwebtools_get_options( 'catchwebtools_custom_css' );

			if( !empty ( $custom_css ) ){
				$output .= PHP_EOL . '/* Start of Custom CSS from Options Box */' . PHP_EOL . $custom_css . PHP_EOL ;
			}
		}

		if ( '' != $output ) {
			$output = '<style type="text/css" rel="cwt">'. $output. '</style>';
		}

		set_transient( 'catchwebtools_custom_css', $output, 7 * DAY_IN_SECONDS );
	}

	return $output;
}