<?php
/**
 * Declare class Shortcode
 *
 * @package Page
 */

namespace LassoLite\Classes;

use LassoLite\Classes\Helper;
use LassoLite\Classes\Setting;
use Lasso_Shortcode;

/**
 * Shortcode
 */
class Shortcode {

	const LASSO_PRO_POST_TYPE       = 'lasso-urls';
	const AAWP_SUPPORT_FIELDS_VALUE = array(
		'title',
		'image',
		'thumb',
		'button',
		'price',
		'list_price',
		'amount_saved',
		'percentage_saved',
		'rating',
		'star_rating',
		'description',
		'reviews',
		'url',
		'link',
		'last_update',
	);

	/**
	 * Lasso Display Boxes
	 *
	 * @param array $attr Attributes of shortcode.
	 */
	public function lasso_lite_core_shortcode( $attr ) {
		$post_id      = $attr['id'] ?? '';
		$title        = $attr['title'] ?? '';
		$title_url    = $attr['title_url'] ?? '';
		$title_type   = $attr['title_type'] ?? '';
		$description  = $attr['description'] ?? '';
		$badge        = $attr['badge'] ?? '';
		$price        = $attr['price'] ?? '';
		$primary_url  = $attr['primary_url'] ?? '';
		$primary_text = $attr['primary_text'] ?? '';
		$image_url    = $attr['image_url'] ?? '';
		$anchor_id    = $attr['anchor_id'] ?? '';
		$brag         = $attr['brag'] ?? '';

		if ( empty( $anchor_id ) ) {
			$unique_id = $post_id;
			$anchor_id = 'lasso-lite-anchor-id-' . $unique_id;
		}

		// ? Lasso Lite must be having "id" parameter
		if ( ! $post_id ) {
			return false;
		}

		$post = get_post( $post_id );
		// ? Check existing post
		if ( is_null( $post ) ) {
			return false;
		}

		// ? Check case Lasso Lite deleted
		if ( 'trash' === $post->post_status ) {
			return false;
		}

		// ? Check post_type is Lasso Lite or not
		if ( SIMPLE_URLS_SLUG !== $post->post_type ) {
			if ( class_exists( 'Lasso_Shortcode' ) ) {
				$shortcode_pro = new Lasso_Shortcode();
				return $shortcode_pro->lasso_core_shortcode( $attr );
			}
			return false;
		}

		// ? Check post_type surl to execute shortcode correctly
		if ( SIMPLE_URLS_SLUG === $post->post_type ) {
			$pass_data = array(
				'post'         => $post,
				'title'        => $title,
				'title_type'   => $title_type,
				'title_url'    => $title_url,
				'description'  => $description,
				'badge'        => $badge,
				'price'        => $price,
				'primary_url'  => $primary_url,
				'primary_text' => $primary_text,
				'image_url'    => $image_url,
				'anchor_id'    => $anchor_id,
				'brag'         => $brag,
			);
			// ? Default single-style display box
			return Helper::include_with_variables( Helper::get_path_views_folder() . 'displays/single.php', $pass_data );
		}

		return false;
	}
}
