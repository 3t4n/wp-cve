<?php
/**
 * Declare class DB
 *
 * @package DB
 */

namespace LassoLite\Classes;

use LassoLite\Classes\Setting_Enum;

use LassoLite\Admin\Constant;
use LassoLite\Models\Url_Details;
use LassoLite\Models\Model;
use LassoLite\Models\Revert;

/**
 * Lasso_DB
 */
class Lasso_DB {
	/**
	 * Construction of Lasso_DB
	 */
	public function __construct() {
		global $wpdb;

		$this->dbname         = $wpdb->dbname;
		$this->prefix         = $wpdb->prefix;
		$this->current_domain = str_replace( 'https://', '', str_replace( 'http://', '', strtolower( get_site_url() ) ) );

		// ? WP Tables
		$this->posts              = $wpdb->posts;
		$this->postmeta           = $wpdb->postmeta;
		$this->terms              = $wpdb->terms;
		$this->term_taxonomy      = $wpdb->term_taxonomy;
		$this->term_relationships = $wpdb->term_relationships;
		$this->options            = $wpdb->options;

		// ? Lasso Tables
		$this->amazon_products = $wpdb->prefix . Constant::LASSO_AMAZON_PRODUCTS_DB;

		// ? Import Tables (external products)
		$this->pretty_links = $wpdb->prefix . 'prli_links';
		$this->aawp         = $wpdb->prefix . 'aawp_products';
		$this->aawp_list    = $wpdb->prefix . 'aawp_lists';
	}

	/**
	 * Get url detail
	 *
	 * @param int $lasso_id Lasso post id.
	 */
	public function get_url_details( $lasso_id ) {
		$sql = '
			SELECT * 
			FROM ' . ( new Url_Details() )->get_table_name() . ' 
			WHERE lasso_id = %s
		';
		$sql = Url_Details::prepare( $sql, $lasso_id );

		return $this->get_row( $sql );
	}

	/**
	 * Run query
	 *
	 * @param string $sql Sql query.
	 */
	public function query( $sql ) {
		global $wpdb;

		$results = $wpdb->query( $sql ); // phpcs:ignore
		$this->log_error( $wpdb->last_error );

		return $results;
	}

	/**
	 * Get row
	 * Get row from cache if existed
	 *
	 * @param string  $sql    Sql query.
	 * @param string  $output Type of results.
	 * @param boolean $is_use_cache    Is use cache.
	 */
	public function get_row( $sql, $output = 'OBJECT', $is_use_cache = false ) {
		$results      = false;
		$cache_string = md5( trim( (string) $sql ) . $output );

		if ( $is_use_cache ) {
			$results = Cache_Per_Process::get_instance()->get_cache( $cache_string );
		}

		if ( false === $results ) {
			global $wpdb;

			$results = $wpdb->get_row( $sql, $output ); // phpcs:ignore
			$this->log_error( $wpdb->last_error );

			if ( $is_use_cache ) {
				Cache_Per_Process::get_instance()->set_cache( $cache_string, $results );
			}
		}

		return $results;
	}

	/**
	 * Get var
	 * Get var from cache if existed
	 *
	 * @param string  $sql Sql query.
	 * @param boolean $is_use_cache    Is use cache.
	 */
	public function get_var( $sql, $is_use_cache = false ) {
		$results      = false;
		$cache_string = md5( trim( (string) $sql ) );

		if ( $is_use_cache ) {
			$results = Cache_Per_Process::get_instance()->get_cache( $cache_string );
		}

		if ( false === $results ) {
			global $wpdb;

			$results = $wpdb->get_var( $sql ); // phpcs:ignore
			$this->log_error( $wpdb->last_error );

			if ( $is_use_cache ) {
				Cache_Per_Process::get_instance()->set_cache( $cache_string, $results );
			}
		}

		return $results;
	}

	/**
	 * Get col
	 * Get col from cache if existed
	 *
	 * @param string  $sql Sql query.
	 * @param boolean $is_use_cache    Is use cache.
	 */
	public function get_col( $sql, $is_use_cache = false ) {
		$results      = false;
		$cache_string = md5( trim( (string) $sql ) );

		if ( $is_use_cache ) {
			$results = Cache_Per_Process::get_instance()->get_cache( $cache_string );
		}

		if ( false === $results ) {
			global $wpdb;

			$results = $wpdb->get_col( $sql ); // phpcs:ignore
			$this->log_error( $wpdb->last_error );

			if ( $is_use_cache ) {
				Cache_Per_Process::get_instance()->set_cache( $cache_string, $results );
			}
		}

		return $results;
	}

	/**
	 * Print error log message to log file
	 *
	 * @param string $error Error message.
	 */
	public function log_error( $error ) {
		if ( ! empty( $error ) ) {
			if ( strpos( $error, 'Illegal mix of collations' ) !== false
				|| strpos( $error, 'Unknown column' ) !== false
			) {
				Update_DB::create_tables();
			}

			// ? Add force write log for lasso_debug, to see what happen when Lasso call query.
			trigger_error( $error, E_USER_NOTICE ); // phpcs:ignore
		}
	}

