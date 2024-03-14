<?php

/**
 * QR code card model.
 *
 * QR code card data manipulation class
 *
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WQM_Cards' ) ) {
	class WQM_Cards {
		/**
		 * Get all exists qr-code cards for select in widget form
		 *
		 * @return array
		 */
		public static function get_all_cards() {
			$args = array(
				'post_type'     => WQM_QR_Code_Type::POST_TYPE_SLUG,
				'orderBy'       => 'name',
				'order'         => 'ASC',
				'cache_results' => true,
			);

			$cards = [];

			$the_query = new WP_Query();
			$posts     = $the_query->query( $args );
			foreach ( $posts as $post ) {
				$cards[ $post->ID ] = $post->post_title;
			}

			return $cards;
		}
	}
}