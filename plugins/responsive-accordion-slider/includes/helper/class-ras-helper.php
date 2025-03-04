<?php

/**
 *  Helper Class for Image Slider
 */

 class Resp_Accordion_Slider_Helper {
	
	/*
	*
	* Generate html attributes based on array
	*
	* @param array atributes
	*
	* @return string
	*
	*/
	public static function generate_attributes( $attributes ) {

		

		$return = '';
		foreach ( $attributes as $name => $value ) {

			if ( in_array( $name, array( 'alt', 'rel', 'title' ) ) ) {
				$value = str_replace('<script', '&lt;script', $value );
				$value = strip_tags( htmlspecialchars( $value ) );
				$value = preg_replace( '@<(script|style)[^>]*?>.*?</\\1>@si', '', $value );
			}else{
				$value = esc_attr( $value );
			}

			$return .= ' ' . esc_attr( $name ) . '="' . $value . '"';
		}
		
		return $return;

	}

	/**
	 * Callback to sort tabs/fields on priority.
	 *
	 * @since 1.0.1
	 *
	 * @return bool
	 */
	public static function sort_data_by_priority( $a, $b ) {
		if ( ! isset( $a['priority'], $b['priority'] ) ) {
			return -1;
		}
		if ( $a['priority'] == $b['priority'] ) {
			return 0;
		}
		return $a['priority'] < $b['priority'] ? -1 : 1;
	}


	public static function get_title( $item, $default_source ){

		$title = isset($item['title']) ? $item['title'] : '';

		if ( '' == $title && 'none' != $default_source ) {
			$title = self::get_image_info( $item['id'],$default_source );
		}

		return $title;

	}

	public static function get_description( $item, $default_source ){
		
		$description = isset($item['description']) ? $item['description'] : '';

		if ( '' == $description && 'none' != $default_source ) {
			$description = self::get_image_info( $item['id'],$default_source );
		}

		return $description;

	}
}