	/**
	 * Get lasso post id by product id and product_type
	 *
	 * @param string $product_id   Product id.
	 * @param string $product_type Product type. Default is amazon.
	 */
	public function get_lasso_id_by_product_id_and_type( $product_id, $product_type = Amazon_Api::PRODUCT_TYPE ) {
		global $wpdb;

		if ( ! $product_id ) {
			return false;
		}

		$sql = '
			SELECT DISTINCT lud.lasso_id as post_id
			FROM ' . $this->posts . ' as wpp
				LEFT JOIN ' . ( new Url_Details() )->get_table_name() . ' as lud
				ON wpp.id = lud.lasso_id
			WHERE wpp.post_type = %s 
				AND lud.product_id = %s 
				AND lud.product_type = %s 
				AND wpp.post_status = "publish"
		';

		$prepare = $wpdb->prepare( $sql, Constant::LASSO_POST_TYPE, $product_id, $product_type ); // phpcs:ignore
		$post    = $this->get_row( $prepare );

		if ( $post && get_post( $post->post_id ) ) {
			return $post->post_id;
		}

		return false;
	}

	/**
	 * Update data in url details table
	 *
	 * @param int    $lasso_id       Lasso post id.
	 * @param string $redirect_url   Redirect url.
	 * @param string $base_domain    Base domain.
	 * @param int    $is_opportunity Is Opportunity.
	 * @param string $product_id     Product id. Default to empty.
	 * @param string $product_type   Product type. Default to empty.
	 */
	public function update_url_details( $lasso_id, $redirect_url, $base_domain, $is_opportunity, $product_id = '', $product_type = '' ) {
		$url_detail_model = new Url_Details();
		$redirect_url     = trim( $redirect_url );
		$redirect_url     = addcslashes( $redirect_url, "'" );
		$sql              = '
			INSERT INTO ' . $url_detail_model->get_table_name() . ' (lasso_id, redirect_url, base_domain, is_opportunity, product_id, product_type)
			VALUES (%d, %s, %s, %d, %s, %s) 
			ON DUPLICATE KEY
				UPDATE
					redirect_url = %s,
					base_domain = %s,
					is_opportunity = %d,
					product_id = %s,
					product_type = %s
		';
		$prepare          = Url_Details::prepare(
			$sql,
			// ? insert
			$lasso_id,
			$redirect_url,
			$base_domain,
			$is_opportunity,
			$product_id,
			$product_type,
			// ? on duplicate update
			$redirect_url,
			$base_domain,
			$is_opportunity,
			$product_id,
			$product_type
		);

		Url_Details::query( $prepare );

		// ? Unset deprecated cache
		Cache_Per_Process::get_instance()->un_set( Amazon_Api::OBJECT_KEY . '_' . Amazon_Api::FUNCTION_NAME_GET_LASSO_ID_BY_PRODUCT_ID_AND_TYPE . '_' . $product_id . '_' . $product_type );
	}

