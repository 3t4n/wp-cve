<?php
/**
 * Lasso Lite Url detail - Hook.
 *
 * @package Pages
 */

namespace LassoLite\Pages\Url_Details;

use LassoLite\Classes\Affiliate_Link;
use LassoLite\Classes\Enum;
use LassoLite\Classes\Helper;

/**
 * Lasso Lite Url detail - Hook.
 */
class Hook {
	/**
	 * Declare "Lasso Lite register hook events" to WordPress.
	 */
	public function register_hooks() {
		// ? change Edit URL in Dashboard
		add_filter( 'get_edit_post_link', array( $this, 'affiliate_link_edit_post_link' ), 10, 3 );
	}

	/**
	 * Change edit post link for Lasso Lite post
	 *
	 * @param string $url     The edit link.
	 * @param int    $post_id Post ID.
	 * @param string $context The link context. If set to 'display' then ampersands are encoded.
	 */
	public function affiliate_link_edit_post_link( $url, $post_id, $context ) {

		$post_type = get_post_type( $post_id );

		if ( SIMPLE_URLS_SLUG === $post_type && ( get_option( Enum::LASSO_LITE_ACTIVE ) || Helper::is_lite_using_new_ui() ) ) {
			$url = Affiliate_Link::affiliate_edit_link( $post_id );
		}

		return $url;
	}
}
