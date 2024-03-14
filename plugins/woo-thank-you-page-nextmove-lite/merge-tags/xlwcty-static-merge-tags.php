<?php
defined( 'ABSPATH' ) || exit;

class XLWCTY_Static_Merge_Tags {

	public static $threshold_to_date = 30;
	protected static $_data_shortcode = array();

	/**
	 * Maybe try and parse content to found the xlwcty merge tags
	 * And converts them to the standard wp shortcode way
	 * So that it can be used as do_shortcode in future
	 *
	 * @param string $content
	 *
	 * @return mixed|string
	 */
	public static function maybe_parse_merge_tags( $content = '' ) {
		$get_all      = self::get_all_tags();
		$get_all_tags = wp_list_pluck( $get_all, 'tag' );
		//iterating over all the merge tags
		if ( $get_all_tags && is_array( $get_all_tags ) && count( $get_all_tags ) > 0 ) {
			foreach ( $get_all_tags as $tag ) {
				$matches = array();
				$re      = sprintf( '/\{{%s(.*?)\}}/', $tag );
				$str     = $content;

				//trying to find match w.r.t current tag
				preg_match_all( $re, $str, $matches );

				//if match found
				if ( $matches && is_array( $matches ) && count( $matches ) > 0 ) {

					//iterate over the found matches
					foreach ( $matches[0] as $exact_match ) {

						//preserve old match
						$old_match = $exact_match;

						$single = str_replace( '{{', '', $old_match );
						$single = str_replace( '}}', '', $single );

						$get_parsed_value = call_user_func( array( __CLASS__, $single ) );
						$content          = str_replace( $old_match, $get_parsed_value, $content );
					}
				}
			}
		}

		return $content;
	}

	public static function get_all_tags() {

		$tags = array(
			array(
				'name' => __( 'Shop Title', 'woo-thank-you-page-nextmove-lite' ),
				'tag'  => 'shop_title',
			),
			array(
				'name' => __( 'Home Url', 'woo-thank-you-page-nextmove-lite' ),
				'tag'  => 'home_url',
			),
			array(
				'name' => __( 'Shop URL', 'woo-thank-you-page-nextmove-lite' ),
				'tag'  => 'shop_url',
			),
			array(
				'name' => __( 'Shop Admin Email', 'woo-thank-you-page-nextmove-lite' ),
				'tag'  => 'shop_admin_email',
			),
		);

		return $tags;
	}

	protected static function shop_title() {
		return get_bloginfo( 'name' );
	}

	protected static function home_url() {
		return home_url();
	}

	protected static function shop_url() {
		return wc_get_page_permalink( 'shop' );
	}

	protected static function shop_admin_email() {
		return get_bloginfo( 'admin_email' );
	}

}