	/**
	 * Get importable url query
	 *
	 * @param bool   $include_imported Include imported or not. Default to true.
	 * @param string $search           Search text. Default to empty.
	 * @param string $group_by         Group by column. Default to empty.
	 * @param string $filter_plugin    Plugin name.
	 */
	public function get_importable_urls_query( $include_imported = true, $search = '', $group_by = '', $filter_plugin = null ) {
		$is_prlipro_installed = $this->is_pretty_links_pro_installed();
		$is_aawp_installed    = $this->is_aawp_installed();
		$posts_tbl            = Model::get_wp_table_name( 'posts' );
		$postmeta_tbl         = Model::get_wp_table_name( 'postmeta' );
		$pretty_link_tbl      = Model::get_wp_table_name( 'prli_links' );

		$post_status_allow = "('publish', 'pending', 'draft', 'future', 'private', 'inherit', 'trash')";

		if ( '' === $group_by ) {
			$group_by = 'BASE.import_source, BASE.id, BASE.post_type, BASE.post_name, BASE.post_title, BASE.check_status, BASE.check_disabled';
			$select   = '*';
			$order_by = 'BASE.check_status, BASE.import_source, BASE.post_title';
		} else {
			$select   = $group_by;
			$order_by = $group_by;
		}

		if ( ! $filter_plugin ) {
			$where = '';
		} else {
			$where = 'AND import_source = %s';
			$where = Model::prepare( $where, $filter_plugin );
		}

		$support_plugin      = Setting_Enum::SUPPORT_IMPORT_PLUGINS;
		$support_plugin_flip = array_flip( $support_plugin );
		$sql                 = '';

		$revert_table = ( new Revert() )->get_table_name();

		// ? SQL pre-processing
		if ( $is_prlipro_installed && $support_plugin[ Setting_Enum::PRETTY_LINK_SLUG ] === $filter_plugin ) {
			$prlipro_post_name = "
				CASE
					WHEN p.post_type = 'pretty-link'
						THEN CONVERT(pl.slug USING utf8)
					ELSE CONVERT(p.post_name USING utf8)
				END as post_name,
			";
			$prlipro_join      = '
				LEFT JOIN
					' . $this->pretty_links . " as pl
						ON p.post_type = 'pretty-link'
						AND p.id = pl.link_cpt_id
			";
		} else {
			$prlipro_post_name = 'CONVERT(p.post_name USING utf8) as post_name,';
			$prlipro_join      = '';
		}

		// ? Start SQL Statement
		// AAWP plugin
		if ( ( $is_aawp_installed && empty( $filter_plugin ) ) || ( $support_plugin[ Setting_Enum::AAWP_SLUG ] === $filter_plugin ) ) {
			// ? aawp products
			$sql .= "
				SELECT
					CONVERT(asin USING utf8) AS id,
					'aawp' AS post_type,
					'AAWP' AS import_source,
					CONVERT(asin USING utf8) AS post_name,
					CONVERT(title USING utf8) AS post_title,
					'' AS check_status,
					'' AS check_disabled
				FROM
						" . $this->aawp . ' AS ap
					LEFT JOIN
						' . $revert_table . ' AS r
							ON CONVERT(ap.asin USING utf8) = CONVERT(r.old_uri USING utf8)
				WHERE
					r.old_uri IS NULL

				UNION
			';

			// ? aawp lists
			$sql .= "
				SELECT
					id,
					'aawp_list' AS post_type,
					'AAWP' AS import_source,
					type AS post_name,
					keywords AS post_title,
					'' AS check_status,
					'' AS check_disabled
				FROM " . $this->aawp_list . ' AS ap

				UNION
			';
		}

		// ? Earnist plugin
		$sql = $sql . "
			SELECT
				p.id,
				p.post_type,
				'Earnist' AS import_source,
				CONVERT(p.post_name USING utf8) as post_name,
				CONVERT(p.post_title USING utf8) AS post_title,
				'' AS check_status,
				'' AS check_disabled
			FROM " . $posts_tbl . " AS p
			WHERE
				post_type = 'earnist'
				AND p.ID NOT IN (
					SELECT post_id
					FROM " . $postmeta_tbl . "
					WHERE meta_key = 'old_status'
						AND meta_value != ''
				)
				AND post_status IN " . $post_status_allow;

		// ? Pretty Links plugin
		if ( $is_prlipro_installed && ( empty( $filter_plugin ) || $support_plugin[ Setting_Enum::PRETTY_LINK_SLUG ] === $filter_plugin ) ) {
			$sql = $sql . "
				UNION

				SELECT
					p.id,
					p.post_type,
					'Pretty Links' AS import_source,
					" . $prlipro_post_name . "
					CONVERT(p.post_title USING utf8) AS post_title,
					'' AS check_status,
					'' AS check_disabled
				FROM " . $posts_tbl . ' AS p
					' . $prlipro_join . "
				WHERE
					post_type = 'pretty-link'
					AND p.ID NOT IN (
						SELECT post_id
						FROM " . $postmeta_tbl . "
						WHERE meta_key = 'old_status'
							AND meta_value != ''
					)
					AND p.ID IN (
						SELECT link_cpt_id
						FROM " . $pretty_link_tbl . '
					)
					AND post_status IN ' . $post_status_allow;
		}

		// ? Easyazon plugin
		if ( empty( $filter_plugin ) || $support_plugin[ Setting_Enum::EASYAZON_SLUG ] === $filter_plugin ) {
			$sql = $sql . "
				UNION
	
				SELECT DISTINCT
					CONVERT(substring_index( substring_index(option_name, 'easyazon_item_', -1), '_', 1) USING utf8) AS id,
					'easyazon' AS post_type,
					'EasyAzon' AS import_source,
					'' AS post_name,
					CONVERT(substring_index( option_name, '_', 4) USING utf8) AS post_title,
					CASE
						WHEN CONVERT(substring_index( substring_index(option_name, 'easyazon_item_', -1), '_', 1) USING utf8) IN (
							SELECT CONVERT(old_uri USING utf8) AS old_uri
							FROM " . $revert_table . "
							WHERE plugin = 'easyazon'
						)
						THEN 'checked'
						ELSE ''
					END AS check_status,
					'' AS check_disabled
				FROM " . $this->options . " AS ap
				WHERE option_name LIKE 'easyazon_item_%'
			";
		}

		// ? Easy Affiliate Link plugin - EAL is having 2 types "HTML code" and "Text Link", we only get "text" value.
		if ( empty( $filter_plugin ) || $support_plugin[ Setting_Enum::EASY_AFFILIATE_LINK_SLUG ] === $filter_plugin ) {
			$sql = $sql . "
				UNION
	
				SELECT
					po.ID as id,
					po.post_type,
					'Easy Affiliate Links' as import_source,
					CONVERT(po.post_name USING utf8) as post_name,
					CONVERT(po.post_title USING utf8) as post_title,
					'' as check_status,
					'' as check_disabled
				FROM " . $this->posts . ' as po
				INNER JOIN ' . $this->postmeta . ' as pom ON po.ID = pom.post_id
				WHERE po.post_type = %s 
					AND pom.meta_value = %s
			';

			$sql = Model::prepare( $sql, Setting_Enum::EASY_AFFILIATE_LINK_SLUG, 'text' );
		}

		// ? Affiliate URL Automation plugin
		if ( empty( $filter_plugin ) || $support_plugin[ Setting_Enum::AFFILIATE_URL_SLUG ] === $filter_plugin ) {
			$sql = $sql . "
				UNION

				SELECT
					po.ID AS id,
					po.post_type,
					'Affiliate URLs' AS import_source,
					CONVERT(po.post_name USING utf8) AS post_name,
					CONVERT(po.post_title USING utf8) AS post_title,
					'' AS check_status,
					'' AS check_disabled
				FROM " . $this->posts . ' AS po
					INNER JOIN ' . $this->postmeta . ' AS pom 
					ON po.ID = pom.post_id
				WHERE po.post_type = %s
					AND pom.meta_key = %s
					AND pom.meta_value IS NOT NULL
					AND pom.meta_value <> ""
			';

			$sql = Model::prepare( $sql, Setting_Enum::AFFILIATE_URL_SLUG, '_affiliate_url_redirect' );
		}

		// ? Thirsty Affiliates plugin
		if ( empty( $filter_plugin ) || $support_plugin[ Setting_Enum::THIRSTYLINK_SLUG ] === $filter_plugin ) {
			$sql = $sql . "
				UNION
	
				SELECT
					po.ID AS id,
					po.post_type,
					'Thirsty Affiliates' AS import_source,
					CONVERT(po.post_name USING utf8) AS post_name,
					CONVERT(po.post_title USING utf8) AS post_title,
					'' AS check_status,
					'' AS check_disabled
				FROM " . $this->posts . ' AS po
				WHERE po.post_type = %s 
					AND po.ID IN (
						SELECT post_id
						FROM ' . $this->postmeta . '
						WHERE meta_key = %s
							AND meta_value IS NOT NULL
							AND meta_value <> ""
					)
			';

			$sql = Model::prepare( $sql, Setting_Enum::THIRSTYLINK_SLUG, '_ta_destination_url' );
		}

		// ? Lasso Pro plugin
		$url_details_table = Model::get_wp_table_name( 'lasso_url_details' );
		if ( Model::table_exists( $url_details_table ) && ( empty( $filter_plugin ) || $support_plugin[ Setting_Enum::LASSO_PRO_SLUG ] === $filter_plugin ) ) {
			$sql = $sql . "
				UNION
	
				SELECT
					po.ID as id,
					po.post_type,
					'Lasso Pro' as import_source,
					CONVERT(po.post_name USING utf8) as post_name,
					CONVERT(po.post_title USING utf8) as post_title,
					'' as check_status,
					'' as check_disabled
				FROM " . $this->posts . ' as po
				INNER JOIN ' . $url_details_table . ' AS lud ON lud.lasso_id = po.ID 
				WHERE po.post_type = %s 
					AND lud.redirect_url IS NOT NULL
					AND lud.redirect_url <> ""
			';

			$sql = Model::prepare( $sql, Setting_Enum::LASSO_PRO_SLUG );
		}

		if ( $include_imported ) {
			if ( in_array(
				$filter_plugin,
				array(
					$support_plugin[ Setting_Enum::PRETTY_LINK_SLUG ],
					$support_plugin[ Setting_Enum::THIRSTYLINK_SLUG ],
					$support_plugin[ Setting_Enum::EARNIST_SLUG ],
					$support_plugin[ Setting_Enum::AFFILIATE_URL_SLUG ],
					$support_plugin[ Setting_Enum::AAWP_SLUG ],
					$support_plugin[ Setting_Enum::EASY_AFFILIATE_LINK_SLUG ],
					$support_plugin[ Setting_Enum::LASSO_PRO_SLUG ],
				),
				true
			)
			) {
				$r_plugin_where = "r.plugin IN ('%s')";
				$r_plugin_where = sprintf( $r_plugin_where, $support_plugin_flip[ $filter_plugin ] );
			} else {
				$r_plugin_where = "r.plugin IN ('%s', '%s', '%s', '%s', '%s', '%s', '%s')";
				$r_plugin_where = sprintf(
					$r_plugin_where,
					Setting_Enum::PRETTY_LINK_SLUG,
					Setting_Enum::THIRSTYLINK_SLUG,
					Setting_Enum::EARNIST_SLUG,
					Setting_Enum::AFFILIATE_URL_SLUG,
					Setting_Enum::AAWP_SLUG,
					Setting_Enum::EASY_AFFILIATE_LINK_SLUG,
					Setting_Enum::LASSO_PRO_SLUG,
				);
			}
			$sql = $sql . "
				UNION

				SELECT
					p.id,
					p.post_type,
					CASE
						WHEN r.plugin = 'pretty-link'
							THEN 'Pretty Links'
						WHEN r.plugin = 'thirstylink'
							THEN 'Thirsty Affiliates'
						WHEN r.plugin = 'earnist'
							THEN 'Earnist'
						WHEN r.plugin = 'affiliate_url'
							THEN 'Affiliate URLs'
						WHEN r.plugin = 'aawp'
							THEN 'AAWP'
						WHEN r.plugin = 'easyazon'
							THEN 'EasyAzon'
						WHEN r.plugin = 'amalinks'
							THEN 'Amalinks Pro'
						WHEN r.plugin = 'easy_affiliate_link'
							THEN 'Easy Affiliate Links'
						WHEN r.plugin = '" . Setting_Enum::LASSO_PRO_SLUG . "'
							THEN 'Lasso Pro'
						ELSE 'Unknown'
					END as import_source,
					CASE
						WHEN r.plugin = 'aawp'
							THEN CONVERT(r.old_uri USING utf8)
						ELSE CONVERT(p.post_name USING utf8)
					END as post_name,
					CONVERT(p.post_title USING utf8) as post_title,
					'checked' as check_status,
					'' as check_disabled
				FROM " . $this->posts . ' as p
					INNER JOIN
						' . $revert_table . " as r
					ON p.id = r.lasso_id
				WHERE
					$r_plugin_where
			";

			// ? AmaLinks Pro plugin - imported
			if ( empty( $filter_plugin ) || $support_plugin[ Setting_Enum::AFFILIATE_URL_SLUG ] === $filter_plugin ) {
				$sql = $sql . "
					UNION
	
					SELECT DISTINCT
						CONVERT(r.lasso_id USING utf8) as id,
						'amalinkspro' as post_type,
						'AmaLinks Pro' as import_source,
						CASE
							WHEN CONVERT(la.monetized_url USING utf8) != ''
								THEN CONVERT(la.monetized_url USING utf8)
							WHEN CONVERT(wpp.guid USING utf8) != ''
								THEN CONVERT(wpp.guid USING utf8)
							ELSE ''
						END as post_name,
						CASE
							WHEN CONVERT(la.default_product_name USING utf8) != ''
								THEN CONVERT(la.default_product_name USING utf8)
							WHEN CONVERT(wpp.post_title USING utf8) != ''
								THEN CONVERT(wpp.post_title USING utf8)
							ELSE ''
						END as post_title,
						'checked' as check_status,
						'' as check_disabled
					FROM " . $revert_table . ' as r
					LEFT JOIN ' . $this->posts . ' as wpp
					ON r.lasso_id = wpp.ID
					LEFT JOIN ' . $this->amazon_products . " as la
					ON r.old_uri = la.amazon_id
					WHERE r.plugin = 'amalinkspro'
				";
			}
		}

		$include_imported_where = $include_imported ? '' : 'AND check_status != "checked"';

		$sql = '
			SELECT ' . $select . '
			FROM
				(
					' . $sql . '
				) as BASE
			WHERE
				1=1
				' . $where . '
				' . $include_imported_where . '
				' . $search . '
			GROUP BY ' . $group_by . '
			ORDER BY ' . $order_by;

		return $sql;
	}

