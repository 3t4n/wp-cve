<?php

/**
 * Utility class
 *
 * @link       https://machothemes.com
 * @since      1.0.0
 *
 * @package    Photoblocks
 * @subpackage Photoblocks/includes
 */

class Photoblocks_Utils {

	/**
	 * Slugify text
	 *
	 * @since     1.0.0
	 * @return    string    The text to be slugified
	 */
	public static function slugify( $text ) {
		$text = preg_replace( '~[^\\pL\d]+~u', '-', $text );

		$text = trim( $text, '-' );
		if ( function_exists( 'iconv' ) ) {
			$text = iconv( 'utf-8', 'us-ascii//TRANSLIT', $text );
		}
		$text = strtolower( $text );
		$text = preg_replace( '~[^-\w]+~', '', $text );

		if ( empty( $text ) ) {
			return 'n-a';
		}

		return $text;
	}

	public static function list_thumbnail_sizes() {
		 global $_wp_additional_image_sizes;
		$sizes = array();
		foreach ( get_intermediate_image_sizes() as $s ) {
			if ( in_array( $s, array( 'thumbnail', 'medium', 'large' ) ) ) {
				$sizes[ $s ][0] = get_option( $s . '_size_w' );
				$sizes[ $s ][1] = get_option( $s . '_size_h' );
			} else {
				if ( isset( $_wp_additional_image_sizes ) &&
					isset( $_wp_additional_image_sizes[ $s ] ) &&
					$_wp_additional_image_sizes[ $s ]['width'] > 0 &&
					$_wp_additional_image_sizes[ $s ]['height'] > 0
				) {
					$sizes[ $s ] = array( $_wp_additional_image_sizes[ $s ]['width'], $_wp_additional_image_sizes[ $s ]['height'] );
				}
			}
		}

		return $sizes;
	}
}
