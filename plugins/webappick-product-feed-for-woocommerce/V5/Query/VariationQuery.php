<?php

namespace CTXFeed\V5\Query;

use WP_Query;

/**
 * Class WPWCQuery
 *
 * @package    CTXFeed
 * @subpackage CTXFeed\V5\Query
 * @author     Ohidul Islam <wahid0003@gmail.com>
 * @link       https://webappick.com
 * @license    https://opensource.org/licenses/gpl-license.php GNU Public License
 * @category   MyCategory
 */
class VariationQuery implements QueryInterface {

	/**
	 * @var \CTXFeed\V5\Utility\Config $config Feed Config.
	 */
	private $config;

	/**
	 * @var array|null $arguments Query Arguments.
	 */
	private $arguments;

	/**
	 * WPWCQuery constructor.
	 *
	 * @param \CTXFeed\V5\Utility\Config $config Feed Config.
	 * @param array                      $args   Query Arguments.
	 */
	public function __construct( $config, $args = array() ) {
		$this->config    = $config;
		$this->arguments = wp_parse_args( $args, $this->get_query_arguments() );
	}

	/**
	 * Get product types.
	 *
	 * @return array
	 */
	public function get_product_types() {
		return array( 'product_variation' );
	}

	/**
	 * Set query arguments.
	 *
	 * @return array
	 */
	public function get_query_arguments() {
		$arguments = array(
			'posts_per_page'         => '-1',
			'post_type'              => $this->get_product_types(),
			'post_status'            => $this->get_product_status(),
			'order'                  => 'DESC',
			'fields'                 => 'ids',
			'cache_results'          => false,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
			'suppress_filters'       => false,
		);

		// Include Product Ids.
		$include = $this->config->get_products_to_include();

		if ( $include ) {
			$arguments['post__in'] = $include;
		}

		// Exclude Product Ids
		$exclude = $this->config->get_products_to_exclude();

		if ( $exclude ) {
			$arguments['post__not_in'] = $exclude;
		}

		// Include Author
		$author = $this->config->get_vendors_to_include();

		if ( $author ) {
			$arguments['author__in'] = $author;
		}

		$arguments['meta_query'] = $this->meta_conditions();// phpcs:ignore

		return $arguments;
	}

	/**
	 * Get meta conditions.
	 *
	 * @return array
	 */
	public function meta_conditions() {
		$meta_conditions = array(
			'relation' => 'AND',
		);
		// Remove out of stock products.
		$remove_out_of_stock = $this->config->remove_outofstock_product();

		if ( $remove_out_of_stock ) {
			$out_of_stock      = array(
				'key'     => '_stock_status',
				'value'   => 'outofstock',
				'compare' => '!=',
			);
			$meta_conditions[] = $out_of_stock;
		}

		// Remove back order products.
		$remove_back_order = $this->config->remove_backorder_product();

		if ( $remove_back_order ) {
			$back_order        = array(
				'key'     => '_stock_status',
				'value'   => 'onbackorder',
				'compare' => '!=',
			);
			$meta_conditions[] = $back_order;
		}

		// Remove hidden products.
// $remove_hidden = $this->config->remove_hidden_products();
//
// if ( $remove_hidden ) {
// $hidden            = array(
// 'key'     => '_visibility',
// 'value'   => 'hidden',
// 'compare' => '!=',
// );
// $meta_conditions[] = $hidden;
// }

		// Remove empty price products.
		$remove_empty_price = $this->config->remove_empty_price();

		if ( $remove_empty_price ) {
			$empty_price       = array(
				'key'     => '_regular_price',
				'value'   => '',
				'compare' => '!=',
			);
			$meta_conditions[] = $empty_price;
		}

		return $meta_conditions;
	}

	/**
	 * Get product status.
	 *
	 * @return string
	 */
	public function get_product_status() {
		$status = $this->config->get_post_status_to_include();

		if ( $status ) {
			return $status;
		}

		return 'publish';
	}