	/**
	 * Check whether pretty links pro plugin is install or not
	 */
	public function is_pretty_links_pro_installed() {
		return Model::column_exists( $this->pretty_links, 'link_cpt_id' );
	}

	/**
	 * Check whether aawp plugin is installed or not
	 */
	public function is_aawp_installed() {
		return Model::table_exists( $this->aawp );
	}

	/**
	 * Get pretty link by id
	 *
	 * @param int $id Id of pretty link.
	 */
	public function get_pretty_link_by_id( $id ) {
		$sql = '
			SELECT *
			FROM ' . $this->pretty_links . '
			WHERE link_cpt_id = ' . $id . ';
		';

		return $this->get_row( $sql );
	}

	/**
	 * Get aawp product
	 *
	 * @param string $product_id Amazon product id.
	 */
	public function get_aawp_product( $product_id ) {
		$sql = '
			SELECT *
			FROM ' . $this->aawp . '
			WHERE asin = %s';
		$sql = Model::prepare( $sql, $product_id );

		$row = $this->get_row( $sql );

		if ( $row ) {
			$lasso_amazon_api = new Amazon_Api();

			$row_url = maybe_unserialize( $row->urls ?? $row->url ?? '' );
			$url     = $row_url['basic'] ?? $row_url;
			$url     = is_string( $url ) ? $url : '';

			$row->url = $lasso_amazon_api->get_amazon_product_url( $url, true, false );

			$images    = $row->image_ids;
			$images    = explode( ',', $images );
			$image_id  = $images[0];
			$image_url = 'https://m.media-amazon.com/images/I/' . $image_id . '.jpg';

			$row->image_url = $image_url;
			$row->features  = maybe_unserialize( $row->features ?? array() ); // phpcs:ignore
		}

		return $row;
	}

