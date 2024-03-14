<?php

/**
 * @package WP Product Review Feed Manager/Traits
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

trait WPPRFM_Processing_Support {

	/**
	 * Adds the review data to the product data object
	 *
	 * @param $product_data
	 * @param $comment_data
	 * @param $comment_meta
	 * @param $active_fields
	 * @param $relation_table
	 */
	protected function add_review_data_to_product_data( &$product_data, $comment_data, $comment_meta, $active_fields, $relation_table ) {
		$support_class = new WPPFM_Feed_Support();

		foreach ( $active_fields as $field ) {
			$db_title = $support_class->find_relation( $field, $relation_table );

			if ( property_exists( $comment_data, $db_title ) ) {
				$product_data[ $db_title ] = $comment_data->$db_title;
			}

			if ( array_key_exists( $db_title, $comment_meta ) ) {
				if ( is_array( $comment_meta[ $db_title ] ) ) {
					$product_data[ $db_title ] = $comment_meta[ $db_title ][0];
				} else {
					$product_data[ $db_title ] = $comment_meta->$db_title;
				}
			}

			$this->add_procedural_comment_data( $product_data, $field, $comment_data );
		}
	}

	private function add_procedural_comment_data( &$product_data, $field, $comment_data ) {
		switch ( $field ) {
			case 'review_url':
				$product_data['comment_url'] = get_comment_link( $comment_data );
				break;

			case 'ratings_overall_min':
			case 'ratings_overall_max':
				break;
		}
	}
}
