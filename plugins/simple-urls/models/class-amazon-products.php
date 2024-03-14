<?php
/**
 * Models
 *
 * @package Models
 */

namespace LassoLite\Models;

use LassoLite\Admin\Constant;

use LassoLite\Models\Url_Details;

/**
 * Model
 */
class Amazon_Products extends Model {
	/**
	 * Table name
	 *
	 * @var string
	 */
	protected $table = 'lasso_lite_amazon_products';

	/**
	 * Columns of the table
	 *
	 * @var array
	 */
	protected $columns = array(
		'amazon_id',
		'default_product_name',
		'latest_price',
		'base_url',
		'monetized_url',

		'default_image',
		'out_of_stock',
		'is_prime',
		'currency',
		'savings_amount',

		'savings_percent',
		'savings_basis',
		'features',
		'is_manual',
		'last_updated',
	);

	/**
	 * Primary key of the table
	 *
	 * @var string
	 */
	protected $primary_key = 'amazon_id';

	/**
	 * Create table
	 */
	public function create_table() {
		$columns_sql = '
			amazon_id varchar(20) NOT NULL,
			default_product_name varchar(2000) NOT NULL,
			latest_price varchar(32) NULL,
			base_url varchar(2000) NOT NULL,
			monetized_url varchar(2000) NULL,
			default_image varchar(10000) NULL,
			out_of_stock TINYINT(1) NULL DEFAULT 0,
			is_prime TINYINT(1) NOT NULL DEFAULT 0,
			currency VARCHAR(5) NOT NULL DEFAULT \'USD\',
			savings_amount VARCHAR(10) NULL,
			savings_percent INT(10) NULL,
			savings_basis VARCHAR(10) NULL,
			features TEXT NULL,
			is_manual TINYINT(1) NOT NULL DEFAULT 0,
			rating VARCHAR(10) NULL DEFAULT 0,
			reviews INT(11) NULL DEFAULT 0,
			last_updated datetime NOT NULL,
			PRIMARY KEY  (amazon_id)
		';
		$sql         = '
			CREATE TABLE ' . $this->get_table_name() . ' (
				' . $columns_sql . '
			) ' . $this->get_charset_collate();

		return $this->modify_table( $sql, $this->get_table_name() );
	}

	/**
	 * Count total products in wp_lasso_amazon_products table
	 */
	public static function count_amazon_product_in_db() {
		$amz_tbl = self::get_sql_query_amazon_products_need_to_be_updated();
		$mysql   = '
			select count(amz_tbl.amazon_id) as count
			from (' . $amz_tbl . ') as amz_tbl
		';

		$result = Model::get_col( $mysql );

		return intval( $result[0] ?? 0 );
	}

	/**
	 * Get mysql query to get amazon products need to be update pricing
	 */
	public static function get_sql_query_amazon_products_need_to_be_updated() {
		$now             = gmdate( 'Y-m-d H:i:s', time() );
		$posts           = Model::get_wp_table_name( 'posts' );
		$amazon_products = new Amazon_Products();
		$url_details     = new Url_Details();
		$sql             = '
			SELECT 
				ap.*
			FROM ' . $url_details->get_table_name() . ' AS ud
				LEFT JOIN ' . $posts . ' AS p
					ON ud.lasso_id = p.ID
				LEFT JOIN ' . $amazon_products->get_table_name() . ' AS ap
					ON ap.amazon_id = ud.product_id
			WHERE p.post_type = %s
				AND p.post_status = %s
				AND ud.product_type = %s
				AND ap.amazon_id IS NOT NULL
				AND ap.last_updated < DATE_SUB(%s, INTERVAL 24 HOUR)
			ORDER BY 
				ap.last_updated ASC
		';
		$prepare         = Model::prepare( $sql, Constant::LASSO_POST_TYPE, 'publish', 'amazon', $now );

		return $prepare;
	}

	/**
	 * Get products in wp_lasso_amazon_products table
	 *
	 * @param int $limit Number of results. Default to 100.
	 */
	public static function get_amazon_product_in_db( $limit = 100 ) {
		$mysql   = self::get_sql_query_amazon_products_need_to_be_updated();
		$mysql  .= '
			LIMIT %d
		';
		$prepare = Model::prepare( $mysql, $limit ); // phpcs:ignore

		return Model::get_results( $prepare, ARRAY_A );
	}
}