	/**
	 * Get aawp lists
	 *
	 * @param string $id Id.
	 */
	public function get_aawp_list( $id ) {
		$sql = '
			SELECT *
			FROM ' . $this->aawp_list . '
			WHERE id = %s';
		$sql = Model::prepare( $sql, $id );

		return $this->get_row( $sql );
	}

	/**
	 * Check EasyAzon product id is imported or not
	 *
	 * @param string $asin Amazon product id.
	 */
	public function is_easyazon_product_imported( $asin ) {
		global $wpdb;

		$sql     = '
			select id, lasso_id
			from ' . ( new Revert() )->get_table_name() . '
			where old_uri = %s and plugin = \'easyazon\'
		';
		$prepare = $wpdb->prepare( $sql, $asin ); // phpcs:ignore

		return $this->get_row( $prepare );
	}

	/**
	 * Get url detail by product id (Amazon/Extend product)
	 *
	 * @param string $product_id   Product id.
	 * @param string $product_type Product type.
	 */
	public function get_url_details_by_product_id( $product_id, $product_type ) {
		if ( ! $product_id ) {
			return null;
		}

		global $wpdb;

		$sql     = '
			SELECT * 
			FROM ' . ( new Url_Details() )->get_table_name() . ' as lud
			LEFT JOIN ' . $this->posts . ' as wpp
			ON lud.lasso_id = wpp.ID
			WHERE lud.product_id = %s 
				AND wpp.ID is not null 
				AND wpp.post_status = "publish" 
				AND wpp.post_type = %s 
				AND lud.product_type = %s
			ORDER BY lasso_id desc
		';
		$prepare = $wpdb->prepare( $sql, $product_id, Constant::LASSO_POST_TYPE, $product_type ); // phpcs:ignore

		return $this->get_row( $prepare );
	}