	/**
	 * Query Product Ids.
	 *
	 * @return array Array of product ids.
	 */
	public function product_ids() {
		// Add custom join and where clause
		add_filter( 'posts_join', array( $this, 'custom_join_query' ) );
		add_filter( 'posts_where', array( $this, 'custom_where_query' ) );
		add_filter( 'posts_groupby', array( $this, 'custom_groupby_query' ) );

		// Execute the query
		$query = new WP_Query( $this->arguments );

		// Remove filters after the query is executed
		remove_filter( 'posts_join', '__return_false' );
		remove_filter( 'posts_where', '__return_false' );
		remove_filter( 'posts_groupby', '__return_false' );

		return $query->get_posts();
	}

	/**
	 * Custom Join to get data from parent product and its meta
	 *
	 * @param string $join Query Join.
     * @return string
	 */
	public function custom_join_query( $join ) {
		global $wpdb;
		$join .= " LEFT JOIN {$wpdb->posts} AS parent ON parent.ID = {$wpdb->posts}.post_parent";
		$join .= " LEFT JOIN {$wpdb->term_relationships} AS rel ON rel.object_id = parent.ID";
		$join .= " LEFT JOIN {$wpdb->term_taxonomy} AS tax ON tax.term_taxonomy_id = rel.term_taxonomy_id";
		$join .= " LEFT JOIN {$wpdb->terms} AS term ON term.term_id = tax.term_id";
		$join .= " LEFT JOIN {$wpdb->postmeta} AS parent_meta ON parent_meta.post_id = parent.ID";

		return $join;
	}

	/**
	 * Custom Group By to ensure unique rows
	 *
	 * @param string $groupby Group By clause.
     * @return string
	 */
	public function custom_groupby_query( $groupby ) {// phpcs:ignore
		global $wpdb;

		return "{$wpdb->posts}.ID";
	}

	/**
	 * Custom Where to get data from parent product and its meta
	 *
	 * @param string $where Query Where.
     * @return string
	 */
	public function custom_where_query( $where ) {
		global $wpdb;
		// Exclude products with specific category
		$exclude_categories = $this->config->get_categories_to_exclude();

		// Include products with specific category
		$include_categories = $this->config->get_categories_to_include();

		// Handle category exclusion
		if ( ! empty( $exclude_categories ) ) {
			$exclude_cats = "'" . implode( "','", $exclude_categories ) . "'";
			$where       .= " AND NOT EXISTS (
            SELECT 1 FROM {$wpdb->term_relationships} AS ex_rel
            JOIN {$wpdb->term_taxonomy} AS ex_tax ON ex_tax.term_taxonomy_id = ex_rel.term_taxonomy_id
            JOIN {$wpdb->terms} AS ex_term ON ex_term.term_id = ex_tax.term_id
            WHERE ex_rel.object_id = parent.ID AND ex_term.slug IN ({$exclude_cats})
        )";
		}

		// Handle category inclusion
		if ( ! empty( $include_categories ) ) {
			$include_cats = "'" . implode( "','", $include_categories ) . "'";
			$where       .= " AND EXISTS (
            SELECT 1 FROM {$wpdb->term_relationships} AS in_rel
            JOIN {$wpdb->term_taxonomy} AS in_tax ON in_tax.term_taxonomy_id = in_rel.term_taxonomy_id
            JOIN {$wpdb->terms} AS in_term ON in_term.term_id = in_tax.term_id
            WHERE in_rel.object_id = parent.ID AND in_term.slug IN ({$include_cats})
        )";
		}

		// Remove hidden products
		$remove_hidden_products = $this->config->remove_hidden_products();

		if ( $remove_hidden_products ) {
			$where .= " AND NOT EXISTS (
            SELECT 1 FROM {$wpdb->term_relationships} as tr
            INNER JOIN {$wpdb->term_taxonomy} as tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
            INNER JOIN {$wpdb->terms} as t ON tt.term_id = t.term_id
            WHERE tt.taxonomy = 'product_visibility'
            AND t.slug = 'exclude-from-catalog'
            AND tr.object_id = parent.ID
        )";
		}

		return $where;
	}

}
