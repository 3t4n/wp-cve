<?php
/**
 * This file contain all plugin functions.
 *
 * @package YITH WooCommerce Featured Audio Video Content\Functions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'ywcfav_video_type_by_url' ) ) {
	/**
	 * Retrieve the type of video, by url
	 *
	 * @param string $url The video's url.
	 *
	 * @return mixed A string format like this: "type:ID". Return FALSE, if the url isn't a valid video url.
	 *
	 * @since 1.1.0
	 */
	function ywcfav_video_type_by_url( $url ) {

		$parsed = parse_url( esc_url( $url ) );

		switch ( $parsed['host'] ) {

			case 'www.youtube.com':
			case 'youtu.be':
				$id = ywcfav_get_yt_video_id( $url );

				return "youtube:$id";

			case 'vimeo.com':
			case 'player.vimeo.com':
				preg_match( '/.*(vimeo\.com\/)((channels\/[A-z]+\/)|(groups\/[A-z]+\/videos\/))?([0-9]+)/', $url, $matches );
				$id = $matches[5];

				return "vimeo:$id";

			default:
				return apply_filters( 'yith_woocommerce_featured_video_type', false, $url );

		}
	}
}
if ( ! function_exists( 'ywcfav_get_yt_video_id' ) ) {
	/**
	 * Retrieve the id video from youtube url
	 *
	 * @param string $url The video's url.
	 *
	 * @return string The youtube id video.
	 *
	 * @since 1.1.0
	 */
	function ywcfav_get_yt_video_id( $url ) {

		$pattern =
			'%^# Match any youtube URL
        (?:https?://)?  # Optional scheme. Either http or https
        (?:www\.)?      # Optional www subdomain
        (?:             # Group host alternatives
          youtu\.be/    # Either youtu.be,
        | youtube\.com  # or youtube.com
          (?:           # Group path alternatives
            /embed/     # Either /embed/
          | /v/         # or /v/
          | /watch\?v=  # or /watch\?v=
          )             # End path alternatives.
        )               # End host alternatives.
        ([\w-]{10,12})  # Allow 10-12 for 11 char youtube id.
        $%x';
		$result  = preg_match( $pattern, $url, $matches );
		if ( false !== $result ) {
			return $matches[1];
		}

		return false;
	}
}

if ( ! function_exists( 'ywcfav_save_remote_image' ) ) {

	/**
	 * Save the video image
	 *
	 * @author YITH <plugins@yithemes.com>
	 * @param string $url the image url.
	 * @param string $newfile_name the file name.
	 * @return int|WP_Error
	 */
	function ywcfav_save_remote_image( $url, $newfile_name = '' ) {

		$url = str_replace( 'https', 'http', $url );
		$tmp = download_url( (string) $url );

		$file_array = array();
		preg_match( '/[^\?]+\.(jpg|jpe|jpeg|gif|png)/i', (string) $url, $matches );
		$file_name = basename( $matches[0] );
		if ( '' !== $newfile_name ) {
			$file_name_info = explode( '.', $file_name );
			$file_name      = $newfile_name . '.' . $file_name_info[1];
		}

		if ( ! function_exists( 'remove_accents' ) ) {
			require_once ABSPATH . 'wp-includes/formatting.php';
		}
		$file_name = sanitize_file_name( remove_accents( $file_name ) );
		$file_name = str_replace( '-', '_', $file_name );

		$file_array['name']     = $file_name;
		$file_array['tmp_name'] = $tmp;

		// If error storing temporarily, unlink.
		if ( is_wp_error( $tmp ) ) {
			@unlink( $file_array['tmp_name'] );
			$file_array['tmp_name'] = '';

		}

		// Do the validation and storage stuff.
		return media_handle_sideload( $file_array, 0 );
	}
}

if ( ! function_exists( 'ywcfav_get_gallery_item_class' ) ) {
	/**
	 * Return the default class
	 *
	 * @since 2.0.0
	 * return the woocommerce product gallery image
	 * @return string
	 */
	function ywcfav_get_gallery_item_class() {

		return apply_filters( 'ywcfav_get_gallery_item_class', 'woocommerce-product-gallery__image' );
	}
}

if ( ! function_exists( 'ywcfav_get_thumbnail_gallery_item' ) ) {
	/**
	 * Return the default class
	 *
	 * @since 2.0.0
	 * get the class of thumbnail gallery
	 * @return string
	 */
	function ywcfav_get_thumbnail_gallery_item() {

		return apply_filters( 'ywcfav_get_thumbnail_gallery_item', 'flex-control-nav.flex-control-thumbs li' );
	}
}

if ( ! function_exists( 'ywcfav_get_product_gallery_trigger' ) ) {
	/**
	 * Return the default class
	 *
	 * @since 2.0.0
	 * get the product gallery trigger class
	 * @return string
	 */
	function ywcfav_get_product_gallery_trigger() {

		return apply_filters( 'ywcfav_get_product_gallery_trigger', 'woocommerce-product-gallery__trigger' );
	}
}

if ( ! function_exists( 'ywcfav_check_is_zoom_magnifier_is_active' ) ) {

	/**
	 * Check if zoom magnifier is active
	 *
	 * @return bool
	 */
	function ywcfav_check_is_zoom_magnifier_is_active() {

		if ( defined( 'YITH_YWZM_FREE_INIT' ) || defined( 'YITH_YWZM_PREMIUM' ) ) {
			if ( wp_is_mobile() ) {
				return ( 'yes' === get_option( 'yith_wcmg_enable_mobile' ) );
			}

			return 'yes' === get_option( 'yith_wcmg_enable_plugin' );
		}
		return false;
	}
}

if ( ! function_exists( 'ywcfav_check_is_product_is_exclude_from_zoom' ) ) {

	/**
	 * Check if the product is excluded from zoom
	 *
	 * @return bool
	 */
	function ywcfav_check_is_product_is_exclude_from_zoom() {
		/**
		 * The zoom magnifier instance
		 *
		 * @var YITH_WooCommerce_Zoom_Magnifier_Premium $yith_wcmg;
		 */
		global $yith_wcmg;
		return is_callable( array( $yith_wcmg, 'is_product_excluded' ) ) && $yith_wcmg->is_product_excluded();
	}
}