	/**
	 * Process import
	 *
	 * @param int    $id      Post id.
	 * @param string $slug    Link slug.
	 * @param string $old_uri Old URI.
	 * @param string $plugin  Plugin name.
	 */
	public function process_import( $id, $slug, $old_uri, $plugin ) {
		if ( empty( $id ) || empty( $slug ) ) {
			return false;
		}

		global $wpdb;
		clean_post_cache( $id );

		$result1 = true;
		if ( ! in_array( $plugin, array( 'aawp', 'amalinkspro', 'easyazon' ), true ) ) {
			// ? Flip post time and potentially the slug
			$update_sql = '
				UPDATE ' . $this->posts . '
				SET
					post_name = %s,
					post_type = %s,
					post_modified = NOW(),
					post_modified_gmt = NOW()
				WHERE ID = %d;
			';
			$update_sql = $wpdb->prepare( $update_sql, $slug, SIMPLE_URLS_SLUG, $id ); // phpcs:ignore
			Model::query( $update_sql );
			$result1 = SIMPLE_URLS_SLUG === get_post_type( $id );
		}

		// ? Log what we imported for potential reverts
		$insert_sql = '
			INSERT INTO ' . ( new Revert() )->get_table_name() . ' (lasso_id, old_uri, plugin, revert_dt)
			VALUES (%d, %s, %s, NOW());
		';
		$prepare    = Model::prepare( $insert_sql, $id, $old_uri, $plugin );
		$result2    = Model::query( $prepare );

		clean_post_cache( $id );

		return $result1 && $result2;
	}

