<?php

namespace CTXFeed\V5\Query;

use CTXFeed\V5\Utility\Config;
use CTXFeed\V5\Utility\Settings;

class QueryFactory {
	/**
	 * @param Config $config
	 *
	 * @return array
	 */
	public static function get_ids( $config, $args = [] ) {
		$template  = $config->get_feed_template();
		$queryType = Settings::get( 'product_query_type' );

		$class = "\CTXFeed\V5\Query\\" . strtoupper( $queryType ) . "Query";
		if ( strpos( $template, 'review' ) ) {
			$class = WCReviewQuery::class;
		}

		$feedRules = $config->get_config();

		/**
		 * Fires before looping through request product for getting product data
		 *
		 * @param int[] $productIds
		 * @param array $feedConfig
		 *
		 * @since 3.2.10
		 */

		do_action( 'woo_feed_before_product_loop', [], $feedRules, $config );

		/**
		 * @var WPQuery|WCQuery|WCReviewQuery $class Query Class
		 */
		$productIds = ( new Query( new $class( $config, $args ) ) )->get_ids();


		/**
		 * Fires after looping through request product for getting product data
		 *
		 * @param int[]  $productIds
		 * @param Config $config
		 *
		 * @since 3.2.10
		 */
		do_action( 'woo_feed_after_product_loop', $productIds, $feedRules, $config );

		return $productIds;
	}
}
