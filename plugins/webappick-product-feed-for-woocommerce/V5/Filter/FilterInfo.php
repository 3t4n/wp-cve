<?php

namespace CTXFeed\V5\Filter;

use CTXFeed\V5\Helper\ProductHelper;
use CTXFeed\V5\Utility\Config;
use CTXFeed\V5\Utility\Settings;

/**
 * Class FilterInfo
 *
 * @package    CTXFeed
 * @subpackage CTXFeed\V5\Filter
 * @author     Ohidul Islam <wahid0003@gmail.com>
 * @link       https://webappick.com
 * @license    https://opensource.org/licenses/gpl-license.php GNU Public License
 * @category   Filter
 */
class FilterInfo {

	/**
	 * Get Product Ids or count product ids via WC_Product_Query
	 * @param $args
	 * @param bool $count
	 *
	 * @return array|int|null
	 */
	public static function getProductsWC( $args = [], $count = true ) {
		$config       = new Config( [] );
		$oldQueryType = Settings::get( 'product_query_type' );
		Settings::set( 'product_query_type', 'wc' );
		$default_args = [
			'limit'  => - 1,
			'status' => 'publish',
			'return' => 'ids',
		];

		$args = wp_parse_args( $args, $default_args );
		$Ids  = ProductHelper::get_ids( $config, $args );
		Settings::set( 'product_query_type', $oldQueryType );

		if ( $count ) {
			return count( $Ids );
		}

		return $Ids;
	}

	/**
	 * Get Product Ids or count product ids via WP_Query
	 * @param $args
	 * @param $count
	 *
	 * @return array|int|null
	 */
	public static function getProductsWP( $args = [], $count = true ) {
		$oldQueryType = Settings::get( 'product_query_type' );
		Settings::set( 'product_query_type', 'wp' );
		$config       = new Config( [] );
		$default_args = [
			'posts_per_page' => - 1,
			'post_type'      => [ 'product', 'product_variation' ],
			'post_status'    => 'publish',
			'fields'         => 'ids',
		];
		$args         = wp_parse_args( $args, $default_args );
		$Ids          = ProductHelper::get_ids( $config, $args );
		Settings::set( 'product_query_type', $oldQueryType );

		if ( $count ) {
			return count( $Ids );
		}

		return $Ids;
	}

	/**
	 * Count out of stock products.
	 *
	 * @return array|int|null
	 */
	public static function getOutOfStockProducts( $count = true ) {
		$oldQueryType = Settings::get( 'product_query_type' );
		Settings::set( 'product_query_type', 'wc' );
		$config = new Config( [] );
		$args   = [
			'limit'        => - 1,
			'stock_status' => 'outofstock',
			'status'       => 'publish',
			'return'       => 'ids',
		];
		$Ids    = ProductHelper::get_ids( $config, $args );
		Settings::set( 'product_query_type', $oldQueryType );

		if ( $count ) {
			return count( $Ids );
		}

		return $Ids;
	}

	/**
	 * Count on back order products.
	 *
	 * @return int|null
	 */
	public static function getBackOrderProducts() {
		$oldQueryType = Settings::get( 'product_query_type' );
		Settings::set( 'product_query_type', 'wc' );
		$config = new Config( [] );
		$args   = [
			'limit'        => - 1,
			'stock_status' => 'onbackorder',
			'status'       => 'publish',
			'return'       => 'ids',
		];
		$Ids    = ProductHelper::get_ids( $config, $args );
		Settings::set( 'product_query_type', $oldQueryType );

		return count( $Ids );
	}

	/**
	 * Count Hidden products.
	 *
	 * @return int|null
	 */
	public static function getHiddenProducts() {
		$oldQueryType = Settings::get( 'product_query_type' );
		Settings::set( 'product_query_type', 'wc' );
		$config = new Config( [] );
		$args   = [
			'limit'      => - 1,
			'visibility' => 'hidden',
			'status'     => 'publish',
			'return'     => 'ids',
		];
		$Ids    = ProductHelper::get_ids( $config, $args );
		Settings::set( 'product_query_type', $oldQueryType );

		return count( $Ids );
	}

	/**
	 * Count empty title products.
	 *
	 * @return int|null
	 */
	public static function getEmptyTitleProducts() {
		global $wpdb;
		$emptyPosts = $wpdb->get_results( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_title ='' AND post_type='product' AND 'post_status'='publish'" ) );

		return count( $emptyPosts );
	}

	/**
	 * Count empty description products.
	 *
	 * @return int|null
	 */
	public static function getDescriptionProducts() {
		global $wpdb;
		$emptyPosts = $wpdb->get_results( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_content ='' AND post_type='product' AND 'post_status'='publish'" ) );

		return count( $emptyPosts );
	}

	public static function getEmptyPriceProducts() {
		$oldQueryType = Settings::get( 'product_query_type' );
		Settings::set( 'product_query_type', 'wp' );
		$config = new Config( [] );
		$args   = [
			'posts_per_page' => - 1,
			'post_type'      => [ 'product', 'product_variation' ],
			'post_status'    => 'publish',
			'fields'         => 'ids',
		];

		$args['meta_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
			array(
				'key'     => '_regular_price',
				'compare' => '>',
				'value'   => '0'
			),
		);

		$args2 = [
			'posts_per_page' => - 1,
			'post_type'      => [ 'product', 'product_variation' ],
			'post_status'    => 'publish',
			'fields'         => 'ids',
		];

		$allIds = ProductHelper::get_ids( $config, $args2 );
		$Ids    = ProductHelper::get_ids( $config, $args );
		Settings::set( 'product_query_type', $oldQueryType );

		return count( $allIds ) - count( $Ids );
	}

	/**
	 * Count empty image products.
	 *
	 * @return int|null
	 */
	public static function getEmptyImageProducts() {
		$oldQueryType = Settings::get( 'product_query_type' );
		Settings::set( 'product_query_type', 'wp' );
		$config = new Config( [] );
		$args   = [
			'posts_per_page' => - 1,
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'fields'         => 'ids',
		];

		$args['meta_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
			array(
				'key'     => '_thumbnail_id',
				'compare' => '>',
				'value'   => '0'
			),
		);

		$args2 = [
			'posts_per_page' => - 1,
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'fields'         => 'ids',
		];

		$allIds = ProductHelper::get_ids( $config, $args2 );
		$Ids    = ProductHelper::get_ids( $config, $args );
		Settings::set( 'product_query_type', $oldQueryType );

		return count( $allIds ) - count( $Ids );
	}

}