	/**
	 * Process revert
	 *
	 * @param int  $id               Post id.
	 * @param bool $custom_post_type It is custom post type or not. Default to true.
	 */
	public function process_revert( $id, $custom_post_type = true ) {
		// ? Get post type from revert table
		$result1 = true;
		if ( empty( $id ) ) {
			return false;
		}

		if ( $custom_post_type ) {
			$revert_data = $this->get_revert_by_id( $id );
			$post_type   = get_post_type( $id );
			if ( ! empty( $revert_data ) && Constant::LASSO_POST_TYPE === $post_type ) {
				// ? Switch back
				if ( isset( $revert_data->plugin ) && 'pretty-link' === $revert_data->plugin ) {
					$pretty_link_data = $this->get_pretty_link_by_id( $id );
					$update_sql       = '
						UPDATE ' . $this->posts . '
						SET
							post_name = %s,
							post_type = %s
						WHERE id = %d;
					';
					$update_sql       = Model::prepare( $update_sql, $pretty_link_data->slug, $revert_data->plugin, $id );
				} else {
					$update_sql = '
						UPDATE ' . $this->posts . '
						SET post_type = %s
						WHERE id = %d;
					';
					$update_sql = Model::prepare( $update_sql, $revert_data->plugin, $id );
				}
				$result1 = Model::query( $update_sql );
			}
		}

		// ? Delete tracking record
		$delete_sql = '
			DELETE FROM ' . ( new Revert() )->get_table_name() . '
			WHERE lasso_id = %d;
		';
		$delete_sql = Model::prepare( $delete_sql, $id );
		$result2    = Model::query( $delete_sql );

		clean_post_cache( $id );

		return $result1 && $result2;
	}

	/**
	 * Get revert by id
	 *
	 * @param int $id Id in revert table.
	 */
	public function get_revert_by_id( $id ) {
		$sql = '
			SELECT * 
			FROM ' . ( new Revert() )->get_table_name() . '
			WHERE lasso_id = %d;
		';
		$sql = Model::prepare( $sql, $id );

		return Model::get_row( $sql );
	}

	/**
	 * Delete url detail
	 *
	 * @param int $lasso_id Lasso post id.
	 */
	public function delete_url_details( $lasso_id ) {
		$sql = '
			DELETE FROM ' . ( new Url_Details() )->get_table_name() . ' 
			WHERE lasso_id = %d';
		$sql = Model::prepare( $sql, $lasso_id );

		return Model::query( $sql );
	}

	/**
	 * Get EasyAzon product
	 *
	 * @param string $asin Amazon product id.
	 */
	public function get_easyazon_product( $asin ) {
		if ( ! $asin ) {
			return null;
		}

		$search  = 'easyazon_item_' . $asin . '%';
		$sql     = '
			select option_value
			from ' . $this->options . '
			where option_name like %s
			order by option_id desc
			limit 1
		';
		$prepare = Model::prepare( $sql, $search ); // phpcs:ignore

		return Model::get_row( $prepare );
	}

	/**
	 * Remove: all background processes of Lasso
	 */
	public function remove_all_lasso_processes() {
		$sql = '
			DELETE FROM ' . $this->options . "
			WHERE option_name like '%lassolite_%_batch_%'
		";
		$this->query( $sql );
	}

	/**
	 * Check whether the process is empty
	 */
	public function check_empty_process() {
		$count_query = '
			SELECT count(option_id) as `total`
			FROM ' . $this->options . "
			WHERE 
				option_name LIKE '%lassolite_%_batch_%' 
				AND option_value LIKE 'a:1:{i:0;i:%'
		";
		$total       = $this->get_row( $count_query );
		$total       = $total->total ?? 0;
		$total       = intval( $total );

		// ? delete empty processes if there are more 10 empty processes
		if ( $total > 10 ) {
			$this->remove_empty_process();
		}
	}

	/**
	 * Remove: empty process
	 */
	public function remove_empty_process() {
		$sql = '
			DELETE FROM ' . $this->options . "
			WHERE 
				option_name LIKE '%lassolite_%_batch_%' 
				AND option_value LIKE 'a:1:{i:0;i:%'
		";
		$this->query( $sql );
	}

	/**
	 * Paginate items by a sql query
	 *
	 * @param string $sql   Sql query.
	 * @param int    $page  Number of page.
	 * @param int    $limit Number of results. Default to 10.
	 */
	public function paginate( $sql, $page, $limit = 10 ) {
		$start_index = ( $page - 1 ) * $limit;
		return $sql . ' LIMIT ' . $start_index . ', ' . $limit;
	}

	/**
	 * Get revertable url query
	 *
	 * @param string $filter_plugin Plugin name. Default to null (all plugins).
	 */
	public function get_revertable_urls_query( $filter_plugin = null ) {
		$key = array_search( $filter_plugin, Setting_Enum::SUPPORT_IMPORT_PLUGINS, true );

		if ( false === $key ) {
			$where = "`plugin` IN ('pretty-link', 'thirstylink', 'earnist', 'affiliate_url', 'aawp', 'easyazon', 'amalinkspro', '" . Setting_Enum::LASSO_PRO_SLUG . "')";
		} else {
			$where = '`plugin` = %s';
			$where = Model::prepare( $where, $key );
		}

		$sql = '
			SELECT lasso_id AS import_id, `plugin` AS import_source
			FROM ' . ( new Revert() )->get_table_name() . '
			WHERE ' . $where . '
		';

		return $sql;
	}

