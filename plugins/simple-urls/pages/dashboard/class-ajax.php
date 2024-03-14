<?php
/**
 * Setting General - Ajax.
 *
 * @package Pages
 */

namespace LassoLite\Pages\Dashboard;

use LassoLite\Admin\Constant;
use LassoLite\Classes\Enum;
use LassoLite\Classes\Helper;
use LassoLite\Classes\Page;
use LassoLite\Classes\Setting;
use LassoLite\Classes\SURL;

/**
 * Setting General - Ajax.
 */
class Ajax {
	/**
	 * Declare "SURLs ajax requests" to WordPress.
	 */
	public function register_hooks() {
		add_action( 'wp_ajax_lasso_lite_dashboard_get_list', array( $this, 'lasso_lite_dashboard_get_list' ) );
		add_action( 'wp_ajax_lasso_lite_update_support', array( $this, 'lasso_lite_update_support' ) );
		add_action( 'wp_ajax_lasso_lite_update_customer_flow_enabled', array( $this, 'lasso_lite_update_customer_flow_enabled' ) );
	}

	/**
	 * Add a Field to a Product
	 */
	public function lasso_lite_dashboard_get_list() {
		Helper::verify_access_and_nonce();

		// phpcs:ignore
		$post    = Helper::POST();
		$page    = $post['page'] ?? 1;
		$keyword = $post['keyword'] ?? '';
		$list    = SURL::get_list( $keyword, $page, Enum::LIMIT_ON_PAGE );
		$output  = array();
		foreach ( $list as $surl ) {
			// ? BUILD GROUP LINKS
			$groups     = array();
			$group_list = wp_get_post_terms( $surl->get_id(), Constant::LASSO_CATEGORY, array( 'fields' => 'all' ) );
			foreach ( $group_list as $group ) {
				$group_url = Page::get_page_url( Helper::add_prefix_page( Enum::PAGE_GROUP_DETAIL ) ) . '&post_id=' . $group->term_id . '&subpage=' . Enum::SUB_PAGE_GROUP_URLS;
				$groups[]  = '<a href="' . $group_url . '" class="black hover-purple-text">' . esc_html( $group->name ) . '</a>';
			}
			$groups = implode( ', ', $groups );

			$output[] = array(
				'title'       => esc_html( $surl->get_post_title() ),
				'public_link' => $surl->get_public_url(),
				'link_detail' => $surl->get_link_detail(),
				'img_src'     => $surl->get_thumbnail_url(),
				'clicks'      => $surl->get_clicks(),
				'groups'      => $groups,
			);
		}
		$data['output']        = $output;
		$data['total']         = SURL::total( $keyword );
		$data['limit_on_page'] = Enum::LIMIT_ON_PAGE;
		$data['page']          = $page;

		wp_send_json_success( $data );
	}

	/**
	 * Turn on/off support_enabled flag
	 */
	public function lasso_lite_update_support() {
		Helper::verify_access_and_nonce();

		$post           = Helper::POST();
		$enable_support = $post['enable_support'] ?? Constant::DEFAULT_SETTINGS[ Enum::SUPPORT_ENABLED ];
		$enable_support = Helper::cast_to_boolean( $enable_support );

		Setting::set_setting( Enum::SUPPORT_ENABLED, $enable_support );
	}

	/**
	 * Turn on/off customer_flow_enabled flag
	 */
	public function lasso_lite_update_customer_flow_enabled() {
		Helper::verify_access_and_nonce();

		$post                  = Helper::POST();
		$customer_flow_enabled = $post['customer_flow_enabled'] ?? Constant::DEFAULT_SETTINGS[ Enum::CUSTOMER_FLOW_ENABLED ];
		$customer_flow_enabled = Helper::cast_to_boolean( $customer_flow_enabled );

		Setting::set_setting( Enum::CUSTOMER_FLOW_ENABLED, $customer_flow_enabled );
		wp_send_json_success();
	}
}
