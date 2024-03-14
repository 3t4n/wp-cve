<?php
/**
 * Shortcode Shorce Change Ajax Class.
 *
 * @package RT_FoodMenu
 */

namespace RT\FoodMenu\Controllers\Admin\Ajax;

use RT\FoodMenu\Helpers\Options;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Shortcode Shorce Change Ajax Class.
 */
class ShortcodeSource {
	use \RT\FoodMenu\Traits\SingletonTrait;

	/**
	 * Class Init.
	 *
	 * @return void
	 */
	protected function init() {
		add_action( 'wp_ajax_fmp_sc_source_change', [ $this, 'response' ] );
	}

	/**
	 * Ajax Response.
	 *
	 * @return void
	 */
	public function response() {
		$catList = '';
		$source  = ( isset( $_REQUEST['source'] ) && in_array( $_REQUEST['source'], array_keys( Options::scProductSource() ), true ) ) ? sanitize_text_field( wp_unslash( $_REQUEST['source'] ) ) : TLPFoodMenu()->post_type;

		$terms = [];

		if ( 'product' === $source && TLPFoodMenu()->isWcActive() ) {
			$termList = get_terms( 'product_cat', [ 'hide_empty' => 0 ] );

			if ( is_array( $termList ) && ! empty( $termList ) && empty( $termList['errors'] ) ) {
				$terms = $termList;
			}
		} else {
			$termList = get_terms( TLPFoodMenu()->taxonomies['category'], [ 'hide_empty' => 0 ] );

			if ( is_array( $termList ) && ! empty( $termList ) && empty( $termList['errors'] ) ) {
				$terms = $termList;
			}
		}

		if ( ! empty( $terms ) ) {
			foreach ( $terms as $term ) {
				$catList .= "<option value='{$term->term_id}'>{$term->name}</option>";
			}
		}

		wp_send_json(
			[
				'cat_list' => $catList,
			]
		);

		die();
	}
}