	/**
	 * Get easyazon option by option_name
	 *
	 * @param string $option_name A part of option name.
	 * @return mixed
	 */
	public static function get_easyazon_option( $option_name ) {
		$sql = '
			SELECT option_value
			FROM ' . Model::get_wp_table_name( 'options' ) . '
			WHERE option_name LIKE %s
		';

		$option_name = Helper::esc_like_query( $option_name, true );
		$sql         = Model::prepare( $sql, $option_name );

		$result = Model::get_var( $sql );
		$result = maybe_unserialize( $result );

		return $result;
	}

	/**
	 * Get import plugin options to filter
	 *
	 * @param bool $get_count Get result count.
	 * @return array|int|mixed
	 */
	public function get_import_plugins( $get_count = false ) {
		$sql = $this->get_importable_urls_query( true, '', 'BASE.import_source' );

		if ( $get_count ) {
			return Model::get_count( $sql );
		}

		$import_results = Model::get_results( $sql );

		$result = array();

		foreach ( $import_results as $import_result ) {
			$result[] = $import_result->import_source;
		}

		return $result;
	}


	/**
	 * Get lasso lite post by uri
	 *
	 * @param string $uri Uri.
	 */
	public function get_lasso_by_uri( $uri ) {
		if ( empty( $uri ) ) {
			return null;
		}

		global $wpdb;

		$explode   = explode( '/', $uri );
		$post_name = end( $explode );

		$sql     = '
			SELECT * 
			FROM ' . $this->posts . ' 
			WHERE post_name = %s AND post_type = %s
		';
		$prepare = $wpdb->prepare( $sql, $post_name, Constant::LASSO_POST_TYPE ); // phpcs:ignore

		return $this->get_row( $prepare, 'OBJECT', true );
	}

	/**
	 * Get url detail by url
	 *
	 * @param string $url URL.
	 */
	public function get_url_details_by_url( $url ) {
		global $wpdb;
		$sql     = '
			SELECT 
				lud.* 
			FROM 
				' . ( new Url_Details() )->get_table_name() . ' AS lud
			LEFT JOIN 
				' . $this->posts . " AS wpp
			ON 
				lud.lasso_id = wpp.ID
			WHERE 
				redirect_url LIKE %s 
				AND wpp.ID != ''
		";
		$prepare = $wpdb->prepare( $sql, $url ); // phpcs:ignore

		return $this->get_row( $prepare, 'OBJECT', true );
	}

	/**
	 * Check whether an amazon product existed in DB or not
	 *
	 * @param string $product_id Amazon product id.
	 */
	public function check_amazon_product_exist( $product_id ) {
		global $wpdb;

		$query = '
			SELECT `amazon_id`
			FROM ' . $this->amazon_products . '
			WHERE `amazon_id` = %s
		';

		$prepare = $wpdb->prepare( $query, $product_id ); // phpcs:ignore

		return $this->get_row( $prepare, ARRAY_A );
	}

	/**
	 * Get lasso lite id from url by checking the post-meta key '_surl_redirect'
	 *
	 * @param string $url Url.
	 * @return int
	 */
	public static function get_lasso_lite_id_by_url_from_post_meta( $url ) {
		$url_without_slash_at_the_end       = rtrim( $url, '/' );
		$url_with_slash_at_the_end_wildcard = rtrim( $url, '/' ) . '_';

		$sql = '
			SELECT p.ID
			FROM ' . Model::get_wp_table_name( 'posts' ) . ' AS p
			INNER JOIN ' . Model::get_wp_table_name( 'postmeta' ) . ' AS pm 
				ON pm.post_id = p.ID
			WHERE p.post_type = %s
				AND pm.meta_key = %s
				AND ( pm.meta_value = %s OR pm.meta_value LIKE %s )
		';
		$sql = Model::prepare( $sql, Constant::LASSO_POST_TYPE, '_surl_redirect', $url_without_slash_at_the_end, $url_with_slash_at_the_end_wildcard );

		$lasso_id = Model::get_var( $sql );

		return intval( $lasso_id );
	}

	/**
	 * Get Lasso Pro amazon product
	 *
	 * @param string $product_id Amazon product id.
	 */
	public function get_lasso_pro_amazon_product( $product_id ) {
		$sql = '
			SELECT amazon_id, base_url, default_image AS image_url, default_product_name, latest_price, is_prime, 
					currency, savings_amount, savings_percent, savings_basis, out_of_stock, last_updated, rating, reviews, features
			FROM ' . Model::get_wp_table_name( 'lasso_amazon_products' ) . '
			WHERE amazon_id = %s';
		$sql = Model::prepare( $sql, $product_id );

		$row = $this->get_row( $sql );

		if ( $row ) {
			$lasso_amazon_api = new Amazon_Api();
			$row->url         = $lasso_amazon_api->get_amazon_product_url( $row->base_url, true, false );
			$row->features    = $row->features ? json_decode( $row->features ) : array();
			$row->quantity    = 0 === intval( $row->out_of_stock ) ? 200 : 0;
		}

		return $row;
	}
}
