<?php
/**
 * Podcast player utility functions.
 *
 * @link       https://www.vedathemes.com
 * @since      3.3.0
 *
 * @package    Podcast_Player
 * @subpackage Podcast_Player/Helper
 */

namespace Podcast_Player\Helper\Functions;

use Podcast_Player\Helper\Functions\Getters as Get_Fn;
use Podcast_Player\Helper\Functions\Utility as Utility_Fn;

/**
 * Podcast player utility functions.
 *
 * @package    Podcast_Player
 * @subpackage Podcast_Player/Helper
 * @author     vedathemes <contact@vedathemes.com>
 */
class Validation {

	/**
	 * Constructor method.
	 *
	 * @since  3.3.0
	 */
	public function __construct() {}

	/**
	 * Basic check that URL has a valid scheme.
	 *
	 * @since 3.3.0
	 *
	 * @param string $url Feed url to be checked.
	 *
	 */
	public static function is_valid_url( $url ) {
		if ( ! $url || ! is_string( $url ) ) {
			return false;
		}

		$parsed_url = wp_parse_url( $url, PHP_URL_SCHEME );
		if ( ! $parsed_url ) {
			return false;
		}

		$scheme = strtolower( $parsed_url );
		if ( in_array( $scheme, array( 'http', 'https' ), true ) ) {
			return true;
		}
		return false;
	}

	/**
	 * Check if url is valid image url.
	 *
	 * @since 3.3.0
	 *
	 * @param string $image Image url to be checked.
	 */
	public static function is_valid_image_url( $image ) {
		$img_url = $image ? preg_replace( '/\?.*/', '', $image ) : false;
		if ( ! $img_url ) {
			return false;
		}

		$file_type   = wp_check_filetype( $img_url, wp_get_mime_types() );
		$allowed_ext = array( 'jpg', 'jpeg', 'jpe', 'gif', 'png', 'bmp', 'tiff', 'tif', 'ico' );
		if ( in_array( strtolower( $file_type['ext'] ), $allowed_ext, true ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Check if url is an internal link.
	 *
	 * @since 3.3.0
	 *
	 * @param string $link Link to be checked.
	 */
	public static function is_internal_link( $link ) {
		$host = wp_parse_url( $link, PHP_URL_HOST );

		// Check if relative link without a host.
		if ( empty( $host ) ) {
			return true;
		}

		// Check if host is same as home_url.
		if ( strtolower( $host ) === strtolower( wp_parse_url( home_url(), PHP_URL_HOST ) ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Check if item is supported by the style.
	 *
	 * @param string $style Current display style.
	 * @param mixed  $items item to be checked for support.
	 * @param string $type  Multi items test relationship ('AND' or 'OR').
	 * @return bool
	 */
	public static function is_style_support( $style, $items, $type = 'AND' ) {
		$items           = (array) $items;
		$is_supported    = true;
		$style_supported = Get_Fn::get_style_supported();
		$supported_items = isset( $style_supported[ $style ] ) ? $style_supported[ $style ] : false;
		if ( ! $supported_items ) {
			return false;
		}

		if ( 'OR' === $type ) {
			return (bool) array_intersect( $items, $supported_items );
		}

		foreach ( $items as $item ) {
			if ( ! in_array( $item, $supported_items, true ) ) {
				$is_supported = false;
				break;
			}
		}
		return $is_supported;
	}

	/**
	 * Check if dark color is in contrast with the given color.
	 *
	 * @since 3.3.0
	 *
	 * @param string $color Color Hex code to be checked.
	 */
	public static function is_dark_contrast( $color ) {
		$rgb = Utility_Fn::hex_to_rgb( $color, false );
		if ( $rgb ) {
			$black = Utility_Fn::lumdiff( (int) $rgb['red'], (int) $rgb['green'], (int) $rgb['blue'], 51, 51, 51 );
			$white = Utility_Fn::lumdiff( (int) $rgb['red'], (int) $rgb['green'], (int) $rgb['blue'], 255, 255, 255 );
			return $black > $white;
		}
		return false;
	}

	/**
	 * Check if single episode podcast player layout is to be displayed.
	 *
	 * @since 3.8.0
	 *
	 * @param int    $epinum         Number of episodes to be displayed.
	 * @param string $style          Podcast player display style.
	 * @param bool   $single_episode If only a single episode is displayed.
	 */
	public static function is_single_episode_layout( $epinum, $style, $single_episode = false ) {
		$is_single = false;

		// Default single layout only for default and legacy layouts.
		if ( 1 >= $epinum && ( '' === $style || 'legacy' === $style || 'modern' === $style ) ) {
			$is_single = true;
		}
		if ( $single_episode && ( '' === $style || 'legacy' === $style || 'modern' === $style ) ) {
			$is_single = true;
		}
		return apply_filters( 'podcast_player_single_episode_layout', $is_single, $epinum );
	}
}
