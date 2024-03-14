<?php
/**
 * Lasso Import Url - Ajax.
 *
 * @package Pages
 */

namespace LassoLite\Pages\Import_Urls;

use LassoLite\Classes\Helper;
use LassoLite\Classes\Import as Lasso_Import;
use LassoLite\Classes\Lasso_DB;
use LassoLite\Classes\Helper as Lasso_Helper;

use LassoLite\Classes\Processes\Import_All;
use LassoLite\Classes\Processes\Revert_All;

use LassoLite\Models\Model;


/**
 * Lasso Import Url - Ajax.
 */
class Ajax {
	/**
	 * Declare "Lasso ajax requests" to WordPress.
	 */
	public function register_hooks() {
		add_action( 'wp_ajax_lasso_lite_import', array( $this, 'lasso_lite_import' ) );

		add_action( 'wp_ajax_lasso_lite_import_all_links', array( $this, 'lasso_import_all_links' ) );
		add_action( 'wp_ajax_lasso_lite_revert_all_links', array( $this, 'lasso_revert_all_links' ) );

		add_action( 'wp_ajax_lasso_lite_import_single_link', array( $this, 'lasso_import_single_link' ) );
		add_action( 'wp_ajax_lasso_lite_revert_single_link', array( $this, 'lasso_revert_single_link' ) );

		add_action( 'wp_ajax_lasso_lite_is_import_all_processing', array( $this, 'lasso_is_import_all_processing' ) );
	}

	/**
	 * Import all links
	 */
	public function lasso_import_all_links() {
		Helper::verify_access_and_nonce();

		update_option( Import_All::OPTION, '1' );
		// phpcs:ignore
		$post          = Helper::POST();
		$filter_plugin = $post['filter_plugin'] ?? '';

		$import_all = new Import_All();
		$import_all->import( $filter_plugin );

		wp_send_json_success(
			array(
				'status' => true,
			)
		);
	} // @codeCoverageIgnore

	/**
	 * Revert all links
	 */
	public function lasso_revert_all_links() {
		Helper::verify_access_and_nonce();

		update_option( Revert_All::OPTION, '1' );
		// phpcs:ignore
		$post          = Helper::POST();
		$filter_plugin = $post['filter_plugin'] ?? '';

		$revert_all = new Revert_All();
		$revert_all->revert( $filter_plugin );

		wp_send_json_success(
			array(
				'status' => true,
			)
		);
	} // @codeCoverageIgnore

	/**
	 * Import a single post from other plugins into Lasso
	 */
	public function lasso_import_single_link() {
		Helper::verify_access_and_nonce();

		// phpcs:ignore
		$post             = Helper::POST();
		$import_id        = $post['import_id'] ?? '';
		$post_type        = $post['post_type'] ?? '';
		$post_title       = $post['post_title'] ?? '';
		$import_permalink = $post['import_permalink'] ?? '';

		if ( empty( $import_id ) || empty( $post_type ) ) {
			wp_send_json_success(
				array(
					'status' => false,
				)
			);
		}

		$lasso_import = new Lasso_Import();

		list($status, $import_data) = $lasso_import->process_single_link_data( $import_id, $post_type, $post_title, $import_permalink );

		wp_send_json_success(
			array(
				'status' => $status,
				'data'   => $import_data,
			)
		);
	} // @codeCoverageIgnore

	/**
	 * Revert a single link from Lasso to other plugins
	 */
	public function lasso_revert_single_link() {
		Helper::verify_access_and_nonce();

		// phpcs:ignore
		$post          = Helper::POST();
		$import_id     = $post['import_id'] ?? '';
		$import_source = $post['import_source'] ?? '';
		$post_type     = $post['post_type'] ?? '';
		$lasso_import  = new Lasso_Import();

		$status = $lasso_import->process_single_link_revert( $import_id, $import_source, $post_type );

		wp_send_json_success(
			array(
				'status' => $status,
				'data'   => $post,
			)
		);
	} // @codeCoverageIgnore

	/**
	 * Check if bulk import is processing.
	 */
	public function lasso_is_import_all_processing() {
		wp_send_json_success(
			array(
				'is_processing' => ( new Import_All() )->get_total_remaining() > 0, // ? Check total remaining instead of method "is_process_running()" to cover the case "wp-cron is not working"
			)
		);
	}

	/**
	 * Ajax handler for import page
	 */
	public function lasso_lite_import() {
		Helper::verify_access_and_nonce();

		$lasso_db    = new Lasso_DB();
		$post        = Helper::POST(); // phpcs:ignore
		$page        = $post['pageNumber'] ?? 1;
		$search      = $post['search'] ?? '';
		$search_body = Helper::esc_like_query( $search );
		$filter      = $post['filter'] ?? '';

		$total['plugins']   = $lasso_db->get_import_plugins(); // ? All plugin sources
		$search_query       = Model::get_wpdb()->prepare( 'AND ( post_title LIKE %s OR post_name LIKE %s )', $search_body, $search_body );
		$search_term_string = '' !== $search ? $search_query : '';
		$search_term_string = '' !== $filter ? $search_term_string . Model::get_wpdb()->prepare( ' AND BASE.import_source LIKE %s ', Helper::esc_like_query( $filter ) ) : $search_term_string;
		$sql                = $lasso_db->get_importable_urls_query( true, $search_term_string );
		$posts_sql          = Lasso_Helper::paginate( $sql, $page );
		$posts              = Model::get_results( $posts_sql );
		$total['total']     = Model::get_count( $sql );

		foreach ( $posts as $p ) {
			$p->post_title = Lasso_Helper::format_post_title( $p->post_title ?? '' );
			$p->shortcode  = '';

			// ? Get import target permalinks
			$p = Lasso_Helper::format_importable_data( $p );
		}

		wp_send_json_success(
			array(
				'status' => 1,
				'data'   => $posts,
				'search' => $search,
				'filter' => $filter,
				'total'  => $total,
				'page'   => $page,
				'post'   => $post,
			)
		);
	}

}
