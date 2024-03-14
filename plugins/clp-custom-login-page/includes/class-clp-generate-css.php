<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
};

class CLP_Generate_CSS {

	/**
	 * Generate CSS.
     * @since 1.0.0
	 *
	 * @param string $selector The CSS selector.
	 * @param string $style The CSS style.
	 * @param string $value The CSS value.
	 * @param string $prefix The CSS prefix.
	 * @param string $suffix The CSS suffix.
	 * @param bool   $echo Echo the styles.
	 */
	function generate_css( $selector, $style, $value, $prefix = '', $suffix = '' ) {

		$css = '';
		/*
		 * Bail early if we have no $selector elements or properties and $value.
		 */
		if ( (empty($value) && $value !== '0') || !$selector ) {
			return;
		}

		$css = sprintf( '%s { %s: %s; }', $selector, $style, $prefix . $value . $suffix );

		return $css;

    }
    
    /**
     * echo CSS into header
     * @since 1.0.0
     * @return CSS
    **/
    function get_customizer_css() {

		$customizer_settings = new CLP_Customizer_Settings;

		$css_settings = $customizer_settings->get_css_fields();
        ob_start();
		
        foreach ( $css_settings as $css ) {

			/*
			* Bail early if css is dependent on another option, and it's not selected
			*/
			if ( isset( $css['dependency']) ) {
				$depen1 = get_option( $css['dependency'][0], $css['dependency']['default'] );
				$depen2 = $css['dependency'][1];
				$op = isset( $css['dependency'][2]) ? $css['dependency'][2] : '=';

				if ( !CLP_Helper_Functions::num_cond( $depen1, $depen2, $op ) ) {
					continue;
				}
			} 

			$value = get_option( $css['id'], $css['default'] );
			
			/*
			* run a function to get a value if required e.g. for get media url from ID
			*/
			if ( isset($css['get_value']) ) {

				$params_nm = end($css['get_value']);

				switch ($params_nm) {
					case 1:
						$value = call_user_func( $css['get_value'][0], $css['get_value'][1], $css['get_value'][2] );
						break;
					case 2:
						$value = call_user_func( $css['get_value'][0], $css['get_value'][1], $css['get_value'][2], $css['get_value'][3] );
						break;
					case 0:
					default:
					$value = call_user_func( $css['get_value'][0], $css['get_value'][1] );
						break;
				}

				if ( isset($css['return_value']) && is_array($css['return_value']) ) {
					$value = $value[$css['return_value'][1]];
				}
			}

			/*
			* Bail early if value is empty, but allow 0
			*/

			
			if ( empty($value) && $value !== '0') {
				continue;
			}
			/*
			* replace value string placeholder for specific CSS snippets
			*/
			if ( isset( $css['css_value']) ) {
				$value = str_replace('%VALUE%', $value, $css['css_value']);
			}		

			$property = explode( ',', $css['property'] );

			foreach ( $property as $prop ) {
				echo $this->generate_css( $css['selector'], $prop, $value, isset( $css['prefix'] ) ? $css['prefix'] : '', isset( $css['suffix'] ) ? $css['suffix'] : '' ) . PHP_EOL;
			}
			
        }
        
        $css = ob_get_clean();

        return $css;

    }
}