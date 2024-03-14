<?php
/**
 * Group - Ajax.
 *
 * @package Pages
 */

namespace LassoLite\Pages\Groups;

use LassoLite\Admin\Constant;
use LassoLite\Classes\Affiliate_Link;
use LassoLite\Classes\Enum;
use LassoLite\Classes\Group;
use LassoLite\Classes\Helper;
use LassoLite\Classes\Page;
use LassoLite\Classes\SURL;

/**
 * Lasso Group - Ajax.
 */
class Ajax {
	/**
	 * Declare "Lasso ajax requests" to WordPress.
	 */
	public function register_hooks() {
		add_action( 'wp_ajax_lasso_lite_store_category', array( $this, 'lasso_lite_store_category' ) );
		add_action( 'wp_ajax_lasso_lite_group_get_list', array( $this, 'lasso_lite_group_get_list' ) );
		add_action( 'wp_ajax_lasso_lite_group_get_links', array( $this, 'lasso_lite_group_get_links' ) );
		add_action( 'wp_ajax_lasso_lite_delete_category', array( $this, 'lasso_lite_delete_category' ) );
	}

	/**
	 * Add or update a group
	 */
	public function lasso_lite_store_category() {
		Helper::verify_access_and_nonce();

		$data  = Helper::POST(); // phpcs:ignore

		if ( 0 === (int) $data['cat_id'] ) {
			$result = wp_insert_term(
				$data['cat_name'], // ? the term
				Constant::LASSO_CATEGORY, // ? the taxonomy
				array(
					'description' => $data['cat_desc'],
				)
			);
		} else {
			$result = wp_update_term(
				$data['cat_id'], // ? the term
				Constant::LASSO_CATEGORY, // ? the taxonomy
				array(
					'name'        => $data['cat_name'],
					'description' => $data['cat_desc'],
				)
			);
		}

		if ( $result && ! is_wp_error( $result ) ) {
			$result        = array(
				'status' => 1,
				'link'   => Page::get_page_url( Helper::add_prefix_page( Enum::PAGE_GROUP_DETAIL ) ) . '&post_id=' . $result['term_id'] . '&subpage=' . Enum::SUB_PAGE_GROUP_DETAIL,
				'data'   => $data,
				'cat_id' => $result['term_id'] ?? 0,
			);
			$result['msg'] = 'Group is saved.';
		} else {
			$result        = array(
				'status' => 0,
				'link'   => Page::get_page_url( Helper::add_prefix_page( Enum::PAGE_GROUP_DETAIL ) ),
				'data'   => $data,
				'cat_id' => 0,
			);
			$result['msg'] = 'Unexpected error!';
		}

		wp_send_json_success( $result );
	}

	/**
	 * Get list links linked to group
	 */
	public function lasso_lite_group_get_list() {
		Helper::verify_access_and_nonce();

		$post    = Helper::POST();
		$page    = $post['page'] ?? 1;
		$keyword = $post['keyword'] ?? '';
		$list    = Group::get_list( $page, $keyword, '1=1', Enum::LIMIT_ON_PAGE );
		$output  = array();
		foreach ( $list as $group ) {
			$output[] = array(
				'title'       => esc_html( $group->get_post_title() ),
				'description' => esc_html( $group->get_description() ),
				'count'       => intval( $group->get_count() ),
				'link_detail' => esc_html( $group->get_link_detail() ),
			);
		}

		$data['output']        = $output;
		$data['total']         = Group::total();
		$data['limit_on_page'] = Enum::LIMIT_ON_PAGE;
		$data['page']          = $page;

		wp_send_json_success( $data );
	}

	/**
	 * Get urls of group
	 */
	public function lasso_lite_group_get_links() {
		Helper::verify_access_and_nonce();

		$post     = Helper::POST();
		$group_id = $post['group_id'] ?? '';
		$group    = Group::get_by_id( $group_id );
		if ( $group ) {
			$urls   = SURL::get_urls_by_group( $group );
			$output = array();
			foreach ( $urls as $url ) {
				$lasso_url = Affiliate_Link::get_lasso_url( $url->get_id() );
				$output[]  = array(
					'post_id'         => intval( $url->get_id() ),
					'image_src'       => esc_html( $lasso_url->image_src ),
					'link_url_detail' => esc_html( $lasso_url->edit_link ),
					'link_name'       => esc_html( $lasso_url->name ),
					'permalink'       => esc_html( $lasso_url->permalink ),
				);
			}
			$data['output'] = $output;
			wp_send_json_success( $data );
		}
	}

	/**
	 * Delete group
	 */
	public function lasso_lite_delete_category() {
		Helper::verify_access_and_nonce();

		$post    = Helper::POST(); // phpcs:ignore
		$post_id = $post['cat_id'];

		wp_delete_term( $post_id, Constant::LASSO_CATEGORY );

		$redirect_link = add_query_arg(
			array(
				'post_type' => Constant::LASSO_POST_TYPE,
				'page'      => Helper::add_prefix_page( Enum::PAGE_GROUPS ),
			),
			admin_url( 'edit.php' )
		);

		wp_send_json_success(
			array(
				'data'          => 1,
				'post'          => $post,
				'redirect_link' => $redirect_link,
			)
		);
	}
}
