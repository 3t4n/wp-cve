<?php
/**
 * Helper Functions
 *
 * @author 		MojofyWP
 * @package 	builder
 *
 */

/* ------------------------------------------------------------------------------- */

if ( ! function_exists('wrsl_is_light_or_dark') ) :
	/**
	 * Detect if a color is light or dark
	 *
	 * @return string
	 */
	function wrsl_is_light_or_dark( $color ) {
	
		if ( FALSE === strpos( $color, '#' ) ){
			// Not a color
			return NULL;
		}
	
		$hex = str_replace( '#', '', $color );
	
		$c_r = hexdec( substr( $hex, 0, 2 ) );
		$c_g = hexdec( substr( $hex, 2, 2 ) );
		$c_b = hexdec( substr( $hex, 4, 2 ) );
	
		$brightness = ( ( $c_r * 299 ) + ( $c_g * 587 ) + ( $c_b * 114 ) ) / 1000;
	
		return ( $brightness > 155 ) ? 'light' : 'dark' ;
	}
	endif;

/* ------------------------------------------------------------------------------- */

/**
* Style Generator
*
*/
if( !function_exists( 'wrsl_inline_styles' ) ) {
	function wrsl_inline_styles( $arg1 = NULL, $arg2 = NULL, $arg3 = NULL ){

		if ( 3 == func_num_args() ) {
			$container_id = $arg1; $type = $arg2; $args = $arg3;
		}
		elseif ( 2 == func_num_args() ) {
			$container_id = $arg1; $type = 'css'; $args = $arg2;
		}
		elseif ( 1 == func_num_args() ) {
			$container_id = NULL; $type = 'css'; $args = $arg1;
		}

		// Get the generated CSS
		global $wrsl_inline_css;

		$css = '';

		if( empty( $args ) || ( !is_array( $args ) && '' == $args ) ) return;

		switch ( $type ) {

			case 'background' :

				// Set the background array
				$bg_args = $args['background'];

				if( isset( $bg_args['color'] ) && '' != $bg_args['color'] ){
					$css .= 'background-color: ' . $bg_args['color'] . '; ';
				}

				if( isset( $bg_args['repeat'] ) && '' != $bg_args['repeat'] ){
					$css .= 'background-repeat: ' . $bg_args['repeat'] . ';';
				}

				if( isset( $bg_args['position'] ) && '' != $bg_args['position'] ){
					$css .= 'background-position: ' . $bg_args['position'] . ';';
				}

				if( isset( $bg_args['stretch'] ) && '' != $bg_args['stretch'] ){
					$css .= 'background-size: cover;';
				}

				if( isset( $bg_args['fixed'] ) && '' != $bg_args['fixed'] ){
					$css .= 'background-attachment: fixed;';
				}

				if( isset( $bg_args['image'] ) && '' != $bg_args['image'] ){
					$image = wp_get_attachment_image_src( $bg_args['image'] , 'full' );
					$css.= 'background-image: url(\'' . $image[0] .'\');';
				}
			break;

			case 'button' :

				// Set the background array
				$button_args = $args['button'];

				if( isset( $button_args['background-color'] ) && '' != $button_args['background-color'] ){
					$css .= 'background-color: ' . $button_args['background-color'] . '; ';
				}

				if( isset( $button_args['color'] ) && '' != $button_args['color'] ){
					$css .= 'color: ' . $button_args['color'] . '; ';
				}

			break;

			case 'margin' :
			case 'padding' :

				// Set the Margin or Padding array
				$trbl_args = $args[ $type ];

				if( isset( $trbl_args['top'] ) && '' != $trbl_args['top'] ){
					$css .= $type . '-top: ' . $trbl_args['top'] . '; ';
				}

				if( isset( $trbl_args['right'] ) && '' != $trbl_args['right'] ){
					$css .= $type . '-right: ' . $trbl_args['right'] . '; ';
				}

				if( isset( $trbl_args['bottom'] ) && '' != $trbl_args['bottom'] ){
					$css .= $type . '-bottom: ' . $trbl_args['bottom'] . '; ';
				}

				if( isset( $trbl_args['left'] ) && '' != $trbl_args['left'] ){
					$css .= $type . '-left: ' . $trbl_args['left'] . '; ';
				}

			break;

			case 'border' :

				// Set the background array
				$border_args = $args['border'];

				if( isset( $border_args['color'] ) && '' != $border_args['color'] ){
					$css .= 'border-color: ' . $border_args[ 'color' ] . ';';
				}

				if( isset( $border_args['width'] ) && '' != $border_args['width'] ){
					$css .= 'border-width: ' . $border_args[ 'width' ] . 'px;';
				}
			break;

			case 'color' :

				if( '' == $args[ 'color' ] ) return ;
				$css .= 'color: ' . $args[ 'color' ] . ';';

			break;

			case 'css' :
			default :

				if ( is_array( $args ) ){

					if ( isset( $args['css'] ) ) {
						if ( is_array( $args['css'] ) ){
							foreach ( $args['css'] as $css_atribute => $css_value ) {
								// Skip this if a css value is not sent.
								if ( ! isset( $css_value ) || '' == $css_value || NULL == $css_value ) continue;
								$css .= "$css_atribute: $css_value;";
							}
						}
						else {
							$css .= $args['css'];
						}
					}
				}
				else if ( is_string( $args ) ){

					$css .= $args;
				}

			break;

		}

		$css = apply_filters( 'wrsl_inline_' . $type . '_css' , $css, $args);

		// Bail if no css is generated
		if ( '' == trim( $css ) ) return false;

		$inline_css = '';

		// If there is a container ID specified, append it to the beginning of the declaration
		if( NULL !== $container_id ) {
			$inline_css = ' ' . $container_id . ' ' . $inline_css;
		}

		if( isset( $args['selectors'] ) ) {
            if ( is_string( $args['selectors'] ) && '' != $args['selectors'] ) {
            	$inline_css .= $args['selectors'];
            } else if( is_array( $args['selectors'] ) && !empty( $args['selectors'] ) ){
            	$inline_css .= implode( ', ' . $inline_css . ' ',  $args['selectors'] );
            }
		}

		// Apply inline CSS
		if( '' == trim( $inline_css ) ) {
			$inline_css .= $css;
		} else {
			$inline_css .= '{ ' . $css . '} ';
		}

		// Format/Clean the CSS.
		$inline_css = str_replace( "\n", '', $inline_css );
		$inline_css = str_replace( "\r", '', $inline_css );
		$inline_css = str_replace( "\t", '', $inline_css );
		$inline_css = "\n" . $inline_css;

		// Add the new CSS to the existing CSS
		$wrsl_inline_css .= $inline_css;

		return $inline_css;
	}
} // wrsl_inline_styles

/* ------------------------------------------------------------------------------- */

/**
* Apply Inline Styles
*/
if( !function_exists( 'wrsl_apply_inline_styles' ) ) {
	function wrsl_apply_inline_styles(){
		global $wrsl_inline_css;

		$wrsl_inline_css = apply_filters( 'wrsl_inline_css', $wrsl_inline_css );

		if( '' == $wrsl_inline_css || FALSE == $wrsl_inline_css ) return;

		wp_enqueue_style(
			'wrslb-inline-styles',
			wrsl()->plugin_url( '/assets/css/inline.css' )
		);

		wp_add_inline_style(
			'wrslb-inline-styles',
			$wrsl_inline_css
		);
	}
} // wrsl_apply_inline_styles

/* ------------------------------------------------------------------------------- */



/* ------------------------------------------------------------------------------- */



/* ------------------------------------------------------------------------------- */