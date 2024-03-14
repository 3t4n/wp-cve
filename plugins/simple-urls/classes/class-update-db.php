<?php
/**
 * Declare class Update_DB
 *
 * @package Update_DB
 */

namespace LassoLite\Classes;

use LassoLite\Classes\Helper;

use LassoLite\Models\Amazon_Products;
use LassoLite\Models\Model;
use LassoLite\Models\Url_Details;
use LassoLite\Models\Revert;

/**
 * Update_DB
 */
class Update_DB {
	/**
	 * Update_DB constructor.
	 */
	public function __construct() {

		if ( ! Helper::is_lite_using_new_ui() ) {
			return;
		}

		// ? Perform any additional schema changes and data updates/upgrades.
		$this->update_lasso_database();

		$this->update_version();
	}

	/**
	 * Get version from database
	 */
	public function get_version() {
		$version = Helper::get_option( 'lasso_version', 100 );
		$version = floatval( $version );

		return $version;
	}

	/**
	 * Set version and save it to the option table
	 */
	public function update_version() {
		Helper::update_option( 'lasso_version', LASSO_LITE_VERSION );
	}

	/**
	 * Create all Lasso Lite tables
	 */
	public static function create_tables() {
		( new Amazon_Products() )->create_table();
		( new Url_Details() )->create_table();
		( new Revert() )->create_table();
	}


	/**
	 * Update database structure for new version
	 */
	public function update_lasso_database() {
		$version = $this->get_version();

		if ( $version < 108 ) {
			$this->create_tables();
		}

		if ( $version < 113 ) {
			( new Amazon_Products() )->create_table();
			$this->update_rating_and_review_data_from_aawp();
		}
	}

	/**
	 * Update rating and reviews data from AAWP product table to Lasso amazon table, so we can support aawp fields shortcode
	 */
	public function update_rating_and_review_data_from_aawp() {
		$lite_amazon_product_table = ( new Amazon_Products() )->get_table_name();
		$aawp_product_table        = Model::get_wp_table_name( 'aawp_products' );
		if ( Model::table_exists( $aawp_product_table ) ) {
			$sql = '
				UPDATE ' . $lite_amazon_product_table . ' AS lap
				INNER JOIN ' . $aawp_product_table . ' AS aawpp ON lap.amazon_id = aawpp.asin
				SET lap.rating = IF(aawpp.rating IS NOT NULL, aawpp.rating, lap.rating),
					lap.reviews = IF(aawpp.reviews IS NOT NULL, aawpp.reviews, lap.reviews)
			';

			Model::query( $sql );
		}
	}
}
