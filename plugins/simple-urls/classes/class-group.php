<?php
/**
 * Models
 *
 * @package Models
 */

namespace LassoLite\Classes;

use LassoLite\Admin\Constant;
use LassoLite\Classes\Helper;
use LassoLite\Models\Model;

/**
 * Model
 */
class Group extends LL_Object {

	/**
	 * Get groups query
	 *
	 * @param int    $page   Page number.
	 * @param string $search Search text.
	 * @param string $where  Where statement. Default to '1=1'.
	 * @param string $limit  Limit.
	 * @return Group[]
	 */
	public static function get_list( $page = null, $search = '', $where = '1=1', $limit = Enum::LIMIT_ON_PAGE ) {

		$terms              = Model::get_wpdb()->terms;
		$term_taxonomy      = Model::get_wpdb()->term_taxonomy;
		$term_relationships = Model::get_wpdb()->term_relationships;
		$posts              = Model::get_wpdb()->posts;
		if ( ! empty( $search ) ) {
			$search_body = Helper::esc_like_query( $search );
			$search      = Model::get_wpdb()->prepare( 'AND t.name LIKE %s', $search_body );
		}
		$sql = "
			SELECT
				t.term_id as `post_id`,
				t.term_id,
				t.name as post_title,
				t.slug,
				CASE
					WHEN LENGTH(tt.description) > 237
						THEN CONCAT(SUBSTRING(tt.description, 1, 237), '...')
					ELSE tt.description
				END as description,
				SUM(
					CASE
						WHEN p.ID IS NOT NULL
							THEN 1
						ELSE 0
					END
				) as `count`
			FROM
					" . $terms . ' as t
				INNER JOIN
					' . $term_taxonomy . " as tt
						ON t.term_id = tt.term_id
						AND tt.taxonomy = '" . Constant::LASSO_CATEGORY . "'
				LEFT JOIN
					" . $term_relationships . ' as tr
						ON tt.term_taxonomy_id = tr.term_taxonomy_id
				LEFT JOIN
					' . $posts . " as p
						ON tr.object_id = p.ID
						AND p.post_type = '" . Constant::LASSO_POST_TYPE . "'
						AND p.post_status = 'publish'
			WHERE
				" . $where . '
				' . $search . '
			GROUP BY
				t.term_id,
				t.name,
				t.slug
		';

		if ( ! is_null( $page ) ) {
			$sql = Helper::paginate( $sql, $page, $limit );
		}

		$results = Model::get_results( $sql );
		$list    = array();
		foreach ( $results as $result ) {
			$list[] = new self( $result );
		}
		return $list;
	}

	/**
	 * Get groups query
	 *
	 * @param string $search Search text.
	 * @param string $where  Where statement. Default to '1=1'.
	 * @return Group[]
	 */
	public static function total( $search = '', $where = '1=1' ) {

		$terms              = Model::get_wpdb()->terms;
		$term_taxonomy      = Model::get_wpdb()->term_taxonomy;
		$term_relationships = Model::get_wpdb()->term_relationships;
		$posts              = Model::get_wpdb()->posts;
		if ( ! empty( $search ) ) {
			$search = Helper::esc_like_query( $search );
			$search = Model::get_wpdb()->prepare( 'AND t.name LIKE %s', $search );
		}
		$sql = "
			SELECT
				t.term_id as `post_id`,
				t.term_id,
				t.name as post_title,
				t.slug,
				CASE
					WHEN LENGTH(tt.description) > 237
						THEN CONCAT(SUBSTRING(tt.description, 1, 237), '...')
					ELSE tt.description
				END as description,
				SUM(
					CASE
						WHEN p.ID IS NOT NULL
							THEN 1
						ELSE 0
					END
				) as `count`
			FROM
					" . $terms . ' as t
				INNER JOIN
					' . $term_taxonomy . " as tt
						ON t.term_id = tt.term_id
						AND tt.taxonomy = '" . Constant::LASSO_CATEGORY . "'
				LEFT JOIN
					" . $term_relationships . ' as tr
						ON tt.term_taxonomy_id = tr.term_taxonomy_id
				LEFT JOIN
					' . $posts . " as p
						ON tr.object_id = p.ID
						AND p.post_type = '" . Constant::LASSO_POST_TYPE . "'
						AND p.post_status = 'publish'
			WHERE
				" . $where . '
				' . $search . '
			GROUP BY
				t.term_id,
				t.name,
				t.slug
		';

		return Model::get_count( $sql );
	}

	/**
	 * Get edit URL
	 */
	public function get_link_detail() {
		return Page::get_page_url( Helper::add_prefix_page( Enum::PAGE_GROUP_DETAIL ) . '&post_id=' . $this->get_post_id() ) . '&subpage=' . Enum::SUB_PAGE_GROUP_URLS;
	}

	/**
	 * Get by ID
	 *
	 * @param int $id ID.
	 *
	 * @return Group|null
	 */
	public static function get_by_id( $id ) {
		$term_data = get_term( $id, Constant::LASSO_CATEGORY );
		if ( ! empty( $term_data ) && ! is_wp_error( $term_data ) ) {
			return new self( $term_data );
		}
		return null;
	}

	/**
	 * Get group id
	 *
	 * @return int
	 */
	public function get_id() {
		return (int) $this->get_term_id();
	}

	/**
	 * Get total links of group
	 *
	 * @return int|mixed
	 */
	public function get_total_links() {
		$terms              = Model::get_wpdb()->terms;
		$term_taxonomy      = Model::get_wpdb()->term_taxonomy;
		$term_relationships = Model::get_wpdb()->term_relationships;
		$posts              = Model::get_wpdb()->posts;

		$sql    = '
			SELECT
				count(*) as `count`
			FROM
					' . $terms . ' as t
				INNER JOIN
					' . $term_taxonomy . " as tt
						ON t.term_id = tt.term_id
						AND tt.taxonomy = '" . Constant::LASSO_CATEGORY . "'
				LEFT JOIN
					" . $term_relationships . ' as tr
						ON tt.term_taxonomy_id = tr.term_taxonomy_id
				INNER JOIN
					' . $posts . " as p
						ON tr.object_id = p.ID
			WHERE
				t.term_id = %d
				AND t.slug = %s 
				AND p.post_type = '" . Constant::LASSO_POST_TYPE . "' 
				AND p.post_status = 'publish' 
				AND tt.taxonomy = '" . Constant::LASSO_CATEGORY . "'";
		$sql    = Model::prepare( $sql, $this->get_id(), $this->get_slug() );
		$result = Model::get_row( $sql );
		return $result->count;
	}
}
